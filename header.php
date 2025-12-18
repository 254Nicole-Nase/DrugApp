<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Drug Info Center'; ?></title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php session_start(); ?>
    
    <div class="app-wrapper">
        <header class="main-header">
            <div class="header-content">
                <div class="logo">
                    <span class="logo-icon">💊</span>
                    <h1>Drug Info Center</h1>
                </div>
                
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <div class="user-info">
                        <span class="user-avatar">👤</span>
                        <span class="user-name">Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
                    </div>
                <?php endif; ?>
            </div>
        </header>
        
        <?php if (isset($_SESSION['admin_id'])): ?>
        <nav class="main-nav">
            <ul class="nav-list">
                <li class="nav-item <?php echo ($section == 'dashboard') ? 'active' : ''; ?>">
                    <a href="dashboard.php">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item <?php echo ($section == 'addDrug') ? 'active' : ''; ?>">
                    <a href="addDrug.php">
                        <span class="nav-icon">➕</span>
                        <span class="nav-text">Add Drug</span>
                    </a>
                </li>
                <li class="nav-item <?php echo ($section == 'drugCategories') ? 'active' : ''; ?>">
                    <a href="drugCategories.php">
                        <span class="nav-icon">📁</span>
                        <span class="nav-text">Categories</span>
                    </a>
                </li>
                <li class="nav-item <?php echo ($section == 'changePassword') ? 'active' : ''; ?>">
                    <a href="changePassword.php">
                        <span class="nav-icon">🔐</span>
                        <span class="nav-text">Change Password</span>
                    </a>
                </li>
                <li class="nav-item nav-logout">
                    <a href="logout.php">
                        <span class="nav-icon">🚪</span>
                        <span class="nav-text">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
        
        <main class="main-content">
            <div class="container">
