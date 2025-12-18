<?php
$pageTitle = "Drug Details";
$section = "view";

include 'header.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

require_once("db_connection.php"); 

$drug = null;
$error_message = null;

if (isset($_GET['drug_id']) && is_numeric($_GET['drug_id'])) {
    $drugId = (int)$_GET['drug_id'];

    // Query to retrieve drug details by drug_id using prepared statement
    $query = "SELECT d.drug_id, d.drug_name, d.description, d.image_url, c.category_name
              FROM drug_details d
              JOIN drug_categories c ON d.category_id = c.category_id
              WHERE d.drug_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $drugId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $drug = $result->fetch_assoc();
    } else {
        $error_message = "Error fetching drug details.";
    }

    $stmt->close();
} else {
    $error_message = "Drug ID not provided or invalid.";
}

$conn->close();
?>

<div class="details-container">
    <?php if ($error_message): ?>
        <div class="alert alert-error">
            <span class="alert-icon">⚠️</span>
            <?php echo $error_message; ?>
        </div>
        <div class="back-link">
            <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    <?php elseif ($drug): ?>
        <div class="drug-details-card">
            <div class="drug-details-header">
                <div class="back-nav">
                    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
                </div>
                <div class="drug-actions-top">
                    <a href="editDrug.php?drug_id=<?php echo $drug['drug_id']; ?>" class="btn btn-edit">✏️ Edit</a>
                    <a href="deleteDrug.php?drug_id=<?php echo $drug['drug_id']; ?>" 
                       class="btn btn-delete"
                       onclick="return confirm('Are you sure you want to delete this drug?');">🗑️ Delete</a>
                </div>
            </div>
            
            <div class="drug-details-content">
                <div class="drug-image-section">
                    <div class="drug-image-wrapper">
                        <?php if ($drug['image_url'] && file_exists($drug['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($drug['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($drug['drug_name']); ?>" />
                        <?php else: ?>
                            <div class="no-image">
                                <span>🖼️</span>
                                <p>No image available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="drug-info-section">
                    <h1 class="drug-title"><?php echo htmlspecialchars($drug['drug_name']); ?></h1>
                    
                    <div class="drug-meta">
                        <span class="category-badge">
                            <span class="badge-icon">📁</span>
                            <?php echo htmlspecialchars($drug['category_name']); ?>
                        </span>
                    </div>
                    
                    <div class="drug-description">
                        <h3>📋 Description</h3>
                        <p><?php echo nl2br(htmlspecialchars($drug['description'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-error">
            <span class="alert-icon">⚠️</span>
            Drug not found.
        </div>
        <div class="back-link">
            <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    <?php endif; ?>
</div>
</div>

<?php include 'footer.php'; ?>
