<?php
require_once 'header.php';
?>

<div class="ocean-image">
    <h1 style="margin-top: -4%;">Unlimited Revisions</h1>
    <h6>Confidential help for women seeking change<br> On-Line Guide and Guided Journal<br></h6>
</div>
<!--wrapper-->
<div class="wrapper p-3">
    <!--welcome blurb-->
    <?php
    if (isset($_GET["success"])) {
    ?>
        <center>
            <div class="alert fs-5" style="width: 50%; background-color: var(--ur-blue); border-color: #519db7; color: #155724;" role="alert">
                <i style="color: black">
                    Thank You For Submitting Your Form! We Will Reach Out To You Shortly After Reviewing.
                </i>
            </div>
        </center>
    <?php
    }

    if (isset($_GET["error"])) {
    ?>
        <center>
            <div class="alert alert-danger fs-5" style="width: 50%" role="alert">
                There Was An Error Connecting To Our Servers And Your Form Was Not Submitted. Please Try
                Again.
            </div>
        </center>
    <?php
    }
    ?>

    <?php
    if ($_GET["page_num"] == 1 || !isset($_GET["page_num"])) {


    ?>
        <div class="container-fluid" style="padding: 0% 5%;">
            <h3>Welcome
                <?php
                if (isset($_SESSION['username'])) {
                    echo $_SESSION['username'] . ",";
                }
                ?>
            </h3>
            <p>
                CONGRATULATIONS! You have taken an important step in your self-care. The commitment you are making to
                self-analysis will benefit many aspects of your life, particularly decision-making about relationships. UR
                is
                for
                women who have coped with toxic, abusive or dissatisfying relationships and the impact, which can be
                long-lasting
                in surprising ways. UR is for women seeking that elusive self-love that immunizes from further harm.
            </p>
            <p>
                The <b>UR</b> pathway and tools can help you realize the change that comes from within.
            </p>
            <div class="m-5" style="text-align: center;">
                <h3 class="my-4">The UR Pathway Consists of 4 Journal Parts and 4 Support Sessions</h3>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Part One: Getting to Know Me</h5>
                                    <p class="card-text">An individual consultation with Lori<br>ENTER the pathway support
                                        session</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Part Two: Getting Ready to Excavate</h5>
                                    <p class="card-text">An individual consultation with Lori<br>EXCAVATE support session
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Part Three: Getting to EXAMINE</h5>
                                    <p class="card-text">An individual consultation with Lori<br>EXAMINE support session</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Part Four: Getting ready to EXIT</h5>
                                    <p class="card-text">An individual consultation with Lori<br>EXIT support session</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Mission-->
        <?php
        if (!isset($_SESSION['user_id'])) {
        ?>
            <!-- <center>
                <div style="max-width: 800px;">
                    <h3>New Misson</h3>

                    <p>TO SPEND TIME AND ENERGY ON YOU</p>

                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                        exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
                        dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
                        mollit anim id est laborum.
                    </p>
                </div>
            </center> -->


            <div class="ulbluebg">
                <div class="uralert">
                    <div class="row">
                        <div class="col-6" style="text-align: center;">
                            <img class="image-reponsive" src="images/misson.jpg" style="width: 100%; max-height: auto; border-radius: 20px;">
                        </div>
                        <section class="col-6">
                            <div class="d-flex justify-content-center align-items-center container" style="width: 100%; height: 100%;">
                                <section id="sign-up-form" style="width: 100%; height: 100%;">
                                    <form action="includes/signup.inc.php" method="post" style="width: 100%; height: 100%;" class="border border-2 rounded form-background p-4">
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
                                            <label class="control-label" for="username_input">Username</label>
                                            <input class="form-control" id="username_input" type="text" name="username" placeholder="Username">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="first_name">First Name</label>
                                            <input class="form-control" type="text" id="first_name" name="first_name" placeholder="First Name">
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label" for="last_name">Last Name</label>
                                            <input class="form-control" type="text" id="last_name" name="last_name" placeholder="Last Name">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="email">Email</label>
                                            <input class="form-control" type="email" id="email" name="email" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="pwd">Password</label>
                                            <input class="form-control" type="password" id="pwd" name="pwd" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="repeat_pwd">Password Repeat</label>
                                            <input class="form-control" type="password" id="repeat_pwd" name="repeat_pwd" placeholder="Re-enter password">
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary mt-3">Sign up</button>
                                        </div>
                                        <br>
                                        <center>
                                            <a href="" id="login-btn">Login</a>
                                        </center>
                                    </form>
                                </section>

                                <section id="login-form" style="width: 100%; height: 100%;" class="hide">
                                    <form action="includes/login.inc.php" method="post" style="width: 100%; height: 100%;" class="border border-2 rounded form-background p-4">
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
                                            <button type="submit" class="btn btn-primary my-3">Login</button>
                                        </div>
                                        <center>
                                            <a href="#" id="sign-up-btn">Sign Up</a>
                                        </center>
                                    </form>
                                </section>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        <?php } ?>
        <script>
            const signUpForm = document.getElementById('sign-up-form');
            const loginForm = document.getElementById('login-form');
            const loginBtn = document.getElementById('login-btn');
            const signUpBtn = document.getElementById('sign-up-btn');

            loginBtn.addEventListener('click', (e) => {
                e.preventDefault();
                signUpForm.classList.add('hide');
                loginForm.classList.remove('hide');
            });

            signUpBtn.addEventListener('click', (e) => {
                e.preventDefault();
                signUpForm.classList.remove('hide');
                loginForm.classList.add('hide');
            });
        </script>

    <?php
    }
    ?>
    <?php
    if (isset($_SESSION['user_id'])) {
    ?>
        <form class="" id="journal-form" method="post" action="includes/journal-form.inc.php?page_num=<?php echo $_GET['page_num']; ?>">
            <?php
            require_once 'journal-form.php';
            ?>
            <button id="journal-submit-btn" type="submit" class="journal-submit-btn hide">Submit</button>
        </form>
    <?php
    }
    ?>







    <?php
    require_once 'footer.php';
    ?>