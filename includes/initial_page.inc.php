<?php

require_once("../config-url.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "admin_users.php?error=invalid_request_method");
    exit;
}

if (!isset($_POST["page_num"]) || empty($_POST["page_num"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=no_page_num");
    exit;
}

$page_num = $_POST["page_num"];

require_once("../connect/db.php");

// ? Update statement
$sql = "UPDATE `initial_page` SET `page_num` = ? WHERE id = 1;";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    header("Location: " . BASE_URL . "admin_users.php?error=failed_to_update_initial_page");
    $stmt->close();
    exit;
}

$stmt->bind_param("i", $page_num);

// ? This checks the execute statement
if ($stmt->execute()) {
    // ? Check the number of affected rows
    if ($stmt->affected_rows > 0) {
        // * Successful update
        header("Location: " . BASE_URL . "admin_users.php?success=updated_initial_page");
        $stmt->close();
        exit;
    } else {
        // ! No rows were updated
        header("Location: " . BASE_URL . "admin_users.php?error=failed_to_update_initial_page");
        $stmt->close();
        exit;
    }
} else {
    // ! Failed update
    header("Location: " . BASE_URL . "admin_users.php?error=failed_to_update_initial_page");
    $stmt->close();
    exit;
}