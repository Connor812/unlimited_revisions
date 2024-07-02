<?php

require_once ("../config-url.php");
// Get the raw POST data
$postData = file_get_contents('php://input');
// Decode the JSON data
$data = json_decode($postData, true);

$user_id = $data['userId'];
$comment = $data['comment'];
$userdata_name = $data['userdataName'];
$stage = $data['stage'];

require_once ("../connect/db.php");

// Insert statement
$sql = "INSERT INTO `user_input_comments` (`user_id`, `userdata_name_reference`, `user_comment`, `stage`) VALUES (?, ?, ?, ?);";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    $stmt->close();
    echo json_encode(["status" => "Prepare Error"]);
    exit;
}

// Bind parameters to the prepared statement
$stmt->bind_param("issi", $user_id, $userdata_name, $comment, $stage);

// Execute the statement
if ($stmt->execute()) {
    // Insertion successful
    $stmt->close();
    echo json_encode(["status" => "Success"]);
    exit;
} else {
    // Insertion failed
    $stmt->close();
    echo json_encode(["status" => "Execute Error", "error" => $stmt->error]);
    exit;
}