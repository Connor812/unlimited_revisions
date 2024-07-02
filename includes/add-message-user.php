<?php

require_once ("../config-url.php");

if (!isset($_POST["user_id"])) {
    header("Location: " . BASE_URL . "profile.php?error=no_id");
    exit;
}

$user_id = $_POST["user_id"];

if (!isset($_POST["from"])) {
    header("Location: " . BASE_URL . "profile.php?error=from");
    exit;
}

$from = $_POST["from"];

if (!isset($_POST["message"])) {
    header("Location: " . BASE_URL . "profile.php?error=message");
    exit;
}

$message = $_POST["message"];

require_once ("../connect/db.php");

// Begin transaction
$mysqli->begin_transaction();

try {
    // Insert statement
    $sql = "INSERT INTO `messages` (`user_id`, `who_from`,`message`, `user_read_status`) VALUES (?, ?, ?, 'read');";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        header("Location: " . BASE_URL . "profile.php?error=prepare");
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

        header("Location: " . BASE_URL . "profile.php?success=message_inserted");
        exit;
    } else {
        // Insertion failed
        $stmt->close();
        $mysqli->rollback();

        header("Location: " . BASE_URL . "profile.php?error=message_execute");
        exit;
    }
} catch (Exception $e) {
    $mysqli->rollback();
    throw $e;
}
