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

$sql = "DELETE FROM `permission` WHERE `page_num` = ?;";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    header("Location: " . BASE_URL . "admin_users.php?error=failed_delete_permissions");
    $stmt->close();
    exit;
}

$stmt->bind_param("i", $page_num);

if ($stmt->execute()) {
    // * Successful update
    header("Location: " . BASE_URL . "admin_users.php?success=deleted_permissions");
    $stmt->close();
    exit;
} else {
    // ! Failed update
    header("Location: " . BASE_URL . "admin_users.php?error=failed_delete_permissions");
    $stmt->close();
    exit;
}