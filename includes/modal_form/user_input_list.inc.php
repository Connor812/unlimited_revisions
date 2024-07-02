<?php

require_once "../../connect/db.php";
require_once "../../config-url.php";
require_once 'update_journal_page.php';
require_once ("../get_column_count.php");

// Get input data from POST and GET
$section_name = $_POST['section_name'];
$section_id = $_GET['section_id'];
$page_num = $_GET['page_num'];


// Error handlers ---------------------------------------------------------------------------------------------------------------------------->
// Error handler, checks to see if content is empty
if ($section_id == '') {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_section_id&page_num=" . $page_num);
    exit;
} elseif (empty ($page_num) || !isset ($_GET['page_num']) || $_GET['page_num'] == "add_page") {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_page_num");
    exit;
}

// Update the order_num to fit the new section
update_journal_page($section_id, $page_num, $mysqli);

// Need to add one to the section_id to place it in the new section
$new_section_id = $section_id + 1;
$section_name = "user_input_list";
// Insert a new section into the journal_page table
$sql = "INSERT INTO `journal_page`(`section_type`, `section_name`, `order_num`, `page_num`) VALUES ('user_input_list', ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sii", $section_name, $new_section_id, $page_num);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            header("Location: " . BASE_URL . "/admin_pages.php?success=added_user_input_list&page_num=$page_num");
            exit;
        } else {
            // echo "Error adding section";
            header("Location: " . BASE_URL . "/admin_pages.php?error=no__rows_added&page_num=" . $page_num);
            exit;
        }
    } else {
        header("Location: " . BASE_URL . "/admin_pages.php?error=failed_execute&page_num=" . $page_num);
        exit;
    }
    $stmt->close();
} else {
    header("Location: " . BASE_URL . "/admin_pages.php?error=failed_prepare&page_num=" . $page_num);
    exit;
}