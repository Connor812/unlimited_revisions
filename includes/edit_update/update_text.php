<?php 

require_once("../../config-url.php");

$text_content = $_POST["text_content"];
$page_num = $_GET["page_num"];
$order_num = $_GET["order_num"];

if (!isset($_GET["page_num"]) || !isset($_GET["order_num"])) {
    header("Location: " . BASE_URL . "admin_pages.php?error=missing_section_params");
    exit;
} elseif (empty($text_content)) {
    header("Location: " . BASE_URL . "/admin_edit/edit_text.php?error=empty_input&page_num=$page_num&order_num=$order_num");
    exit;
} else {
    // Include necessary files and initialize database connection
    require_once '../../connect/db.php';
    

    // Prepare and execute the SQL statement
    $sql = "UPDATE journal_page
    JOIN text ON journal_page.id = text.section_id
    SET journal_page.section_name = ?,
        text.text_content = ?
    WHERE journal_page.page_num = ? AND journal_page.order_num = ?;";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssii", $text_content, $text_content, $page_num, $order_num);

        // Execute the query
        $stmt->execute();

        // Check for success
        if ($stmt->affected_rows > 0) {
            echo "Update successful!";
            header("Location: " . BASE_URL . "admin_pages.php?success=updated_success&page_num=$page_num&#$order_num");
        } else {
            echo "Update failed!";
            header("Location: " . BASE_URL . "admin_pages.php?error=updated_same&page_num=$page_num#$order_num");
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Prepare failed: " . $mysqli->error;
    }

    // Close the database connection
    $mysqli->close();
}
