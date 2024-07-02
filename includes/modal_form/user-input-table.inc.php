<?php

require_once "../../connect/db.php";
require_once "../../config-url.php";
require_once 'update_journal_page.php';

$section_id = $_GET['section_id'];
$page_num = $_GET['page_num'];
$questions = $_POST['questions'];

if (empty($page_num)) {
    header("Location: " . BASE_URL . "admin_pages.php?error=no_page_num");
    exit;
}

if (empty($section_id)) {
    header("Location: " . BASE_URL . "admin_pages.php?error=no_section_id&page_num=" . $page_num);
    exit;
}

if (count($questions) == 0) {
    header("Location: " . BASE_URL . "admin_pages.php?error=empty_input&page_num=" . $page_num);
    exit;
}

try {

    $mysqli->begin_transaction();
    update_journal_page($section_id, $page_num, $mysqli);
    $new_section_id = $section_id + 1;

    $sql = "INSERT INTO `journal_page` (`section_type`, `section_name`, `order_num`, `page_num`) VALUES ('user_input_table', 'user_input_table', ?, ?);";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error preparing the statement: " . $mysqli->error);
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param("ii", $new_section_id, $page_num);

    // Execute the statement
    if (!$stmt->execute()) {
        throw new Exception("Error executing the statement: " . $stmt->error);
    }

    $sql = "SELECT MAX(id) FROM journal_page;";

    $result = mysqli_query($mysqli, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $latestID = $row['MAX(id)'];
        }
    } else {
        throw new Exception("Error getting the latest ID: " . $mysqli->error);
    }

    foreach ($questions as $question) {

        list($question_id, $type) = explode(";", $question);

        $sql = "INSERT INTO `user_input_table` (`page_num`, `section_id`, `part`, `type`, `question_id`) VALUES (?, ?, ? , ?, ?);";

        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparing the statement: " . $mysqli->error);
        }

        $stmt->bind_param("iiisi", $page_num, $latestID, $part, $type, $question_id);

        if (!$stmt->execute()) {
            throw new Exception("Error executing the statement: " . $stmt->error);
        }
    }

    $mysqli->commit();
    header("Location: " . BASE_URL . "admin_pages.php?success=section_added&page_num=" . $page_num);
    exit;
} catch (Exception $e) {
    $mysqli->rollback();
    header("Location: " . BASE_URL . "admin_pages.php?error=sql_error&page_num=" . $page_num);
    exit;
}
