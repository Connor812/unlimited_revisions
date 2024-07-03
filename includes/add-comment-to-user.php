<?php

header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: POST"); // Allow POST requests
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow Content-Type and Authorization headers
header('Content-Type: application/json');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Get the JSON payload from the request
$json = file_get_contents('php://input');

// Decode the JSON into an associative array
$data = json_decode($json, true);

$user_id = $data['user_id'];
$user_input_section_id = $data['user_input_section_id'];
$userdata_name = $data['userdata_name'];
$user_input = $data['user_input'];
$is_there_data = $data['is_there_data'];
$user_comment_id = $data['user_comment_id'];
$stage = $data['stage'];

require_once("../connect/db.php");

if ($is_there_data == "true") {

    $sql = "UPDATE `user_input_comments` SET `user_comment` = ? WHERE id = ?;";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        $message = array(
            "status" => "error",
            "message" => "Failed to prepare statement",
        );
        echo json_encode($message);
        exit;
    }

    $stmt->bind_param("si", $user_input, $user_comment_id);

    // ? This checks the execute statement
    if ($stmt->execute()) {
        // ? Check the number of affected rows
        if ($stmt->affected_rows > 0) {
            // * Successful update
            $message = array(
                "status" => "success",
                "message" => "Comment updated successfully",
            );
            echo json_encode($message);
            $stmt->close();
            exit;
        } else {
            // ! No rows were updated
            $message = array(
                "status" => "error",
                "message" => "No rows were updated",
            );
            echo json_encode($message);
            $stmt->close();
            exit;
        }
    } else {
        // ! Failed update
        $message = array(
            "status" => "error",
            "message" => "Failed to update comment",
        );
        echo json_encode($message);
        $stmt->close();
        exit;
    }
} else {

    $sql = "INSERT INTO `user_input_comments` (`user_id`, `userdata_name_reference`, `user_comment`, `stage`) VALUES (?, ?, ?, ?);";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        $message = array(
            "status" => "error",
            "message" => "Failed to prepare statement",
        );
        echo json_encode($message);
        exit;
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param("issi", $user_id, $userdata_name, $user_input, $stage);

    // Execute the statement
    if ($stmt->execute()) {
        // Insertion successful
        $message = array(
            "status" => "success",
            "message" => "Comment added successfully",
        );
        echo json_encode($message);
        $stmt->close();
        exit;
    } else {
        // Insertion failed
        $message = array(
            "status" => "error",
            "message" => "Failed to add comment",
        );
        echo json_encode($message);
        $stmt->close();
        exit;
    }
}
