<?php

session_start();
require_once("../connect/db.php");
require_once("../config-url.php");

$old_username = $_SESSION["admin_username"];
$new_username = $_POST["username"];

if (empty($username)) {
    header("Location: " . BASE_URL . "admin_change_pwd.php?error=empty_input");
    exit;
}

$sql = "UPDATE admin SET `username` = ? WHERE username = ?;";

$stmt = $mysqli->prepare($sql);

if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $new_username, $old_username);

    // Execute the statement
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // echo "Username updated successfully.";
        $_SESSION["admin_username"] = $new_username;
        header("Location: " . BASE_URL . "admin_change_pwd.php?success=updated_username");
        exit;
    } else {
        // echo "No rows were updated. The username may not exist or the new and old usernames are the same.";
        header("Location: " . BASE_URL . "admin_change_pwd.php?error=failed_update_username");
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle statement preparation error
    echo 'Error preparing statement.';
}

// Close the database connection if needed
$mysqli->close();


