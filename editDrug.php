<?php
$pageTitle = "Edit Drug Details";
$section = "editDrug";

include 'header.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

require_once("db_connection.php"); 

$error_message = null;
$drug = null;

// Get drug ID
if (!isset($_GET['drug_id']) || !is_numeric($_GET['drug_id'])) {
    set_flash_message('error', 'Invalid drug ID.');
    header("Location: dashboard.php");
    exit;
}

$drug_id = (int)$_GET['drug_id'];

// Get categories from database
$categories = [];
$catQuery = "SELECT category_id, category_name FROM drug_categories ORDER BY category_name";
$catResult = $conn->query($catQuery);
if ($catResult) {
    while ($cat = $catResult->fetch_assoc()) {
        $categories[$cat['category_id']] = $cat['category_name'];
    }
}

// Fetch existing drug data
$query = "SELECT drug_id, drug_name, category_id, description, image_url FROM drug_details WHERE drug_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $drug_id);
$stmt->execute();
$result = $stmt->get_result();
$drug = $result->fetch_assoc();
$stmt->close();

if (!$drug) {
    set_flash_message('error', 'Drug not found.');
    header("Location: dashboard.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $drug_name = sanitize_input($_POST['drug_name']);
    $category_id = (int)$_POST['category'];
    $description = sanitize_input($_POST['description']);

    // Validate inputs
    if (empty($drug_name) || empty($category_id) || empty($description)) {
        $error_message = "All fields are required.";
    } else {
        $image_path = $drug['image_url']; // Keep existing image by default
        
        // Check if new image was uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "img/"; 
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($_FILES['image']['type'], $allowed_types)) {
                $error_message = "Invalid image type. Allowed: JPG, PNG, GIF, WEBP";
            } elseif ($_FILES['image']['size'] > $max_size) {
                $error_message = "Image too large. Maximum size: 5MB";
            } else {
                // Generate unique filename
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $new_image_name = uniqid('drug_') . '.' . $extension;
                $new_image_path = $upload_dir . $new_image_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path)) {
                    // Delete old image if it exists and is different
                    if ($drug['image_url'] && file_exists($drug['image_url']) && $drug['image_url'] !== $new_image_path) {
                        unlink($drug['image_url']);
                    }
                    $image_path = $new_image_path;
                } else {
                    $error_message = "Error uploading new image.";
                }
            }
        }
        
        if (!$error_message) {
            // Update database using prepared statement
            $query = "UPDATE drug_details SET drug_name = ?, category_id = ?, description = ?, image_url = ? WHERE drug_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sissi", $drug_name, $category_id, $description, $image_path, $drug_id);
            
            if ($stmt->execute()) {
                set_flash_message('success', 'Drug "' . $drug_name . '" updated successfully!');
                header("Location: dashboard.php"); 
                exit;
            } else {
                $error_message = "Error updating database: " . $stmt->error;
            }
            $stmt->close();
        }
    }
    
    // Update drug array with posted values for form persistence
    $drug['drug_name'] = $drug_name;
    $drug['category_id'] = $category_id;
    $drug['description'] = $description;
}

$conn->close();
?>

<div class="form-container">
    <div class="form-header">
        <h2>✏️ Edit Drug</h2>
        <p>Update the drug information below</p>
    </div>
    
    <?php if ($error_message): ?>
        <div class="alert alert-error">
            <span class="alert-icon">⚠️</span>
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <form action="" method="post" enctype="multipart/form-data" class="drug-form">
        <div class="form-group">
            <label for="drug_name">
                <span class="label-icon">💊</span> Drug Name
            </label>
            <input type="text" name="drug_name" id="drug_name" 
                   placeholder="Enter drug name" 
                   value="<?php echo htmlspecialchars($drug['drug_name']); ?>" 
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
                            <?php echo ($drug['category_id'] == $id) ? 'selected' : ''; ?>>
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
                      placeholder="Enter detailed description..."
                      required><?php echo htmlspecialchars($drug['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label>
                <span class="label-icon">🖼️</span> Current Image
            </label>
            <div class="current-image">
                <?php if ($drug['image_url'] && file_exists($drug['image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($drug['image_url']); ?>" alt="Current drug image">
                <?php else: ?>
                    <p>No image available</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="image">
                <span class="label-icon">📤</span> Upload New Image (Optional)
            </label>
            <div class="file-upload-wrapper">
                <input type="file" name="image" id="image" accept="image/*">
                <p class="file-help">Leave empty to keep current image. Accepted: JPG, PNG, GIF, WEBP (Max: 5MB)</p>
            </div>
            <div id="image-preview" class="image-preview"></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <span>💾</span> Update Drug
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

