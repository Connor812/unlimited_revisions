<?php

require_once("../config-url.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "admin_users.php?error=invalid_request_method");
    exit;
}

if (!isset($_POST["page_num"]) || empty($_POST["page_num"])) {
    header("Location: " . BASE_URL . "admin_users.php?error=no_page_num");
    exit;
}

$page_num = $_POST["page_num"];

require_once("../connect/db.php");

$sql = "SELECT id FROM `users`;";

$result = mysqli_query($mysqli, $sql);

$mysqli->begin_transaction();

try {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $id = $row["id"];
            // ? check if the permission exists already

            $sql = "SELECT * FROM `permission` WHERE user_id = ? AND page_num = ?;";
            $stmt = $mysqli->prepare($sql);

            if (!$stmt) {
                // ! Handle the case where the prepared statement could not be created
                header("Location: " . BASE_URL . "admin_users.php?error=failed_update_permissions");
                $stmt->close();
                exit;
            }

            $stmt->bind_param("ii", $id, $page_num);

            // ? checks to see if the execute fails
            if (!$stmt->execute()) {
                header("Location: " . BASE_URL . "admin_users.php?error=permission_check_failed");
                $stmt->close();
                exit;
            }

            // * Gets the Result
            $permissionCheckResult = $stmt->get_result();

            if ($permissionCheckResult->num_rows > 0) {
                // * Permission Already Exists Move On To Next Step
                // echo "Permission Already Exists <br>";
            } else {
                // ! No Permission Found
                // ? Add permission if doesn't exist

                // Insert statement
                $sql = "INSERT INTO `permission` (`user_id`, `page_num`) VALUES (?, ?);";

                $stmt = $mysqli->prepare($sql);

                if (!$stmt) {
                    header("Location: " . BASE_URL . "admin_users.php?error=failed_update_permissions");
                    $stmt->close();
                    $mysqli->rollback();
                    exit;
                }

                // Bind parameters to the prepared statement
                $stmt->bind_param("ii", $id, $page_num);

                // Execute the statement
                if ($stmt->execute()) {
                    // * Permissions Successfully Updated Move onto Next Step
                    // echo "successfully Updated Permissions for user <br>";
                } else {
                    // ! Error To Update User
                    // echo "failed to update user <br>";
                    header("Location: " . BASE_URL . "admin_users.php?error=failed_update_permissions");
                    $mysqli->rollback();
                    exit;
                }
            }
        }

        // * Successfully Updated all the users permissions
        header("Location: " . BASE_URL . "admin_users.php?success=updated_all_permissions");
        $mysqli->commit();
        exit;

    } else {
        // ! No data found
        header("Location: " . BASE_URL . "admin_users.php?error=no_users");
        $mysqli->rollback();
        exit;
    }
} catch (Exception $err) {
    header("Location: " . BASE_URL . "admin_users.php?error=failed_update_permissions");
    $mysqli->rollback();
    exit;
}