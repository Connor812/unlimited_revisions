<?php

require_once "../connect/db.php";

$username = $_POST["username"];
$firstName = $_POST["first_name"];
$lastName = $_POST["last_name"];
$email = $_POST["email"];
$pwd = $_POST["pwd"];
$repeat_pwd = $_POST['repeat_pwd'];

//Make sure to delete for testing only
// echo "Username: " . $username;
// echo "first name: " . $firstName;
// echo "second name: " . $lastName;
// echo "email: " . $email;
// echo "pwd: " . $pwd;
// echo "repeat_pwd: ". $repeat_pwd;

// Error Handlers for the login form
if (empty($username)) {
    header("Location: ../signup.php?error=empty_username");
    exit;
} elseif (empty($firstName)) {
    header("Location: ../signup.php?error=empty_first_name");
    exit;
} elseif (empty($lastName)) {
    header("Location: ../signup.php?error=empty_last_name");
    exit;
} elseif (empty($email)) {
    header("Location: ../signup.php?error=empty_email");
    exit;
} elseif (empty($pwd)) {
    header("Location: ../signup.php?error=empty_pwd");
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../signup.php?error=invalid_email");
    exit;
}

// Check password length
if (strlen($pwd) <= 3) {
    header("Location: ../signup.php?error=short_password");
    exit;
}

// Checks to see if the pwd and the repeat pwd are the same
if ($pwd != $repeat_pwd) {
    header("Location: ../signup.php?error=passwords_dont_match");
    exit;
}

$mysqli->begin_transaction();

try {
    // Check if username Exists
    $sql = "SELECT username FROM users WHERE username = ?;";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo "Query failed: (" . $stmt->errno . ") " . $stmt->error;
            exit;
        }

        if ($result->num_rows > 0) {
            header("Location: ../signup.php?error=username_exists");
            $mysqli->rollback();
            exit;
        }
        $stmt->close();
    } else {
        echo "Prepare failed: " . $mysqli->error;
        exit;
    }

    // Create new user in data base

    // Hashing the password
    $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT);

    $sql = "INSERT INTO `users`(`username`, `email`, `password`, `first_name`, `last_name`) VALUES (?, ?, ?, ?, ?);";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssss", $username, $email, $hashedPwd, $firstName, $lastName);
        if ($stmt->execute()) {
            // Query executed successfully
            $sql = "SELECT * FROM `initial_page` WHERE id = 1;";

            $result = mysqli_query($mysqli, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    $page_num = $row["page_num"];

                    $user_id = get_username($username, $mysqli);

                    // Insert statement
                    $sql = "INSERT INTO `permission` (`user_id`, `page_num`) VALUES (?, ?);";

                    $stmt = $mysqli->prepare($sql);

                    if (!$stmt) {
                        header("Location: ../signup.php?error=failed_to_create_user");
                        $mysqli->rollback();
                        $stmt->close();
                        exit;
                    }

                    // Bind parameters to the prepared statement
                    $stmt->bind_param("ss", $user_id, $page_num);

                    // Execute the statement
                    if ($stmt->execute()) {
                        // Insertion successful
                        header("Location: ../login.php?message=successful_signup");
                        $mysqli->commit();
                        $stmt->close();
                        exit;
                    } else {
                        // Insertion failed
                        header("Location: ../signup.php?error=failed_to_create_user");
                        $mysqli->rollback();
                        $stmt->close();
                        exit;
                    }
                }
            } else {
                // ! No data found
                header("Location: ../signup.php?error=failed_to_create_user");
                $mysqli->rollback();
                $stmt->close();
                exit;
            }

        } else {
            header("Location: ../signup.php?error=failed_to_create_user");
            $mysqli->rollback();
            $stmt->close();
            exit;
        }
        $stmt->close();
    } else {
        header("Location: ../signup.php?error=prepare_statement_failed");
        $mysqli->rollback();
        return;
    }
} catch (Exception $err) {
    header("Location: ../signup.php?error=$err");
    $mysqli->rollback();
    exit;
}

function get_username($username, $mysqli)
{
    $sql = "SELECT id FROM `users` WHERE username = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // ! Handle the case where the prepared statement could not be created
        header("Location: ../signup.php?error=failed_to_create_user");
        exit;
    }

    $stmt->bind_param("s", $username);

    // ? checks to see if the execute fails
    if (!$stmt->execute()) {
        header("Location: ../signup.php?error=failed_to_create_user");
        $stmt->close();
        exit;
    }

    // * Gets the Result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // * Your Code here
            return $row["id"];
        }
    } else {
        // ! No data found
        header("Location: ../signup.php?error=no_username_found");
        $mysqli->rollback();
        $stmt->close();
        exit;
    }
}