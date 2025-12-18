<?php
$pageTitle = "Change Password";
$section = "changePassword";

include 'header.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

require_once("db_connection.php"); 

$error_message = null;
$success_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif (strlen($new_password) < 6) {
        $error_message = "New password must be at least 6 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "New passwords do not match.";
    } else {
        // Get current password hash from database
        $query = "SELECT password FROM administrators WHERE admin_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $_SESSION['admin_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();
        
        if ($admin && password_verify($current_password, $admin['password'])) {
            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password
            $updateQuery = "UPDATE administrators SET password = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $hashed_password, $_SESSION['admin_id']);
            
            if ($stmt->execute()) {
                $success_message = "Password changed successfully!";
            } else {
                $error_message = "Error updating password. Please try again.";
            }
            $stmt->close();
        } else {
            $error_message = "Current password is incorrect.";
        }
    }
}

$conn->close();
?>

<div class="form-container">
    <div class="form-header">
        <h2>🔐 Change Password</h2>
        <p>Update your account password</p>
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
    
    <form action="" method="post" class="password-form">
        <div class="form-group">
            <label for="current_password">
                <span class="label-icon">🔑</span> Current Password
            </label>
            <input type="password" name="current_password" id="current_password" 
                   placeholder="Enter your current password" required>
        </div>
        
        <div class="form-group">
            <label for="new_password">
                <span class="label-icon">🔒</span> New Password
            </label>
            <input type="password" name="new_password" id="new_password" 
                   placeholder="Enter new password (min. 6 characters)" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">
                <span class="label-icon">🔒</span> Confirm New Password
            </label>
            <input type="password" name="confirm_password" id="confirm_password" 
                   placeholder="Confirm your new password" required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <span>💾</span> Update Password
            </button>
            <a href="dashboard.php" class="btn btn-secondary">
                <span>❌</span> Cancel
            </a>
        </div>
    </form>
</div>
</div>

<?php include 'footer.php';?>

