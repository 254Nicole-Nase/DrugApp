<?php
/**
 * Firebase Configuration
 * 
 * To use Firebase instead of MySQL:
 * 1. Go to https://console.firebase.google.com/
 * 2. Create a new project (or use existing)
 * 3. Go to Project Settings > Service Accounts
 * 4. Click "Generate new private key" and download the JSON file
 * 5. Rename it to 'firebase-credentials.json' and place it in this folder
 * 6. Go to Firestore Database and create a database
 * 7. Update the values below
 */

// Firebase Project Configuration
define('FIREBASE_PROJECT_ID', 'your-project-id');  // From Firebase Console
define('FIREBASE_API_KEY', 'your-api-key');        // From Project Settings > General
define('FIREBASE_DATABASE_URL', 'https://your-project-id.firebaseio.com');

// Path to service account credentials JSON file
define('FIREBASE_CREDENTIALS_PATH', __DIR__ . '/firebase-credentials.json');

/**
 * Firebase REST API Helper Class
 * Uses Firebase REST API (no SDK required)
 */
class FirebaseDB {
    private $projectId;
    private $accessToken;
    private $baseUrl;
    
    public function __construct() {
        $this->projectId = FIREBASE_PROJECT_ID;
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
        $this->accessToken = $this->getAccessToken();
    }
    
    /**
     * Get access token from service account
     */
    private function getAccessToken() {
        if (!file_exists(FIREBASE_CREDENTIALS_PATH)) {
            throw new Exception("Firebase credentials file not found. Please download it from Firebase Console.");
        }
        
        $credentials = json_decode(file_get_contents(FIREBASE_CREDENTIALS_PATH), true);
        
        // Create JWT
        $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        
        $now = time();
        $payload = base64_encode(json_encode([
            'iss' => $credentials['client_email'],
            'sub' => $credentials['client_email'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/datastore'
        ]));
        
        $signature = '';
        openssl_sign(
            "$header.$payload",
            $signature,
            $credentials['private_key'],
            'SHA256'
        );
        $signature = base64_encode($signature);
        
        $jwt = "$header.$payload.$signature";
        
        // Exchange JWT for access token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }
    
    /**
     * Make API request to Firestore
     */
    private function request($method, $path, $data = null) {
        $url = $this->baseUrl . $path;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json'
        ]);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return ['code' => $httpCode, 'data' => json_decode($response, true)];
    }
    
    /**
     * Get all documents from a collection
     */
    public function getCollection($collection) {
        $response = $this->request('GET', "/$collection");
        
        if ($response['code'] !== 200) {
            return [];
        }
        
        $documents = [];
        if (isset($response['data']['documents'])) {
            foreach ($response['data']['documents'] as $doc) {
                $id = basename($doc['name']);
                $documents[$id] = $this->parseDocument($doc);
            }
        }
        
        return $documents;
    }
    
    /**
     * Get a single document
     */
    public function getDocument($collection, $documentId) {
        $response = $this->request('GET', "/$collection/$documentId");
        
        if ($response['code'] !== 200) {
            return null;
        }
        
        return $this->parseDocument($response['data']);
    }
    
    /**
     * Add a new document
     */
    public function addDocument($collection, $data) {
        $firestoreData = $this->formatForFirestore($data);
        $response = $this->request('POST', "/$collection", ['fields' => $firestoreData]);
        
        if ($response['code'] === 200) {
            return basename($response['data']['name']);
        }
        
        return null;
    }
    
    /**
     * Update a document
     */
    public function updateDocument($collection, $documentId, $data) {
        $firestoreData = $this->formatForFirestore($data);
        $response = $this->request('PATCH', "/$collection/$documentId", ['fields' => $firestoreData]);
        
        return $response['code'] === 200;
    }
    
    /**
     * Delete a document
     */
    public function deleteDocument($collection, $documentId) {
        $response = $this->request('DELETE', "/$collection/$documentId");
        return $response['code'] === 200;
    }
    
    /**
     * Parse Firestore document format to simple array
     */
    private function parseDocument($doc) {
        $result = [];
        if (isset($doc['fields'])) {
            foreach ($doc['fields'] as $key => $value) {
                $result[$key] = $this->parseValue($value);
            }
        }
        return $result;
    }
    
    /**
     * Parse Firestore value
     */
    private function parseValue($value) {
        if (isset($value['stringValue'])) return $value['stringValue'];
        if (isset($value['integerValue'])) return (int)$value['integerValue'];
        if (isset($value['doubleValue'])) return (float)$value['doubleValue'];
        if (isset($value['booleanValue'])) return $value['booleanValue'];
        if (isset($value['nullValue'])) return null;
        if (isset($value['arrayValue'])) {
            $arr = [];
            foreach ($value['arrayValue']['values'] ?? [] as $v) {
                $arr[] = $this->parseValue($v);
            }
            return $arr;
        }
        return null;
    }
    
    /**
     * Format data for Firestore
     */
    private function formatForFirestore($data) {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $result[$key] = ['stringValue' => $value];
            } elseif (is_int($value)) {
                $result[$key] = ['integerValue' => (string)$value];
            } elseif (is_float($value)) {
                $result[$key] = ['doubleValue' => $value];
            } elseif (is_bool($value)) {
                $result[$key] = ['booleanValue' => $value];
            } elseif (is_null($value)) {
                $result[$key] = ['nullValue' => null];
            } elseif (is_array($value)) {
                $result[$key] = ['arrayValue' => ['values' => array_map(function($v) {
                    return ['stringValue' => (string)$v];
                }, $value)]];
            }
        }
        return $result;
    }
}

// Helper functions to match MySQL interface
function firebase_get_drugs() {
    $db = new FirebaseDB();
    return $db->getCollection('drugs');
}

function firebase_get_drug($id) {
    $db = new FirebaseDB();
    return $db->getDocument('drugs', $id);
}

function firebase_add_drug($data) {
    $db = new FirebaseDB();
    return $db->addDocument('drugs', $data);
}

function firebase_update_drug($id, $data) {
    $db = new FirebaseDB();
    return $db->updateDocument('drugs', $id, $data);
}

function firebase_delete_drug($id) {
    $db = new FirebaseDB();
    return $db->deleteDocument('drugs', $id);
}

function firebase_get_categories() {
    $db = new FirebaseDB();
    return $db->getCollection('categories');
}
?>

