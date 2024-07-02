<?php

require_once("../../config-url.php");

$video = $_FILES["my_video"];
$page_num = $_GET["page_num"];
$order_num = $_GET["order_num"];

echo $video["name"];

if (!isset($_GET["page_num"]) || !isset($_GET["order_num"])) {
    header("Location: " . BASE_URL . "admin_pages.php?error=missing_section_params");
    exit;
} elseif (empty($video["name"])) {
    header("Location: " . BASE_URL . "/admin_edit/edit_video.php?error=empty_input&page_num=$page_num&order_num=$order_num");
    exit;
} else {
    // Include necessary files and initialize database connection
    require_once '../../connect/db.php';

    // Define the target directory to save the videos
    $targetDirectory = '../../videos/';

    // Get the file information
    $fileName = basename($video["name"]);
    $targetFilePath = $targetDirectory . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Check if it's a valid video file
    $allowedFormats = array('mp4', 'avi', 'mov'); // Add more formats if needed
    if (!in_array(strtolower($fileType), $allowedFormats)) {
        // echo "error invalid format";
        header("Location: " . BASE_URL . "/admin_edit/edit_video.php?error=invalid_format&page_num=$page_num&order_num=$order_num");
        exit;
    }

    // Check if the file size is over 100MB
    $maxFileSize = 100 * 1024 * 1024; // 100MB in bytes
    if ($video["size"] > $maxFileSize) {
        // echo "error file too large";
        header("Location: " . BASE_URL . "/admin_edit/edit_video.php?error=file_too_large&page_num=$page_num&order_num=$order_num");
        exit;
    }

    // Move the file to the target directory
    if (move_uploaded_file($video["tmp_name"], $targetFilePath)) {
        // Prepare and execute the SQL statement
        $sql = "UPDATE video SET video_src = ? WHERE section_id IN (SELECT id FROM journal_page WHERE page_num = ? AND order_num = ?)";

        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $videoSrc = 'videos/' . $fileName; // Save the video source as 'videos/the_file_name'
            $stmt->bind_param("sii", $videoSrc, $page_num, $order_num);

            // Execute the query
            $stmt->execute();

            // Check for success
            if ($stmt->affected_rows > 0) {
                // echo "Update successful!";
                header("Location: " . BASE_URL . "admin_pages.php?success=updated_success&page_num=$page_num&order_num=$order_num");
                $stmt->close();
                exit;
            } else {
                // echo "Update failed!";
                header("Location: " . BASE_URL . "admin_pages.php?error=updated_same&page_num=$page_num&order_num=$order_num");
                $stmt->close();
                exit;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Prepare failed: " . $mysqli->error;
        }

        // Close the database connection
        $mysqli->close();
    } else {
        echo "Error uploading file";
    }
}
