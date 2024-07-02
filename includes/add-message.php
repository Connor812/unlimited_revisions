<?php

require_once ("../config-url.php");

if (!isset($_POST["user_id"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=no_id");
    exit;
}

$user_id = $_POST["user_id"];

if (!isset($_GET["username"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=no_username");
    exit;
}

$username = $_GET["username"];

if (!isset($_GET["page_num"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=page_num");
    exit;
}

$page_num = $_GET["page_num"];

if (!isset($_POST["from"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=from&username=$username&page_num=$page_num&user_id=$user_id");
    exit;
}

$from = $_POST["from"];

if (!isset($_POST["message"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=message&username=$username&page_num=$page_num&user_id=$user_id");
    exit;
}

$message = $_POST["message"];

require_once ("../connect/db.php");

// Begin transaction
$mysqli->begin_transaction();

try {
    // Insert statement
    $sql = "INSERT INTO `messages` (`user_id`, `who_from`,`message`, `admin_read_status`) VALUES (?, ?, ?, 'read');";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        header("Location: " . BASE_URL . "admin_users.php?error=prepare&username=$username&page_num=$page_num&user_id=$user_id");
        $stmt = $mysqli->prepare($sql);
        exit;
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param("iss", $user_id, $from, $message);

    // Execute the statement
    if ($stmt->execute()) {
        // Insertion successful
        $stmt->close();
        $mysqli->commit();

        header("Location: " . BASE_URL . "admin_users.php?success=message_inserted&username=$username&page_num=$page_num&user_id=$user_id");
        exit;
    } else {
        // Insertion failed
        $stmt->close();
        $mysqli->rollback();

        header("Location: " . BASE_URL . "admin_users.php?error=message_execute&username=$username&page_num=$page_num&user_id=$user_id");
        exit;
    }
} catch (Exception $e) {
    $mysqli->rollback();
    throw $e;
}
