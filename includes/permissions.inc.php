<?php

require_once "../connect/db.php";
require_once "../config-url.php";

$user_id = $_GET['user_id'];
$username = $_GET['username'];

if (isset($_POST)) {
    // Initialize an array to store the page statuses
    $pageStatuses = array();

    // Iterate over page checkboxes and check their status
    for ($i = 1; $i <= 8; $i++) {
        $pageName = "page_" . $i;
        $pageStatus = isset($_POST[$pageName]) ? "true" : "false";

        // Store the page status in the array
        $pageStatuses[$pageName] = $pageStatus;
    }

    // Loop through the page statuses and perform actions
    foreach ($pageStatuses as $pageName => $status) {
        // Extract the page number from the checkbox name
        $page_num = (int) substr($pageName, 5);

        // Check if the combination of user_id and page_num exists
        $sql = "SELECT * FROM permission WHERE user_id = ? AND page_num = ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            die("Error preparing the statement: " . $mysqli->error);
        }

        $stmt->bind_param("ii", $user_id, $page_num);
        $stmt->execute();

        if ($stmt->error) {
            die("Error executing the statement: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result === false) {
            die("Error getting result set: " . $stmt->error);
        }

        if ($result->num_rows > 0) {
            // The combination exists
            if ($status === "false") {
                // If it's false, delete the permission
                $sql = "DELETE FROM permission WHERE user_id = ? AND page_num = ?";
                $stmt = $mysqli->prepare($sql);

                if ($stmt === false) {
                    die("Error preparing the statement: " . $mysqli->error);
                }

                $stmt->bind_param("ii", $user_id, $page_num);
                $stmt->execute();

                if ($stmt->error) {
                    die("Error executing the statement: " . $stmt->error);
                }

                // echo "Permission deleted for Page $page_num.<br>";
            } else {
                // echo "Permission exists for Page $page_num.<br>";
            }
        } else {
            // The combination does not exist, so insert it if it's true
            if ($status === "true") {
                $sql = "INSERT INTO permission (user_id, page_num) VALUES (?, ?)";
                $stmt = $mysqli->prepare($sql);

                if ($stmt === false) {
                    die("Error preparing the statement: " . $mysqli->error);
                }

                $stmt->bind_param("ii", $user_id, $page_num);
                $stmt->execute();

                if ($stmt->error) {
                    die("Error executing the statement: " . $stmt->error);
                }

                // echo "Permission inserted for Page $page_num.<br>";
            }
        }
    }

    // Redirect to the admin_users.php page
    header("Location: " . BASE_URL . "/admin_users.php?username=$username&user_id=$user_id&success=permission_updated");
    exit; // Make sure to exit after redirection
}