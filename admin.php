<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/admin_login.css" />
</head>

<body>

    <?php

    require_once("admin-header.php");
    require_once("includes/admin_errors.inc.php");
    require_once 'config-url.php';

    session_start();

    if (isset($_SESSION['admin_username'])) {
        header("Location: " . BASE_URL . "admin_pages.php");
    }

    ?>

    <form class="admin-login-form" action="includes/admin_login.inc.php" method="post">
        <div class="input-container">
            <h1>Admin Login</h1>
            <div class="input-section">
                <label for="username-input">Username</label>
                <input id="username-input" class="admin-input" type="text" name="username" placeholder="Username" />
            </div>
            <div class="input-section">
                <label for="pwd-input">Password</label>
                <input id="pwd-input" class="admin-input" type="password" name="pwd" placeholder="Password" />
            </div>
            <div class="submit-btn-container">
                <button class="submit-btn" type="submit">Login</button>
            </div>
        </div>
    </form>
    <script type="text/javascript" src="<?php echo BASE_URL ?>/js/admin.js"></script>

</body>

</html>