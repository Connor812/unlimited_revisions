<?php
session_start();
require_once "../connect/db.php";
require_once "../config-url.php";

$user_id = $_SESSION['user_id'];
$page_num = $_GET["page_num"];

if (!isset ($_SESSION['user_id'])) {
    exit;
}

$user_input = $_POST;
// var_dump($user_input);

// Initialize an empty array to store the variables
$variables = [];

// Loop over the data
foreach ($user_input as $key => $value) {
    $typeKey = $key . "_type";
    if (isset ($user_input[$typeKey])) {
        $type = $user_input[$typeKey];
    } else {
        // Default to "textarea" if no type is provided
        $type = "textarea";
    }

    // Exclude keys that end with "_type" from the output
    if (substr($key, -5) === "_type") {
        continue;
    }

    $variables[] = [
        "key" => $key,
        "value" => $value,
        "type" => $type
    ];
}

// Now $variables contains an array of items with keys, values, and types
foreach ($variables as $variable) {
    $key = $variable["key"];
    $value = $variable["value"];
    $type = $variable["type"];

    // Use $key, $value, and $type as needed
    // echo "Key: $key, Value: $value, Type: $type\n <br>";
}

foreach ($variables as $variable) {
    $user_input_name = $variable['key'];
    $user_input = $variable['value'];
    $type = $variable['type'];
    $params;

    if ($type == 'textarea') {
        $params = 'si';
    } else {
        $params = 'ii';
    }

    // echo $variable['key'] . "<br>";
    // echo $variable['value'] . "<br>";
    // echo $variable['type'] . "<br>";

    $sql = "SELECT user_id FROM `user_input` WHERE user_id = ?;";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $sql = "UPDATE `user_input` SET `$user_input_name` = ? WHERE user_id = ?;";

            $stmt = $mysqli->prepare($sql);

            if ($stmt) {
                $stmt->bind_param($params, $user_input, $user_id);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    // The update was successful
                    // echo "Update successful!";
                    header("Location: " . BASE_URL . "/journal.php?success=success&page_num=$page_num");
                } else {
                    // No rows were updated
                    // echo "No rows were updated.";
                    header("Location: " . BASE_URL . "/journal.php?success=success&page_num=$page_num");
                }
            } else {
                // Error in preparing the statement
                // echo "Error in preparing the statement.";
                header("Location: " . BASE_URL . "/journal.php?error=error_sending_user_info&page_num=$page_num");
            }
        } else {
            $sql = "INSERT INTO `user_input` ($user_input_name, `user_id`) VALUES (?, ?);";

            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param($params, $user_input, $user_id);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    // echo "Update successful!";
                    header("Location: " . BASE_URL . "/journal.php?success=success&page_num=$page_num");
                } else {
                    // No rows were updated
                    echo "No rows were updated.";
                }
            }
        }
    }
}