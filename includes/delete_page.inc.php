<?php

require_once("../config-url.php");

if (!isset($_GET["page_num"]) || empty($_GET["page_num"])) {
    header("Location: " . BASE_URL . "admin_pages.php?page_num=1&error=no_page_num");
}

$page_num = $_GET["page_num"];

require_once("../connect/db.php");

// Start mysqli transaction
mysqli_begin_transaction($mysqli);

try {
    // First Delete the page_name from the page_name where page_num

    $sql = "DELETE FROM `page_name` WHERE page_num = ?;";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        header("Location: " . BASE_URL . "admin_pages.php?error=failed_delete_page");
        exit;
    }

    $stmt->bind_param("i", $page_num);
    $stmt->execute();

    if ($stmt->affected_rows == 0) {
        // echo "no page name";
    }

    // Deleting the page from journal_page

    $sql = "DELETE FROM `journal_page` WHERE page_num = ?;";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        mysqli_rollback($mysqli);
        header("Location: " . BASE_URL . "admin_pages.php?error=failed_delete_page");
        exit;
    }

    $stmt->bind_param("i", $page_num);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // echo "Successfully Deleted Page";
    } else {
        // echo "Error Deleting Page";
        mysqli_rollback($mysqli);
        header("Location: " . BASE_URL . "admin_pages.php?error=failed_delete_page");
        exit;
    }

    // Updating the page_nam

    $sql = "UPDATE `page_name` SET `page_num` = page_num - 1 WHERE page_num > ?;";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        header("Location: " . BASE_URL . "admin_pages.php?error=failed_delete_page");
        exit;
    }

    $stmt->bind_param("i", $page_num);
    $stmt->execute();

    // Check if there was an error during execution
    if ($stmt->errno) {
        // If there was an error, rollback and handle it
        // echo "Error Updating Pages: " . $stmt->error;
        mysqli_rollback($mysqli);
        header("Location: " . BASE_URL . "admin_pages.php?error=failed_delete_page");
        exit;
    }

    // Now updating the journal page to fill in the whole for the deleted page

    $sql = "UPDATE `journal_page` SET `page_num` = page_num - 1 WHERE page_num > ?;";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        header("Location: " . BASE_URL . "admin_pages.php?error=failed_delete_page");
        exit;
    }

    $stmt->bind_param("i", $page_num);
    $stmt->execute();

    // Check if there was an error during execution
    if ($stmt->errno) {
        // If there was an error, rollback and handle it
        // echo "Error Updating Pages: " . $stmt->error;
        mysqli_rollback($mysqli);
        header("Location: " . BASE_URL . "admin_pages.php?error=failed_delete_page");
        exit;
    }

    // If everything is successful, commit the transaction
    mysqli_commit($mysqli);
    header("Location: " . BASE_URL . "admin_pages.php?success=deleted_page&page_num=1");
} catch (Exception $e) {
    // An error occurred, rollback the transaction
    mysqli_rollback($mysqli);

    // Handle or log the exception as needed
    // echo "Error: " . $e->getMessage();
    header("Location: " . BASE_URL . "admin_pages.php?error=failed_update_pages");
}

// Close the database connection (optional, depending on your application flow)
mysqli_close($mysqli);
