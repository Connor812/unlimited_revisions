<?php

require_once ("../config-url.php");

if (empty ($_GET["user_id"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=no_user_id");
    exit;
}

$user_id = $_GET["user_id"];

if (empty ($_GET["username"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=no_username");
    exit;
}

$user_name = $_GET["username"];

if (empty ($_GET["userdata_name"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=no_userdata_name&username=$user_name");
    exit;
}

$userdata_name = $_GET["userdata_name"];

if (empty ($_POST["note"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=no_note&username=$user_name");
    exit;
}

$note = $_POST["note"];

require_once ("../connect/db.php");

// Insert statement
$sql = "INSERT INTO `user_input_notes` (`user_id`, `userdata_name`, `note`) VALUES (?, ?, ?);";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    header("Location: " . BASE_URL . "admin_users.php?error=note_prepare&username=$user_name");
    $stmt->close();
    exit;
}

// Bind parameters to the prepared statement
$stmt->bind_param("iss", $user_id, $userdata_name, $note);

// Execute the statement
if ($stmt->execute()) {
    // Insertion successful
    header("Location: " . BASE_URL . "admin_users.php?success=added_note&username=$user_name");
    $stmt->close();
    exit;
} else {
    // Insertion failed
    header("Location: " . BASE_URL . "admin_users.php?error=note_execute&username=$user_name");
    $stmt->close();
    exit;
}