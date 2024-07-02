<?php
require_once "admin-header.php";
require_once "config-url.php";
require_once "includes/admin_errors.inc.php";

if (!isset($_SESSION["admin_username"])) {
    header("Location: " . BASE_URL . "admin.php?error=access_denied");
    exit;
}

function get_page_name($mysqli)
{
    $sql = "SELECT * FROM `initial_page` WHERE id = 1;";

    $result = mysqli_query($mysqli, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $page_num = $row["page_num"];


            $sqlGetName = "SELECT page_name FROM `page_name` WHERE page_num = ?;";
            $stmtGetName = $mysqli->prepare($sqlGetName);

            if (!$stmtGetName) {

                return "Error Getting Initial Page";
            }

            $stmtGetName->bind_param("i", $page_num);

            // ? checks to see if the execute fails
            if (!$stmtGetName->execute()) {
                $stmtGetName->close();
                return "Error Getting Initial Page";
            }

            // * Gets the Result
            $resultGetName = $stmtGetName->get_result();

            if ($resultGetName->num_rows > 0) {

                while ($rowGetName = $resultGetName->fetch_assoc()) {
                    return $rowGetName["page_name"];
                }
            } else {
                // ! No data found
                return "Error Getting Initial Page";
            }
        }
    } else {
        // ! No data found
        return "Error Getting Initial Page";
    }
}

?>

<div class="row justify-content-center permissions-container" style="width: 100%;">
    <form method="post" action="includes/initial_page.inc.php" class="change-permissions-container col-3">

        <h5 class="text-start">Select Users Initial Page <br>
            Current Page:
            <?php echo get_page_name($mysqli) ?>
        </h5>

        <select name="page_num" class="btn btn-warning form-select" aria-label="Default select example">
            <option selected disabled>Select A Page</option>
            <?php
            $sql = "SELECT * FROM `page_name`;";

            $result = mysqli_query($mysqli, $sql);

            if (mysqli_num_rows($result) > 0) {
                $page_num = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    // * Your Code here
                    $page_num++;
            ?>
                    <option value="<?php echo $row["page_num"] ?>">
                        <?php echo ($row["page_name"]) ? $row["page_name"] : "Page $page_num" ?>
                    </option>

                <?php
                }
            } else {
                // ! No data found
                ?>
                <option disabled>Couldn't Find Pages</option>
            <?php
            }
            ?>
        </select>

        <button class="btn btn-primary" type="submit">Update Permissions For All Users</button>

    </form>
    <form method="post" action="includes/update_all_permissions.inc.php" class="change-permissions-container col-3">

        <h5 class="text-start">Give Permissions To All Users</h5>

        <select name="page_num" class="btn btn-success form-select" aria-label="Default select example">
            <option selected disabled>Select A Page</option>
            <?php
            $sql = "SELECT * FROM `page_name`;";

            $result = mysqli_query($mysqli, $sql);

            if (mysqli_num_rows($result) > 0) {
                $page_num = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    // * Your Code here
                    $page_num++;
            ?>
                    <option value="<?php echo $row["page_num"] ?>">
                        <?php echo ($row["page_name"]) ? $row["page_name"] : "Page $page_num" ?>
                    </option>

                <?php
                }
            } else {
                // ! No data found
                ?>
                <option disabled>Couldn't Find Pages</option>
            <?php
            }
            ?>
        </select>

        <button class="btn btn-primary" type="submit">Update Permissions For All Users</button>

    </form>
    <form action="includes/delete_permissions.inc.php" method="post" class="change-permissions-container col-3">
        <h5 class="text-start">Remove Permissions For All Users</h5>

        <select name="page_num" class="btn btn-danger form-select" aria-label="Default select example">
            <option selected disabled>Select A Page</option>
            <?php
            $sql = "SELECT * FROM `page_name`;";

            $result = mysqli_query($mysqli, $sql);

            if (mysqli_num_rows($result) > 0) {
                $page_num = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    // * Your Code here
                    $page_num++;
            ?>

                    <option value="<?php echo $row["page_num"] ?>">
                        <?php echo ($row["page_name"]) ? $row["page_name"] : "Page $page_num" ?>
                    </option>

                <?php
                }
            } else {
                // ! No data found
                ?>
                <option disabled>Couldn't Find Pages</option>
            <?php
            }
            ?>
        </select>

        <button class="btn btn-primary">Update Permissions For All Users</button>
    </form>
