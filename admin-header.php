<?php
require_once 'config-url.php';
session_start();
require_once 'connect/db.php';

// Calculate the base URL
$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';

// Define stylesheets
$stylesheets = [
    'mdb.min.css',
    'bootstrap.css',
    'admin.css',
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible"
          content="ie=edge" />
    <title>UR Admin</title>
    <!-- MDB icon -->
    <link rel="icon"
          href="<?php echo BASE_URL; ?>img/mdb-favicon.ico"
          type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    <?php
    // Output dynamic CSS links
    foreach ($stylesheets as $stylesheet) {
        echo '<link rel="stylesheet" href="' . BASE_URL . 'css/' . $stylesheet . '" />' . PHP_EOL;
    }
    ?>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- Container wrapper -->
        <div class="container-fluid">
            <!-- Toggle button -->
            <button class="navbar-toggler"
                    type="button"
                    data-mdb-toggle="collapse"
                    data-mdb-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse"
                 id="navbarSupportedContent">
                <!-- Navbar brand -->
                <a class="navbar-brand mt-2 mt-lg-0"
                   href="#">
                    <img src="<?php echo BASE_URL ?>/images/UR logo WHiteonclear.jpg"
                         height="60"
                         alt="MDB Logo"
                         loading="lazy" />
                </a>
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                    if (isset($_SESSION["admin_username"])) {

                        ?>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="<?php echo BASE_URL ?>/admin_pages.php">Pages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="<?php echo BASE_URL ?>/admin_users.php">Users</a>
                        </li>

                        <?php
                    }
                    ?>
                    <?php
                    if (isset($_SESSION["admin_username"])) {

                        ?>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="<?php echo BASE_URL ?>admin_change_pwd.php">Change Password</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="<?php echo BASE_URL ?>includes/admin_logout.inc.php">Logout</a>
                        </li>

                        <?php
                    }
                    ?>
                </ul>
                <!-- Left links -->
            </div>
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- End Navbar -->