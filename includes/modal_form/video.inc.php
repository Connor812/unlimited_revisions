<?php

require_once "../../connect/db.php";
require_once "../../config-url.php";
require_once "update_journal_page.php";

$section_name = $_POST['section_name'];
$section_id = $_GET['section_id'];
$video_src = 'videos/' . $_FILES['my_video']['name'];
$page_num = $_GET['page_num'];

// Error handler, checks to see if content is empty
if (empty($section_name)) {
    header("Location: " . BASE_URL . "/admin_pages.php?error=empty_input&page_num=$page_num");
    exit;
} elseif ($section_id == '') {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_section_id&page_num=$page_num");
    exit;
} elseif (empty($page_num) || !isset($_GET['page_num']) || $_GET['page_num'] == "add_page") {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_page_num");
    exit;
} elseif (empty($_FILES['my_video']['name'])) {
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_video&page_num=$page_num");
    exit;
} elseif ($_FILES['my_video']['size'] > 100 * 1024 * 1024) {
    // Check if the video size is greater than 100 MB
    header("Location: " . BASE_URL . "/admin_pages.php?error=video_too_large&page_num=$page_num");
    exit;
} else {
    $allowed_formats = ['mp4', 'avi', 'mov']; // Add more formats if needed
    $uploaded_format = pathinfo($_FILES['my_video']['name'], PATHINFO_EXTENSION);

    if (!in_array($uploaded_format, $allowed_formats)) {
        // Check if the video format is not allowed
        header("Location: " . BASE_URL . "/admin_pages.php?error=invalid_video_format&page_num=$page_num");
        exit;
    }
}

if (isset($_FILES['my_video'])) {
    $uploadDir = '../../videos/';
    $uploadFile = $uploadDir . basename($_FILES['my_video']['name']);

    // Check for file type and size
    $allowedFileTypes = ['video/mp4', 'video/mpeg', 'video/quicktime'];
    $maxFileSize = 104857600; // 100MB (adjust as needed)

    if (in_array($_FILES['my_video']['type'], $allowedFileTypes) && $_FILES['my_video']['size'] <= $maxFileSize) {
        if (move_uploaded_file($_FILES['my_video']['tmp_name'], $uploadFile)) {
            // echo "File is valid and was successfully uploaded.\n";

            // Updates the order_num to fit the new section 
            update_journal_page($section_id, $page_num, $mysqli);

            // Needs to be plus one to add it the the new section
            $new_section_id = $section_id + 1;

            $sql = "INSERT INTO `journal_page`(`section_type`, `section_name`, `order_num`, `page_num`) VALUES ('video', ?, ?, ?)";
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
                        header("Location: " . BASE_URL . "/admin_pages.php?error=error_adding_video&page_num=$page_num");
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
                $sql = "INSERT INTO `video`(`video_src`, `section_id`) VALUES (?, ?);";
                $stmt = $mysqli->prepare($sql);

                if ($stmt) {
                    // Bind your parameters
                    $stmt->bind_param("si", $video_src, $latestID);

                    // Execute the statement
                    if ($stmt->execute()) {
                        if ($stmt->affected_rows > 0) {
                            // echo "New section added!";
                            header("Location: " . BASE_URL . "/admin_pages.php?success=added_video&page_num=$page_num#$section_id");
                        } else {
                            // echo "Error adding section";
                            header("Location: " . BASE_URL . "/admin_pages.php?error=error_adding_video&page_num=$page_num");
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
            // echo "Error: " . $_FILES['my_video']['error'];
            header("Location: " . BASE_URL . "/admin_pages.php?error=couldnt_move_file&page_num=$page_num");
        }
    } else {
        // echo "Invalid file type or size. Please upload a valid video file (max size: 100MB).";
        header("Location: " . BASE_URL . "/admin_pages.php?error=file_to_large&page_num=$page_num");
    }
} else {
    // echo 'No file was uploaded.';
    header("Location: " . BASE_URL . "/admin_pages.php?error=no_video_file&page_num=$page_num");
}