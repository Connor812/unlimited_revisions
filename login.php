<?php

require_once("config-url.php");

session_start();

if (isset($_SESSION["username"])) {
    header("Location: " . BASE_URL . "journal.php");
    exit;
}

require_once 'header.php';
?>

<div class="d-flex justify-content-center align-items-center container" style="height: 100%;">
    <form action="includes/login.inc.php" method="post"
        class="form-horizontal col-md-6 col-md-offset-3 border border-2 p-5 rounded form-background">
        <!-- Title -->
        <div class="form-group text-center">
            <h1>Login</h1>
        </div>
        <?php

        if (isset($_GET['message'])) {

            echo '
            <div style="background-color: #61C14E; color: white; padding: 10px; border: 3px solid #4CAF50; border-radius: 5px; text-align: center;">
                Created user successfully
            </div>';
        }

        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'invalid_password') {
                echo '<div style="background-color: #E57373; color: white; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
        Invalid Password! Please try again.
    </div>';
            } elseif ($_GET['error'] == 'empty_username') {
                echo '<div style="background-color: #E57373; color: white; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
        Cannot Leave Username Empty!
    </div>';
            } elseif ($_GET['error'] == 'empty_pwd') {
                echo '<div style="background-color: #E57373; color: white; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
        Cannot Leave Password Empty!
    </div>';
            } elseif ($_GET['error'] == 'username_doesnt_exist') {
                echo "<div style='background-color: #E57373; color: white; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;'>
        Username Doesn't Exist, Please try again.
    </div>";
            }
        }

        ?>
        <!-- Username input -->
        <div class="form-group">
            <label class="control-label" for="username_input">Username</label>
            <input type="text" name="username" id="username_input" class="form-control" placeholder="Username" />
        </div>

        <!-- Password input -->
        <div class="form-group">
            <label class="control-label" for="password_input">Password</label>
            <input type="password" id="password_input" name="pwd" class="form-control" placeholder="Password" />
        </div>

        <!-- Submit button -->
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary mt-3">Login</button>
        </div>
    </form>
</div>



<?php
require_once 'footer.php'
    ?>