<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>UNLIMITED REVISIONS PERSONAL JOURNAL</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="This is a platform for women in need who are suffering from an abusive relationship with their partner. Here you can find the help you are looking for.">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/ur.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <!--nav-->
    <nav class="navbar navbar-expand-lg bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="images/URWclear.jpg" style="width:75px;" alt="Hero image">
            </a>
            <button class="navbar-toggler" type="button" style="border: 2px solid white;" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <svg width="40" height="40" fill="white" class="list-button" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                </svg>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="list-style: none;">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="journal.php">Journal</a>
                    </li>
                    <?php
                    if (isset($_SESSION["username"])) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="logout.php">Logout</a>
                        </li>
                    <?php
                    } ?>
                </ul>
                <?php
                if (isset($_SESSION["username"])) {

                    require_once("connect/db.php");

                    $sql = "SELECT COUNT(*) AS total_unread_messages
                    FROM messages
                    WHERE user_id = ? AND user_read_status = 'un-read';";
                    $stmt = $mysqli->prepare($sql);

                    if (!$stmt) {
                        // ! Handle the case where the prepared statement could not be created
                        $total_unread_messages = 0;
                    }

                    $stmt->bind_param("i", $_SESSION["user_id"]);

                    // ? checks to see if the execute fails
                    if (!$stmt->execute()) {
                        $total_unread_messages = 0;
                        $stmt->close();
                    }

                    // * Gets the Result
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // * Your Code here
                            $total_unread_messages = $row["total_unread_messages"];
                        }
                    } else {
                        // ! No data found
                        $total_unread_messages = 0;
                    }

                ?>
                    <li class="nav-item">
                        <a class="nav-link active position-relative" aria-current="page" href="profile.php">
                            <svg width="30" height="30" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                            </svg>

                            <?php

                            if ($total_unread_messages > 0) { ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo $total_unread_messages ?>
                                    <span class="visually-hidden">unread messages</span>
                                </span>

                            <?php } ?>
                        </a>
                    </li>
                <?php } ?>
            </div>
        </div>
    </nav>

    <!-- The Modal -->
    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <h2>Unsaved Changes</h2>
            <p>You have unsaved changes. Do you want to stay on this page or leave?</p>
            <button>Stay on Page</button>
            <button>Leave with Unsaved Changes</button>
        </div>

    </div>