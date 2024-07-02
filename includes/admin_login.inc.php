<?php

session_start();
require_once '../connect/db.php';
require_once '../config-url.php';

$username = $_POST['username'];
$pwd = $_POST['pwd'];
$hashedPwd = password_hash($pwd, PASSWORD_BCRYPT);

// Check if $username or $pwd is empty
if (empty($username) || empty($pwd)) {
    // Redirect with an error message
    header("Location: " . BASE_URL . "admin.php?error=empty_input");
    exit;
}

$sql = 'SELECT `username`,  `pwd` FROM `admin` WHERE `username` = ?;';

$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the username exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $matchPwd = $row['pwd'];

        if (password_verify($pwd, $matchPwd)) {
            // Password matches, set session variable and redirect
            $_SESSION['admin_username'] = $username;
            header("Location: " . BASE_URL . "admin_pages.php?success=logged_in");
            exit;
        } else {
            // Password doesn't match
            header("Location: " . BASE_URL . "admin.php?error=pwd_doesnt_match");
            exit;
        }
    } else {
        // Username doesn't exist
        header("Location: " . BASE_URL . "admin.php?error=username_doesnt_exist");
        exit;
    }
} else {
    // Handle statement preparation error
    // echo 'Error preparing statement.';
    header("Location: " . BASE_URL . "admin.php?error=failed_to_login");
}
