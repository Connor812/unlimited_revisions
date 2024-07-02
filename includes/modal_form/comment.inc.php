<?php

require_once "../../connect/db.php";
require_once "../../config-url.php";
require_once 'update_journal_page.php';
require_once("../get_column_count.php");

// Get input data from POST and GET
$section_name = $_POST['section_name'];
$section_id = $_GET['section_id'];
$page_num = $_GET['page_num'];
$comment_placeholder = $_POST['comment_placeholder'];
$column_count = get_column_count($mysqli);

// Output for debugging
// echo $section_name . "<br>";
// echo $section_id . "<br>";
// echo $page_num . "<br>";
// echo $comment_userdata_name . "<br>";
// echo $comment_placeholder . "<br>";

// Error handlers ---------------------------------------------------------------------------------------------------------------------------->
// Error handler, checks to see if content is empty
if (empty($comment_placeholder) || empty($section_name)) {
    header("Location: " . BASE_URL . "/admin_pages.php?error=empty_input&page_num=" . $page_num);
    exit;
} elseif ($section_id == '') {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_section_id&page_num=" . $page_num);
    exit;
} elseif (empty($page_num) || !isset($_GET['page_num']) || $_GET['page_num'] == "add_page") {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_page_num");
    exit;
}

// Update the order_num to fit the new section
update_journal_page($section_id, $page_num, $mysqli);

// Need to add one to the section_id to place it in the new section
$new_section_id = $section_id + 1;

// Insert a new section into the journal_page table
$sql = "INSERT INTO `journal_page`(`section_type`, `section_name`, `order_num`, `page_num`) VALUES ('comment', ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sii", $section_name, $new_section_id, $page_num);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // echo "New section added!";
        } else {
            // echo "Error adding section";
            header("Location: " . BASE_URL . "/admin_pages.php?error=failed_adding_comment&page_num=" . $page_num);
        }
    } else {
        echo "Error executing the statement: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error preparing the statement: " . $mysqli->error;
}

// SQL query to get the latest ID from journal_page
$sql = "SELECT MAX(id) FROM journal_page;";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $latestID = $row['MAX(id)'];

    // Add a new column to the user_input table
    $sqlAddColumn = "ALTER TABLE user_input ADD COLUMN `$column_count` TEXT DEFAULT NULL;";
    if ($mysqli->query($sqlAddColumn)) {
        // echo "New column added!";
    } else {
        // echo "Error adding column: " . $mysqli->error;
        header("Location: " . BASE_URL . "/admin_pages.php?error=failed_adding_comment&page_num=" . $page_num);
    }

    // Insert data into the comment table
    $sql = "INSERT INTO `comment`(`comment_userdata_name`, `comment_placeholder`, `section_id`) VALUES (?,?,?);";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssi", $column_count, $comment_placeholder, $latestID);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // echo "New section added!";
                header("Location: " . BASE_URL . "/admin_pages.php?success=added_comment&page_num=$page_num#$section_id");
            } else {
                // echo "Error adding section";
                header("Location: " . BASE_URL . "/admin_pages.php?error=failed_adding_comment&page_num=$page_num");
            }
        } else {
            echo "Error executing the statement: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $mysqli->error;
    }
} else {
    echo "No results found.";
}