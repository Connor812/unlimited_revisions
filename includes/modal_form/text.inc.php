<?php

require_once "../../connect/db.php";
require_once '../../config-url.php';
require_once "update_journal_page.php";

$section_name = $_POST["section_name"];
$text_content = $_POST["text_content"];
$section_id = $_GET['section_id'];
$page_num = $_GET['page_num'];

// Error handler, checks to see if content is empty
if (empty($section_name) || empty($text_content)) {
    header("Location: " . BASE_URL . "/admin_pages.php?error=empty_input&page_num=" . $page_num);
    exit;
} elseif ($section_id == '') {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_section_id&page_num=" . $page_num);
    exit;
} elseif (empty($page_num) || !isset($_GET['page_num']) || $_GET['page_num'] == "add_page") {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_page_num");
    exit;
}

// Updates the order_num to fit the new section 
update_journal_page($section_id, $page_num, $mysqli);

// Needs to be plus one to add it the the new section
$new_section_id = $section_id + 1;

$sql = "INSERT INTO `journal_page`(`section_type`, `section_name`, `order_num`, `page_num`) VALUES ('text', ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    // Bind your parameters
    $stmt->bind_param("sii", $text_content, $new_section_id, $page_num);

    // Execute the statement
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // echo "New section added!";
        } else {
            // echo "Error adding section";
            header("Location: " . BASE_URL . "/admin_pages.php?error=error_adding_text?page_num=$page_num");
        }
    } else {
        // Handle execution error
        echo "Error executing the statement: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle statement preparation error
    echo "Error preparing the statement: " . $mysqli->error;
}


// SQL query to get the latest ID
$sql = "SELECT MAX(id) FROM journal_page;";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $latestID = $row['MAX(id)'];
    $sql = "INSERT INTO `text`(`text_content`, `section_id`) VALUES (?, ?);";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        // Bind your parameters
        $stmt->bind_param("si", $text_content, $latestID);

        // Execute the statement
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // echo "New section added!";
                header("Location: " . BASE_URL . "/admin_pages.php?success=added_text&page_num=" . $page_num);
            } else {
                header("Location: " . BASE_URL . "/admin_pages.php?error=error_adding_text?page_num=$page_num");
                exit;
                // echo "Error adding section";
            }
        } else {
            // Handle execution error
            echo "Error executing the statement: " . $stmt->error;
        }
        // Close the statement
        $stmt->close();
    } else {
        // Handle statement preparation error
        echo "Error preparing the statement: " . $mysqli->error;
    }
} else {
    echo "No results found.";
}
