<?php
$pageTitle = "Drug Info Center";
$section = null;

include 'header.php';

// Check if the user is already logged in; if so, redirect to the dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$login_error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once("db_connection.php"); 
    
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password']; // Don't sanitize password before verification

    // SQL query to check administrator credentials (using prepared statement)
    $query = "SELECT admin_id, username, password FROM administrators WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    // Check if user exists and verify password
    if ($admin && password_verify($password, $admin['password'])) {
        // Store administrator data in session
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['username'];
        
        set_flash_message('success', 'Welcome back, ' . $admin['username'] . '!');
        header("Location: dashboard.php");
        exit;
    } else {
        // Display an error message if login fails
        $login_error = "Invalid username or password";
    }
    
    $conn->close();
}
?>

<div class="login-container">
    <div class="login-box">
        <div class="login-header">
            <div class="login-icon">🔐</div>
            <h2>Administrator Login</h2>
            <p>Enter your credentials to access the dashboard</p>
        </div>
        
        <?php if (isset($login_error)): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <?php echo $login_error; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="" class="login-form">
            <div class="form-group">
                <label for="username">
                    <span class="label-icon">👤</span> Username
                </label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required>
            </div>
            
            <div class="form-group">
                <label for="password">
                    <span class="label-icon">🔑</span> Password
                </label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Sign In
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php';?>
