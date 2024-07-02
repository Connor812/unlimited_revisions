<?php

require_once("../../config-url.php");

$section_name = $_POST["section_name"];
$comment_placeholder = $_POST["comment_placeholder"];
$page_num = $_GET["page_num"];
$order_num = $_GET["order_num"];

if (!isset($_GET["page_num"]) || !isset($_GET["order_num"])) {
    header("Location: " . BASE_URL . "/admin_pages.php?error=missing_section_params");
    exit;
} elseif (empty($section_name) || empty($comment_placeholder)) {
    header("Location: " . BASE_URL . "/admin_edit/edit_comment.php?error=empty_input&page_num=$page_num&order_num=$order_num");
    exit;
} else {
    // Include necessary files and initialize database connection
    require_once '../../connect/db.php';

    // Prepare and execute the SQL statement
    $sql = "UPDATE journal_page jp
    JOIN comment c ON jp.id = c.section_id
    SET jp.section_name = ?,
        c.comment_placeholder = ?
    WHERE jp.page_num = ? AND jp.order_num = ?";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssii", $section_name, $comment_placeholder, $page_num, $order_num);

        // Execute the query
        $stmt->execute();

        // Check for success
        if ($stmt->affected_rows > 0) {
            header("Location: " . BASE_URL . "/admin_pages.php?success=updated_success&page_num=$page_num&#$order_num");
            // Close the statement
            $stmt->close();
            exit;
        } else {
            header("Location: " . BASE_URL . "/admin_pages.php?error=updated_same&&page_num=$page_num#$order_num");
            // Close the statement
            $stmt->close();
            exit;
        }

    } else {
        echo "Prepare failed: " . $mysqli->error;
    }

    // Close the database connection
    $mysqli->close();
}
