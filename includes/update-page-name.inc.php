<?php
require_once("../connect/db.php");
require_once("../config-url.php");

$update_page_name = $_POST["page_name"];
$page_num = $_POST["page_num"];

// Check if the page_num exists in the page_name table
$check_sql = "SELECT COUNT(*) AS num_rows FROM page_name WHERE page_num = ?";
$check_stmt = $mysqli->prepare($check_sql);

if ($check_stmt) {
    $check_stmt->bind_param("i", $page_num);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $row = $check_result->fetch_assoc();
    $num_rows = $row['num_rows'];

    if ($num_rows > 0) {
        // Page_num exists, perform update
        $update_sql = "UPDATE page_name SET page_name = ? WHERE page_num = ?";
        $update_stmt = $mysqli->prepare($update_sql);

        if ($update_stmt) {
            $update_stmt->bind_param("si", $update_page_name, $page_num);
            $update_stmt->execute();

            // Check for success or failure
            if ($update_stmt->affected_rows > 0) {
                header("Location: " . BASE_URL . "/admin_pages.php?success=updated_page_name&page_num=$page_num");
                exit();
            } else {
                header("Location: " . BASE_URL . "/admin_pages.php?error=update_failed&page_num=$page_num");
                exit();
            }
        } else {
            header("Location: " . BASE_URL . "/admin_pages.php?error=update_preparation_failed&page_num=$page_num");
            exit();
        }
    } else {
        // Page_num doesn't exist, perform insert
        $insert_sql = "INSERT INTO page_name (page_num, page_name) VALUES (?, ?)";
        $insert_stmt = $mysqli->prepare($insert_sql);

        if ($insert_stmt) {
            $insert_stmt->bind_param("is", $page_num, $update_page_name);
            $insert_stmt->execute();

            // Check for success or failure
            if ($insert_stmt->affected_rows > 0) {
                header("Location: " . BASE_URL . "/admin_pages.php?success=created_page_name&page_num=$page_num");
                exit();
            } else {
                header("Location: " . BASE_URL . "/admin_pages.php?error=insert_failed&page_num=$page_num");
                exit();
            }
        } else {
            header("Location: " . BASE_URL . "/admin_pages.php?error=insert_preparation_failed&page_num=$page_num");
            exit();
        }
    }

} else {
    header("Location: " . BASE_URL . "/admin_pages.php?error=check_preparation_failed&page_num=$page_num");
    exit();
}