<?php

require_once("../../config-url.php");

if (!isset($_GET["page_num"]) || !isset($_GET["order_num"])) {
    header("Location: " . BASE_URL . "admin_pages.php?error=no_page_num#$order_num");
    exit;
}

$bullet_points = $_POST["bullet_point"];

$page_num = $_GET["page_num"];
$order_num = $_GET["order_num"];

require_once("../../connect/db.php");


$mysqli->begin_transaction();

try {


    foreach ($bullet_points as $bullet_id => $bullet_content) {

        // ? Update statement
        $sql = "UPDATE `bullet_point` SET `bullet_content` = ? WHERE id = ?;";

        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            header("Location: " . BASE_URL . "admin_pages.php?page_num=$page_num&error=failed_prepare_statement#$order_num");
            $stmt->close();
            exit;
        }

        $stmt->bind_param("si", $bullet_content, $bullet_id);

        // ? This checks the execute statement
        if ($stmt->execute()) {
            // ? Check the number of affected rows
            if ($stmt->affected_rows > 0) {
                // * Successful update
                // echo "updated";
            } else {
                // ! No rows were updated
                // echo "no updated";
            }
        } else {
            // ! Failed update
            echo "failed to execute";
            $stmt->close();
            exit;
        }
    }

    $mysqli->commit();
    header("Location: " . BASE_URL . "admin_pages.php?page_num=$page_num&success=updated_bullet#$order_num");
    $stmt->close();
    exit;

} catch (Exception $error) {
    $mysqli->rollback();
    header("Location: " . BASE_URL . "admin_pages.php?page_num=$page_num&error=failed_update_bullet#$order_num");
    exit;
}