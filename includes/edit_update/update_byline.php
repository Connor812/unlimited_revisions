<?php 

require_once("../../config-url.php");

$byline_content = $_POST["byline_content"];
$page_num = $_GET["page_num"];
$order_num = $_GET["order_num"];

if (!isset($_GET["page_num"]) || !isset($_GET["order_num"])) {
    header("Location: " . BASE_URL . "admin_pages.php?error=missing_section_params");
} elseif (empty($byline_content)) {
    header("Location: " . BASE_URL . "/admin_edit/edit_byline.php?error=empty_input&page_num=$page_num&order_num=$order_num");
} else {
    // Include necessary files and initialize database connection
    require_once '../../connect/db.php';
    

    // Prepare and execute the SQL statement
    $sql = "UPDATE journal_page AS jp
            JOIN byline AS b ON jp.id = b.section_id
            SET jp.section_name = ?,
                b.byline_content = ?
            WHERE jp.page_num = ? AND jp.order_num = ?";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssii", $byline_content, $byline_content, $page_num, $order_num);

        // Execute the query
        $stmt->execute();

        // Check for success
        if ($stmt->affected_rows > 0) {
            header("Location: " . BASE_URL . "admin_pages.php?success=updated_success&page_num=$page_num&#$order_num");
            $stmt->close();
            exit;
        } else {
            header("Location: " . BASE_URL . "admin_pages.php?error=updated_same&&page_num=$page_num#$order_num");
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
}
