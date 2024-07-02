<?php
session_start();
require_once "../connect/db.php";
require_once "../config-url.php";

$username = $_POST["username"];
$pwd = $_POST["pwd"];

// echo "Username: " . $username . " Password: " . $pwd;

// Error Handlers for the login form
if (empty($username)) {
    header("Location: " . BASE_URL . "/login.php?error=empty_username");
    exit;
} elseif (empty($pwd)) {
    header("Location: " . BASE_URL . "/login.php?error=empty_pwd");
    exit;
}

// Check if username exists 

$sql = "SELECT username FROM users WHERE username = ?;";

$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: ../login.php?error=username_doesnt_exist");
        exit;
    }

    $stmt->close();
} else {
    echo "Prepare failed: " . $mysqli->error;
    exit;
}

// If username exists, get the password,check if it matches input password and start a session and take back to index.php

$sql = "SELECT * FROM users WHERE username = ?;";

$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
} else {
    echo "Prepare failed: " . $mysqli->error;
    exit;
}

$row = $result->fetch_assoc();
$storedPassword = $row['password'];
$userId = $row['id'];

// Check if the stored password matches the input password
if (password_verify($pwd, $storedPassword)) {
    // Passwords match, start a session and redirect
    $_SESSION['username'] = $username; // Store username in the session
    $_SESSION['user_id'] = $userId;
    header("Location: ../journal.php?success=logged_in"); // Redirect to the desired page
    exit;
} else {
    header("Location: ../login.php?error=invalid_password");
    exit;
}