<?php

require_once '../../connect/db.php';
require_once '../../config-url.php';
require_once 'update_journal_page.php';

$section_name = $_POST['section_name'];
$section_id = $_GET['section_id'];
$image_src = 'images/' . $_FILES['my_image']['name'];
$image_text = $_POST['image_text'];
$page_num = $_GET['page_num'];

// Error handler, checks to see if content is empty
if (empty($section_name) || empty($image_text)) {
    header("Location: " . BASE_URL . "/admin_pages.php?error=empty_input&page_num=" . $page_num);
    exit;
} elseif ($section_id == '') {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_section_id&page_num=" . $page_num);
    exit;
} elseif (empty($page_num) || !isset($_GET['page_num']) || $_GET['page_num'] == "add_page") {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_page_num");
    exit;
} elseif (empty($_FILES['my_image']['name'])) {
    // Check if a video file is uploaded
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_image&page_num=" . $page_num);
    exit;
} else {
    // Check if the uploaded file is an image
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']; // Add more types if needed

    if (!in_array($_FILES['my_image']['type'], $allowedTypes)) {
        header("Location: " . BASE_URL . "/admin_pages.php?error=invalid_image&page_num=" . $page_num);
        exit;
    }
}

if (isset($_FILES['my_image'])) {
    $uploadDir = '../../images/'; // Path to the "images" directory
    $uploadFile = $uploadDir . basename($_FILES['my_image']['name']);

    // Check for file type and size
    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 104857600; // 100MB (adjust as needed)

    if (in_array($_FILES['my_image']['type'], $allowedFileTypes) && $_FILES['my_image']['size'] <= $maxFileSize) {
        if (move_uploaded_file($_FILES['my_image']['tmp_name'], $uploadFile)) {
            // echo "File is valid and was successfully uploaded.\n";

            // Updates the order_num to fit the new section 
            update_journal_page($section_id, $page_num, $mysqli);

            // Needs to be plus one to add it to the new section
            $new_section_id = $section_id + 1;

            $sql = "INSERT INTO `journal_page`(`section_type`, `section_name`, `order_num`, `page_num`) VALUES ('image', ?, ?, ?)";
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
                        header("Location: " . BASE_URL . "/admin_pages.php?error=error_adding_image&page_num=$page_num");
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
                $sql = "INSERT INTO `image`(`image_src`, `image_text`, `section_id`) VALUES (?, ?, ?);";
                $stmt = $mysqli->prepare($sql);

                if ($stmt) {
                    // Bind your parameters
                    $stmt->bind_param("ssi", $image_src, $image_text, $latestID);

                    // Execute the statement
                    if ($stmt->execute()) {
                        if ($stmt->affected_rows > 0) {
                            // echo "New section added!";
                            header("Location: " . BASE_URL . "/admin_pages.php?success=added_image&page_num=" . $page_num);
                        } else {
                            // echo "Error adding section";
                            header("Location: " . BASE_URL . "/admin_pages.php?error=error_adding_image&page_num=$page_num");
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
        } else {
            // echo "Upload failed.\n";
            // echo "Error: " . $_FILES['my_image']['error'];
            header("Location: " . BASE_URL . "/admin_pages.php?error=couldnt_move_file&page_num=" . $page_num);
        }
    } else {
        echo "Invalid file type or size. Please upload a valid image file (max size: 100MB).";
    }
} else {
    echo 'No file was uploaded.';
}