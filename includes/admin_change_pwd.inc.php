<?php

session_start();
require_once "../connect/db.php";
require_once("../config-url.php");

// Get the current username from the session
$username = $_SESSION["admin_username"];

// Get the new password and re-entered password from the form submission
$newPassword = $_POST["pwd"];
$reEnteredPassword = $_POST["re_pwd"];

// Check if the new password is not empty
if (empty($newPassword)) {
    header("Location: " . BASE_URL . "admin_change_pwd.php?error=empty_password");
    exit;
}

// Check if the passwords match
if ($newPassword !== $reEnteredPassword) {
    header("Location: " . BASE_URL . "admin_change_pwd.php?error=passwords_not_match");
    exit;
}

// Hash the new password
$hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the password in the database
$sql = "UPDATE admin SET `pwd` = ? WHERE `username` = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $hashedNewPassword, $username);

    // Execute the statement
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // echo "Password updated successfully.";
        header("Location: " . BASE_URL . "admin_change_pwd.php?success=updated_password");
        exit;
    } else {
        // echo "No rows were updated. The username may not exist or the new and old passwords are the same.";
        header("Location: " . BASE_URL . "admin_change_pwd.php?error=failed_update_password");
        exit;
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle statement preparation error
    echo 'Error preparing statement.';
}

// Close the database connection if needed
$mysqli->close();