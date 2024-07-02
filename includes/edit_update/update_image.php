<?php
require_once('../../config-url.php');

$page_num = isset($_GET["page_num"]) ? $_GET["page_num"] : null;
$order_num = isset($_GET["order_num"]) ? $_GET["order_num"] : null;

if (!$page_num || !$order_num) {
    echo "Invalid parameters.";
    exit;
}

$targetDirectory = '../../images/'; // Adjust the path as needed

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $imageText = $_POST['image_text'];
    $imageName = $_FILES["my_image"]["name"];
    $imageTmpPath = $_FILES["my_image"]["tmp_name"];

    // Move the uploaded image to the target directory only if it doesn't exist
    $targetFilePath = $targetDirectory . basename($imageName);
    if (!empty($imageTmpPath) && !file_exists($targetFilePath)) {
        move_uploaded_file($imageTmpPath, $targetFilePath);
    }

    // Generate the image_src only if a new image is uploaded
    $imageSrc = (!empty($imageName) && !empty($imageTmpPath)) ? 'images/' . $imageName : null;

    // Prepare and execute the SQL statement to update the image table
    $sql = "UPDATE image SET image_text = ?, image_src = IFNULL(?, image_src) WHERE section_id IN (SELECT id FROM journal_page WHERE page_num = ? AND order_num = ?)";

    // Include necessary files and initialize the database connection
    require_once '../../connect/db.php';

    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssii", $imageText, $imageSrc, $page_num, $order_num);

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
            header("Location: " . BASE_URL . "admin_pages.php?error=update_failed&page_num=$page_num&order_num=$order_num");
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
    echo "Invalid request method.";
}