<?php
$pageTitle = "Add Drug Details";
$section = "addDrug";

include 'header.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

require_once("db_connection.php"); 

$success_message = null;
$error_message = null;

// Get categories from database
$categories = [];
$catQuery = "SELECT category_id, category_name FROM drug_categories ORDER BY category_name";
$catResult = $conn->query($catQuery);
if ($catResult) {
    while ($cat = $catResult->fetch_assoc()) {
        $categories[$cat['category_id']] = $cat['category_name'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $drug_name = sanitize_input($_POST['drug_name']);
    $category_id = (int)$_POST['category'];
    $description = sanitize_input($_POST['description']);

    // Validate inputs
    if (empty($drug_name) || empty($category_id) || empty($description)) {
        $error_message = "All fields are required.";
    } else {
        // File upload handling
        $upload_dir = "img/"; 
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error_message = "Please select an image to upload.";
        } elseif (!in_array($_FILES['image']['type'], $allowed_types)) {
            $error_message = "Invalid image type. Allowed: JPG, PNG, GIF, WEBP";
        } elseif ($_FILES['image']['size'] > $max_size) {
            $error_message = "Image too large. Maximum size: 5MB";
        } else {
            // Generate unique filename to prevent overwrites
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = uniqid('drug_') . '.' . $extension;
            $image_path = $upload_dir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                // Insert into database using prepared statement
                $query = "INSERT INTO drug_details (drug_name, category_id, description, image_url) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("siss", $drug_name, $category_id, $description, $image_path);
                
                if ($stmt->execute()) {
                    set_flash_message('success', 'Drug "' . $drug_name . '" added successfully!');
                    header("Location: dashboard.php"); 
                    exit;
                } else {
                    $error_message = "Error saving to database: " . $stmt->error;
                    // Delete uploaded file if db insert failed
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }
                $stmt->close();
            } else {
                $error_message = "Error uploading image. Please try again.";
            }
        }
    }
}

$conn->close();
?>

<div class="form-container">
    <div class="form-header">
        <h2>➕ Add New Drug</h2>
        <p>Fill in the details below to add a new drug to the database</p>
    </div>
    
    <?php if ($error_message): ?>
        <div class="alert alert-error">
            <span class="alert-icon">⚠️</span>
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <span class="alert-icon">✅</span>
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    
    <form action="" method="post" enctype="multipart/form-data" class="drug-form">
        <div class="form-group">
            <label for="drug_name">
                <span class="label-icon">💊</span> Drug Name
            </label>
            <input type="text" name="drug_name" id="drug_name" 
                   placeholder="Enter drug name" 
                   value="<?php echo isset($_POST['drug_name']) ? htmlspecialchars($_POST['drug_name']) : ''; ?>" 
                   required>
        </div>

        <div class="form-group">
            <label for="category">
                <span class="label-icon">📁</span> Category
            </label>
            <select name="category" id="category" required>
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $id => $name): ?>
                    <option value="<?php echo $id; ?>" 
                            <?php echo (isset($_POST['category']) && $_POST['category'] == $id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">
                <span class="label-icon">📝</span> Description
            </label>
            <textarea name="description" id="description" rows="5" 
                      placeholder="Enter detailed description of the drug, its effects, and other relevant information..."
                      required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="image">
                <span class="label-icon">🖼️</span> Drug Image
            </label>
            <div class="file-upload-wrapper">
                <input type="file" name="image" id="image" accept="image/*" required>
                <p class="file-help">Accepted formats: JPG, PNG, GIF, WEBP (Max: 5MB)</p>
            </div>
            <div id="image-preview" class="image-preview"></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <span>💾</span> Add Drug
            </button>
            <a href="dashboard.php" class="btn btn-secondary">
                <span>❌</span> Cancel
            </a>
        </div>
    </form>
</div>

<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    const file = e.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>

</div>

<?php include 'footer.php';?>
