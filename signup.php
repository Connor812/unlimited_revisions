<?php

require_once("config-url.php");

session_start();

if (isset($_SESSION["username"])) {
    header("Location: " . BASE_URL . "journal.php");
    exit;
}

require_once 'header.php';
?>
<div class="d-flex justify-content-center align-items-center container"
     style="height: 100%;">
    <form action="includes/signup.inc.php"
          method="post"
          class="form-horizontal col-md-6 col-md-offset-3 border border-2 p-5 rounded form-background">
        <!-- Title -->
        <div class="form-group text-center">
            <h1>Signup</h1>
        </div>
        <?php
        $param = '';

        if (!empty($_GET['error'])) {
            $param = $_GET['error'];
        }

        if ($param == 'empty_username') {
            echo '<div style="background-color: #E57373; color: #464646; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
                    Cannot Leave Username Empty!
                </div>';
        } elseif ($param == 'empty_pwd') {
            echo '<div style="background-color: #E57373; color: #464646; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
            Cannot Leave Password Empty!
            </div>';
        } elseif ($param == 'empty_first_name') {
            echo '<div style="background-color: #E57373; color: #464646; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
            Cannot Leave First Name Empty!
            </div>';
        } elseif ($param == 'empty_last_name') {
            echo '<div style="background-color: #E57373; color: #464646; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
            Cannot Leave Last Name Empty!
            </div>';
        } elseif ($param == 'empty_email') {
            echo '<div style="background-color: #E57373; color: #464646; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
            Cannot Leave email Empty!
            </div>';
        } elseif ($param == 'invalid_email') {
            echo '<div style="background-color: #E57373; color: #464646; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
            Must be Valid Email!
            </div>';
        } elseif ($param == 'short_password') {
            echo '<div style="background-color: #E57373; color: #464646; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
            Password must be more then 3 characters!
            </div>';
        } elseif ($param == 'username_exists') {
            echo '<div style="background-color: #E57373; color: #464646; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
            Username already exists, please try another one!
            </div>';
        } elseif ($param == 'failed_to_create_user') {
            echo '<div style="background-color: #E57373; color: #464646; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;">
            Username already exists, please try another one!
            </div>';
        } elseif ($param == 'passwords_dont_match') {
            echo "<div style='background-color: #E57373; color: #464646; padding: 10px; border: 3px solid red; border-radius: 5px; text-align: center;'>
            The passwords don't match, please try again.
            </div>";
        }

        ?>
        <div class="form-group">
            <label class="control-label"
                   for="username_input">Username</label>
            <input class="form-control"
                   id="username_input"
                   type="text"
                   name="username"
                   placeholder="Username">
        </div>
        <div class="form-group">
            <label class="control-label"
                   for="first_name">First Name</label>
            <input class="form-control"
                   type="text"
                   id="first_name"
                   name="first_name"
                   placeholder="First Name">
        </div>

        <div class="form-group">
            <label class="control-label"
                   for="last_name">Last Name</label>
            <input class="form-control"
                   type="text"
                   id="last_name"
                   name="last_name"
                   placeholder="Last Name">
        </div>
        <div class="form-group">
            <label class="control-label"
                   for="email">Email</label>
            <input class="form-control"
                   type="email"
                   id="email"
                   name="email"
                   placeholder="Email">
        </div>
        <div class="form-group">
            <label class="control-label"
                   for="pwd">Password</label>
            <input class="form-control"
                   type="password"
                   id="pwd"
                   name="pwd"
                   placeholder="Password">
        </div>
        <div class="form-group">
            <label class="control-label"
                   for="repeat_pwd">Password Repeat</label>
            <input class="form-control"
                   type="password"
                   id="repeat_pwd"
                   name="repeat_pwd"
                   placeholder="Re-enter password">
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit"
                    class="btn btn-primary mt-3">Sign up</button>
        </div>
    </form>
</div>

<?php
require_once 'footer.php'
    ?>