</div>


<div class="d-flex justify-content-center p-3 gap-2">
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">
            Select a User
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php
            // Grabs all the users from the database and displays them here
            $sql = "SELECT id, username FROM users;";
            $result = mysqli_query($mysqli, $sql);

            if (!$result) {
                die("Query failed: " . mysqli_error($mysqli));
            }

            while ($row = mysqli_fetch_assoc($result)) {
                $username = $row['username'];
                $user_id = $row['id'];
                echo '<li><a class="dropdown-item" href="admin_users.php?username=' . $username . '&user_id=' . $user_id . '">' . $username . '</a></li>';
            }
            ?>
        </ul>
    </div>
    <input type="hidden" name="username" value="<?php echo $_GET['username'] ?? ''; ?>">
</div>
<?php
// If there is a username in the url, we will display the users data
if (isset($_GET['username'])) {

    $username = $_GET['username'];

    // Query for the users information
    $sql = 'SELECT * FROM users WHERE username = ?;';

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result) {
            $row = $result->fetch_assoc();
            // Getting all the necessary variables
            $user_id = $row["id"];
            $username = $row['username'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $email = $row['email'];
            $registration_date = $row['registration_date'];

            // This gets the amount of pages that are in the database
            $sql = 'SELECT COUNT(DISTINCT page_num) AS num_pages FROM journal_page;';
            $result = mysqli_query($mysqli, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $page_nums = $row['num_pages'];

                // This section will display the users information
?>
                <div width="%100" class="d-flex flex-column align-items-center justify-content-center m-3">
                    <div id="user_info" class="container row border border-dark border-2 rounded p-4">
                        <div class="col d-flex flex-column gap-5">
                            <h3>User Info</h3>
                            <div>
                                <b>User Id:</b>
                                <?php echo $user_id ?>
                            </div>
                            <div>
                                <b>Username:</b>
                                <?php echo $username ?>
                            </div>
                            <div>
                                <b>First Name:</b>
                                <?php echo $first_name ?>
                            </div>
                            <div>
                                <b>Last Name:</b>
                                <?php echo $last_name ?>
                            </div>
                            <div>
                                <b>Email:</b>
                                <?php echo $email ?>
                            </div>
                            <div>
                                <b> Registration Date:</b>
                                <?php echo $registration_date ?>
                            </div>
                        </div>
                        <div class="col">
                            <h3>Permissions</h3>
                            <form method="post" action="includes/permissions.inc.php?user_id=<?php echo $user_id ?>&username=<?php echo $username ?>">
                                <?php
                                // Initialize $checked variable before the loop
                                $checked = '';
                                // this right here will loop over all the pages to echo a check box
                                for ($i = 1; $i <= $page_nums; $i++) {

                                    // Query for if a user has permissions to write on a certain page
                                    $sql = 'SELECT * FROM permission WHERE user_id = ? AND page_num = ?;';

                                    $stmt = $mysqli->prepare($sql);

                                    if ($stmt) {
                                        $stmt->bind_param("ii", $user_id, $i);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        // If the user has permissions from the admin, we will display a checked box, if not, no check
                                        if ($result) {
                                            if ($result->num_rows > 0) {
                                                $checked = 'checked';
                                            } else {
                                                $checked = '';
                                            }
                                        } else {
                                            echo "No Results Found: " . $mysqli->error;
                                            $checked = '';
                                        }
                                    } else {
                                        $checked = '';
                                        echo "Prepare failed: " . $mysqli->error;
                                        exit;
                                    }
                                ?>
                                    <div class="form-check d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" name="page_<?php echo $i ?>" value="<?php echo $i ?>" id="check_box_<?php echo $i ?>" <?php echo $checked ? 'checked' : '' ?> />
                                            <label class="form-check-label">
                                                <?php
                                                $sql = "SELECT page_name FROM page_name WHERE page_num = ?;";
                                                $stmt = $mysqli->prepare($sql);

                                                if ($stmt) {
                                                    $stmt->bind_param("i", $i);
                                                    $stmt->execute();
                                                    $stmt->store_result();

                                                    if ($stmt->num_rows > 0) {
                                                        // Fetch the result
                                                        $stmt->bind_result($page_name);
                                                        $stmt->fetch();
                                                        echo ($page_name) ? $page_name : "Page $i";
                                                    } else {
                                                        echo "Page $i";
                                                    }
                                                } else {
                                                    echo "Prepare failed: " . $mysqli->error;
                                                }
                                                ?>
                                            </label>
                                        </div>
                                        <div>
                                            <a class="btn btn-primary" style="text-decoration: none; color: white;" href="<?php echo BASE_URL ?>/admin_users.php?username=<?php echo $username ?>&user_id=<?php echo $user_id ?>&page_num=<?php echo $i ?>">Open</a>
                                        </div>
                                    </div>
                                <?php
                                }

                                ?>
                                <button type="submit" class="btn btn-primary">Change Permissions</button>
                            </form>
                        </div>
                    </div>

                    <button class="btn btn-primary my-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Open Messages
                    </button>

                    <div class="collapse" id="collapseExample" style="width: 100%;">
                        <div class="card card-body">
                            <div id="chat-box" class="chat-box border border-2">
                                <div class="message-wrapper">

                                    <?php

                                    $sql = "SELECT * FROM `messages` WHERE `user_id` = ?;";
                                    $stmt = $mysqli->prepare($sql);

                                    if (!$stmt) {
                                        echo "Prepared Error";
                                    }

                                    $stmt->bind_param("i", $user_id);
                                    if ($stmt->execute()) {
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {

                                            while ($row = $result->fetch_assoc()) {

                                                $message = $row["message"];
                                                $from = $row["who_from"];
                                                $time = $row["time_stamp"];
                                                if ($from === "user") {
                                    ?>
                                                    <div class="message-container admin-message">
                                                        <div>
                                                            <?php echo $message ?>
                                                        </div>
                                                    </div>
                                                    <div class="admin-time-stamp">
                                                        <?php echo $time ?>
                                                    </div>
                                                <?php
                                                } else {
                                                ?>
                                                    <div class="message-container user-message">
                                                        <div>
                                                            <?php echo $message ?>
                                                        </div>
                                                    </div>
                                                    <div class="user-time-stamp">
                                                        <?php echo $time ?>
                                                    </div>
                                    <?php
                                                }
                                            }
                                        } else {
                                            echo "No Messages Yet";
                                        }
                                    } else {
                                        echo "Error Getting Messages";
                                    }

                                    ?>

                                </div>
                                <div class="send-message">
                                    <form action="includes/add-message.php?username=<?php echo $_GET["username"] ?>&page_num=<?php echo $_GET["page_num"] ?>" method="post">
                                        <div class="input-group mb-3">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                                            <input type="hidden" name="from" value="admin">
                                            <textarea type="text" name="message" class="form-control message-input" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2"></textarea>
                                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2" style="width: 80px">
                                                <svg width="25" height="25" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                                    <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <script>
                                window.onload = function() {
                                    var messageWrapper = document.querySelector('.message-wrapper');
                                    messageWrapper.scrollTop = messageWrapper.scrollHeight;
                                }
                            </script>
                        </div>
                    </div>






        <?php
            }
        } else {
            echo "No results found for the username: " . $username;
        }

        $stmt->close();
    } else {
        echo "Query preparation failed: " . $mysqli->error;
    }
} else {
        ?>

        <div style="width: 100%; height: 80vh;" class="d-flex justify-content-center align-items-center">
            <h1>No User Selected</h1>
        </div>

    <?php
}
    ?>

    <?php

    require_once 'includes/display-sections.inc.php';

    if (isset($_GET['page_num'])) {
        $username = $_GET['username'];
        $page_num = $_GET['page_num'];
        $user_id = $_GET['user_id'];

    ?>
        <div class='container-fluid p-4'>
            <div class='d-flex justify-content-center'>
                <h1>
                    <?php echo $username ?>
                </h1>
            </div>
            <?php
            display_sections($page_num, $mysqli, false, $user_id);
            ?>
        </div>
    <?php

    }

    ?>

    <?php
    require_once 'admin_footer.php';
    ?>

    <script src="js/add-comment-to-user.js"></script>