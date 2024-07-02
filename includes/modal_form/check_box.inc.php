<?php

require_once '../../connect/db.php';
require_once '../../config-url.php';
require_once 'update_journal_page.php';
require_once("../get_column_count.php");

$section_name = $_POST['section_name'];
$section_id = $_GET['section_id'];
$page_num = $_GET['page_num'];

// print_r($_POST) . "<br>";

// Error Handlers -------------------------------------------------------------------->

// Checks for blank name
if (empty($_POST["section_name"])) {
    // echo "section name empty";
    header("Location: " . BASE_URL . "/admin_pages.php?error=name_blank&page_num=" . $page_num);
    exit;
}

if (empty($page_num) || !isset($_GET['page_num']) || $page_num == 'add_page') {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_page_num");
    exit;
}

$item_types = $_POST['item_type'];
$placeholder_texts = $_POST['placeholder_text'];
$item_titles = $_POST['item_title'];

if (empty($item_types)) {
    // echo "section input empty";
    header("Location: " . BASE_URL . "/admin_pages.php?error=needs_input&page_num=" . $page_num);
    exit;
}

// This checks to see if the input data names already exist in the database
for ($i = 0; $i < count($item_types); $i++) {
    $type = $item_types[$i];
    $placeholder_text = $placeholder_texts[$i];
    $title = $item_titles[$i];

    // Checks to see if any of the inputs are empty
    if (empty($type) || empty($title)) {
        // echo "works for empty";
        header("Location: " . BASE_URL . "/admin_pages.php?error=empty_input&page_num=" . $page_num);
        exit;
    }
}

// Starts updating the data base <---------------------------------------------------------------------------------------------------->
// Updates the order_num to fit the new section 
update_journal_page($section_id, $page_num, $mysqli);

// Needs to be plus one to add it the the new section
$new_section_id = $section_id + 1;

$sql = "INSERT INTO `journal_page`(`section_type`, `section_name`, `order_num`, `page_num`) VALUES ('click_list', ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    // Bind your parameters
    $stmt->bind_param("sii", $section_name, $new_section_id, $page_num);

    // Execute the statement
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // echo "New section added!";
        } else {
            // echo "Error adding section";
            header("Location: " . BASE_URL . "/admin_pages.php?error=error_adding_check_box&page_num=" . $page_num);
            exit;
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
    $sql = "INSERT INTO `click_list`(`section_id`) VALUES (?);";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        // Bind your parameters
        $stmt->bind_param("i", $latestID);

        // Execute the statement
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // echo "New section added!";
            } else {
                // echo "Error adding section";
                header("Location: " . BASE_URL . "/admin_pages.php?error=error_adding_check_box&page_num=" . $page_num);
                exit;
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

// SQL query to get the latest ID
$sql = "SELECT MAX(id) FROM click_list;";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $latestID = $row['MAX(id)'];

    // Check if the arrays exist and are not empty
    if (isset($_POST['item_type']) && is_array($_POST['item_type'])) {
        $itemTypes = $_POST['item_type'];
        $itemTitles = $_POST['item_title'];
        $placeholderTexts = $_POST['placeholder_text'];

        // Loop through the arrays
        for ($i = 0; $i < count($itemTypes); $i++) {
            $type = $itemTypes[$i];
            $title = $itemTitles[$i];
            $placeholderText = !empty($placeholderTexts[$i]) ? $placeholderTexts[$i] : null;
            $num_of_columns = get_column_count($mysqli);
            // Process the data
            // echo "Type: $type, Title: $title, Userdata Placeholder Text: $placeholderText<br>";

            // Define the column type based on $type
            $columnType = ($type === 'textarea') ? 'TEXT DEFAULT NULL' : 'BOOLEAN DEFAULT FALSE';

            // Construct SQL to add the column
            $sqlAddColumn = "ALTER TABLE user_input ADD COLUMN `$num_of_columns` $columnType;";

            // Prepare and execute the SQL statement to add the column
            if ($mysqli->query($sqlAddColumn)) {
                // echo "New column added!";
            } else {
                // echo "Error adding column: " . $mysqli->error;
                header("Location: " . BASE_URL . "/admin_pages.php?error=error_adding_check_box&page_num=" . $page_num);
                exit;
            }

            $sql = "INSERT INTO `click_list_items`(`item_type`, `item_title`, `placeholder_text`, `item_userdata_name`, `click_list_id`) VALUES (?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);

            if ($stmt) {
                // Bind your parameters
                $stmt->bind_param("ssssi", $type, $title, $placeholderText, $num_of_columns, $latestID);

                // Execute the statement
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        // echo "New section added!";
                        header("Location: " . BASE_URL . "/admin_pages.php?success=added_click_list&page_num=$page_num#$section_id");
                    } else {
                        // echo "Error adding section";
                        header("Location: " . BASE_URL . "/admin_pages.php?error=error_adding_check_box&page_num=" . $page_num);
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
        }
    }
} else {
    echo "No results found.";
}