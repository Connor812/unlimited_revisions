<?php 

require_once("../../config-url.php");

$subheading_content = $_POST["subheading_content"];
$page_num = $_GET["page_num"];
$order_num = $_GET["order_num"];

if (!isset($_GET["page_num"]) || !isset($_GET["order_num"])) {
    echo "error no page_num";
    header("Location: " . BASE_URL . "admin_pages.php?error=missing_section_params");
} elseif (empty($subheading_content)) {
    echo "error empty input";
    header("Location: " . BASE_URL . "/admin_edit/edit_subheading.php?error=empty_input&page_num=$page_num&order_num=$order_num");
} else {
    // Include necessary files and initialize database connection
    require_once '../../connect/db.php';
    

    // Prepare and execute the SQL statement
    $sql = "UPDATE journal_page
    JOIN subheading ON journal_page.id = subheading.section_id
    SET journal_page.section_name = ?,
        subheading.subheading_content = ?
    WHERE journal_page.page_num = ? AND journal_page.order_num = ?;";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssii", $subheading_content, $subheading_content, $page_num, $order_num);

        // Execute the query
        $stmt->execute();

        // Check for success
        if ($stmt->affected_rows > 0) {
            header("Location: " . BASE_URL . "admin_pages.php?success=updated_success&page_num=$page_num&#$order_num");
            $stmt->close();
            exit;
        } else {
            header("Location: " . BASE_URL . "admin_pages.php?error=updated_same&page_num=$page_num#$order_num");
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
