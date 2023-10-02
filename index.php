<?php
$pageTitle = "Drug Info Center";
$section = null;

include 'header.php';

//session_start();

// Check if the user is already logged in; if so, redirect to the dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once("db_connection.php"); 
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to check administrator credentials
    $query = "SELECT admin_id, username FROM administrators WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($admin_id, $admin_username);
    $stmt->fetch();
    $stmt->close();

    // Check if the query returned a matching administrator
    if ($admin_id) {
        // Store administrator data in session
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['admin_username'] = $admin_username;

        header("Location: dashboard.php");
        exit;
    } else {
        // Display an error message if login fails
        $login_error = "Invalid username or password";
    }
}

?>

    <h2>Administrator Login</h2>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
    // Display login error message
    if (isset($login_error)) {
        echo '<p style="color: red;">' . $login_error . '</p>';
    }
    ?>
    </div>

    <?php include 'footer.php';?>