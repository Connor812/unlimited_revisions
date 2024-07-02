<?php

require_once ("config-url.php");
require_once ("admin-header.php");
require_once ("includes/admin_errors.inc.php");

if (!isset ($_SESSION["admin_username"])) {
    header("Location: " . BASE_URL . "admin.php?error=access_denied");
    exit;
}

?>

<div class=admin-change-form-wrapper>
    <div class="admin-change-pwd-form">
        <center>
            <h1>Change Details</h1>
        </center>
        <hr>

        <div class="admin-change-pwd-section-wrapper">
            <div class="admin-change-pwd-section">
                <form action="includes/admin_change_username.inc.php"
                      method="post">
                    <h2>Username:
                        <?php echo $_SESSION["admin_username"] ?>
                    </h2>
                    <label for="username-input">Username</label>
                    <input id="username-input"
                           class="admin-input"
                           type="text"
                           name="username"
                           placeholder="Username"
                           required />
                    <div class="submit-btn-container">
                        <button class="submit-btn"
                                type="submit">Update</button>
                    </div>
                </form>
            </div>
            <div class="admin-change-pwd-section">
                <form action="includes/admin_change_pwd.inc.php"
                      method="post">
                    <h2>Password:</h2>
                    <label for="pwd-input">Password</label>
                    <input id="pwd-input"
                           class="admin-input"
                           type="password"
                           name="pwd"
                           placeholder="Password"
                           required />
                    <label for="pwd-input">Re-Enter Password</label>
                    <input id="re-pwd-input"
                           class="admin-input"
                           type="password"
                           name="re_pwd"
                           placeholder="Re-Enter Password"
                           required />
                    <div class="submit-btn-container">
                        <button class="submit-btn"
                                type="submit">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo BASE_URL ?>js/admin.js"></script>

    </body>

    </html>