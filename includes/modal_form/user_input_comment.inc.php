<?php

require_once ("../../config-url.php");

$page_num = ($_GET["page_num"]) ? $_GET["page_num"] : 1;

if (empty ($_POST["user_id"])) {
    return "Error";
}

$user_id = $_POST["user_id"];

if (empty ($_POST["userdata_name"])) {
    return "Error";
}

$userdata_name = $_POST["userdata_name"];

if (empty ($_POST["user_comment"])) {
    return "Error";
}

$user_comment = $_POST["user_comment"];

require_once ("../../connect/db.php");

// Insert statement
$sql = "INSERT INTO `user_input_comments` (`user_id`, `userdata_name_reference`, `user_comment`) VALUES (?, ?, ?);";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    $stmt->close();
    return "Error";
}

// Bind parameters to the prepared statement
$stmt->bind_param("iss", $user_id, $userdata_name, $user_comment);

// Execute the statement
if ($stmt->execute()) {
    // Insertion successful
    $stmt->close();
    return "Success";
} else {
    // Insertion failed
    $stmt->close();
    return "Error";
}