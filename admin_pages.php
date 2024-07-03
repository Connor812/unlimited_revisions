<?php

require_once "config-url.php";
session_start();

if (!isset($_SESSION["admin_username"])) {
    header("Location: " . BASE_URL . "admin.php?error=access_denied");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'admin-header.php';
require_once "connect/db.php";
require_once "includes/admin_errors.inc.php";
require_once 'includes/display-sections.inc.php';

?>

<div class="container-fluid border border-bottom border-dark p-3" style="background-color: white;">

    <div class="row">
        <div class="col-sm-6">
            <form method='post' action='<?php echo BASE_URL . "admin_pages.php" ?>'>
                <div class="input-group mb-3">
                    <button class="btn btn-primary" type="submit" value="Submit">Submit</button>
                    <select class="form-select" name="selected_page" id="selected_page" aria-label="Example select with button addon">
                        <option value="add_page">Add Page...</option>
                        <?php

                        $selected_page = "";
                        $inputValue = "";

                        require_once "functions/get-page-num.php";

                        // First check if theres a get for page_num or post for selected_page

                        if (empty($_POST["selected_page"]) && empty($_GET["page_num"])) {
                            $selected_page = 1;
                            $maxPageNum = getMaxPageNum($mysqli);
                            $page_num = $selected_page;
                            if ($maxPageNum !== false) {
                                for ($i = 1; $i <= $maxPageNum; $i++) {
                                    $selected = ($selected_page == $i) ? "selected" : "";
                                    $pageName = getPageName($mysqli, $i);
                                    if ($selected_page == $i) {
                                        echo "works";
                                        $inputValue = $pageName;
                                    }
                                    $optionValue = $pageName ? $pageName : "Page " . $i;

                                    echo "<option value='$i' $selected>$optionValue</option>";
                                }
                            } else {
                                echo "Error getting max page number.";
                            }
                        } elseif (isset($_POST["selected_page"])) {

                            if ($_POST["selected_page"] == "add_page") {
                                $selected_page = $_POST["selected_page"];
                                $maxPageNum = getMaxPageNum($mysqli) + 1;
                                $page_num = $maxPageNum;
                                if ($maxPageNum !== false) {
                                    for ($i = 1; $i <= $maxPageNum; $i++) {
                                        $selected = ($maxPageNum == $i) ? "selected" : "";
                                        $pageName = getPageName($mysqli, $i);
                                        if ($selected_page == $i) {
                                            echo "works";
                                            $inputValue = $pageName;
                                        }
                                        $optionValue = $pageName ? $pageName : "Page " . $i;

                                        echo "<option value='$i' $selected data-input-value='$inputValue'>$optionValue</option>";
                                    }
                                } else {
                                    echo "Error getting max page number.";
                                }
                            } else {
                                $selected_page = $_POST["selected_page"];
                                $maxPageNum = getMaxPageNum($mysqli);
                                $page_num = $selected_page;
                                if ($maxPageNum !== false) {
                                    for ($i = 1; $i <= $maxPageNum; $i++) {
                                        $selected = ($selected_page == $i) ? "selected" : "";
                                        $pageName = getPageName($mysqli, $i);
                                        if ($selected_page == $i) {
                                            echo "works";
                                            $inputValue = $pageName;
                                        }
                                        $optionValue = $pageName ? $pageName : "Page " . $i;

                                        echo "<option value='$i' $selected>$optionValue</option>";
                                    }
                                } else {
                                    echo "Error getting max page number.";
                                }
                            }
                        } elseif (isset($_GET["page_num"])) {
                            $selected_page = $_GET["page_num"];
                            $maxPageNum = getMaxPageNum($mysqli);
                            $page_num = $selected_page;
                            echo $page_num;
                            echo $maxPageNum;

                            if ($page_num > $maxPageNum) {
                                $maxPageNum = $maxPageNum + 1;
                            }

                            echo $maxPageNum;
                            if ($maxPageNum !== false) {
                                for ($i = 1; $i <= $maxPageNum; $i++) {
                                    $selected = ($selected_page == $i) ? "selected" : "";
                                    $pageName = getPageName($mysqli, $i);
                                    if ($selected_page == $i) {
                                        echo "works";
                                        $inputValue = $pageName;
                                    }
                                    $optionValue = $pageName ? $pageName : "Page " . $i;

                                    echo "<option value='$i' $selected>$optionValue</option>";
                                }
                            } else {
                                echo "Error getting max page number.";
                            }
                        }
                        ?>
                    </select>
                </div>
            </form>
            <form action="includes/update-page-name.inc.php" method="post">
                <div class="input-group mb-3">
                    <button class="btn btn-primary" type="submit" id="update-page-name">Update Page
                        Name</button>
                    <input name="page_name" type="text" class="form-control" placeholder="Enter Page Name" value="<?php echo $inputValue; ?>" aria-label="Example text with button addon" aria-describedby="button-addon1">
                    <input type="hidden" name="page_num" value="<?php echo $page_num ?>">
                </div>
            </form>
        </div>
        <div class="col-sm-6">
            <center>
                <?php
                if (!isset($_GET["preview"])) { ?>
                    <a href="<?php echo BASE_URL . "admin_pages.php?page_num=$page_num&preview=true"; ?>"><button class="btn btn-primary">Preview Page</button></a>
                <?php
                } else {
                ?>
                    <a href="<?php echo BASE_URL . "admin_pages.php?page_num=$page_num"; ?>"><button class="btn btn-primary">Edit Page</button></a>
                <?php
                }
                ?>
                <button id="delete-page-btn" class="btn btn-danger modal_button" type="button" page_num="<?php echo $page_num ?>" data-mdb-toggle="modal" data-mdb-target="#delete-page-modal">Delete Page</button>
            </center>
        </div>
    </div>

    <!-- Modals -->
    <!-- Heading Modal -->
    <div class="modal top fade" add-type="header" add-section="add-section" id="heading-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Heading</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->
                    <form id="heading_form" method="post">
                        <h5 class="d-flex justify-content-start">Heading Text</h5>
                        <input name="heading_content" placeholder="Heading Text" type="text" class="form-control" autocomplete="off" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save New Heading</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Subtitle Modal -->
    <div class="modal top fade" add-type="subheading" add-section="add-section" id="subheading-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Subheading</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->
                    <form id="subheading_form" method="post">
                        <h5 class="d-flex justify-content-start">Subheading Title</h5>
                        <input name="subheading_content" placeholder="subheading title" type="text" id="modal-value-input" class="form-control" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save New Subheading</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quote Modal -->
    <div class="modal top fade" add-type="quote" add-section="add-section" id="quote-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Quote</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->
                    <form id="quote_form" method="post">
                        <h5 class="d-flex justify-content-start">Quote</h5>
                        <input name="quote_content" placeholder="Quote" type="text" id="modal-value-input" class="form-control" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save New Quote</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Byline Modal -->
    <div class="modal top fade" add-type="byline" add-section="add-section" id="byline-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Byline</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->

                    <form id="byline_form" method="post">
                        <h5 class="d-flex justify-content-start">Byline</h5>
                        <input name="byline_content" placeholder="Byline text" type="text" class="form-control" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Story Box Modal -->
    <div class="modal top fade" add-type="story_box" add-section="add-section" id="story-box-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Story Box</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->
                    <form id="story_box_form" method="post">
                        <h5 class="d-flex justify-content-center">Story Box</h5>
                        <label class="d-flex justify-content-start">Story Box Title</label>
                        <input name="story_box_name" placeholder="Story Box Title/Question" type="text" class="form-control" />
                        <label class="d-flex justify-content-start">Placeholder Text</label>
                        <input name="placeholder_text" placeholder="Examples or a description of the story box" type="text" class="form-control" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Modal -->
    <div class="modal top fade" add-type="video" add-section="add-section" id="video-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Video</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->
                    <form id="video_form" method="post" enctype="multipart/form-data">

                        <label class="form-label" for="customFile">Video Name</label>
                        <input name="section_name" type="text" class="form-control" placeholder="Chose a name for the video" />
                        <label class="form-label" for="customFile">Upload a video</label>
                        <input name="my_video" type="file" class="form-control" id="customFile" />

                        <input type="submit" class="btn btn-primary" name="submit" value="Upload">
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Check Box Modal -->
    <div class="modal top fade" add-type="check_box" add-section="add-section" id="check-box-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Check Box</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Closse"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->
                    <form id="check_box_form" method="post">
                        <label class="d-flex justify-content-start">Name of List</label>
                        <input name="section_name" placeholder="Chose a name for the list" type="text" class="form-control" />
                        <div id="click_list_input_container"></div>

                        <!-- Drop down for the textarea or checkbox items -->
                        <div class="dropdown">
                            <button class="btn btn-primary" type="button" id="add_item_btn">
                                Add Checkbox
                            </button>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal top fade" add-type="image" add-section="add-section" id="image-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Image</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->
                    <form id="image_form" method="post" enctype="multipart/form-data">

                        <label class="form-label" for="customFile">Image Name</label>
                        <input name="section_name" type="text" class="form-control" placeholder="Chose a name for the Image" />
                        <label class="form-label" for="customFile">Image Text</label>
                        <textarea name="image_text" type="text" class="form-control" placeholder="The text that will be displayed beside the image"></textarea>
                        <label class="form-label" for="customFile">Upload a Image</label>
                        <input name="my_image" type="file" class="form-control" id="customFile" />

                        <input type="submit" class="btn btn-primary" name="submit" value="Upload">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bullets Modal -->
    <div class="modal top fade" add-type="bullets" add-section="add-section" id="bullets-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Bullets</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->

                    <form id="bullet_form" method="post">
                        <label class="form-label">Bullet List Name</label>
                        <input name="section_name" type="text" class="form-control" placeholder="Chose a name for the Bullet List" />

                        <div id="bullet_input_container"></div>
                        <button id="add_bullet_btn" class="btn btn-primary" type="button">Add Bullet</button>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Text Modal -->
    <div class="modal top fade" add-type="text" add-section="add-section" id="text-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Text</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->

                    <form id="text_form" method="post">

                        <label class="form-label">Text Name</label>
                        <input name="section_name" type="text" class="form-control" placeholder="Chose a name for Text Section" />
                        <label class="form-label">Text Content</label>
                        <textarea name="text_content" type="text" class="form-control" placeholder="Please type your text"></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment Modal -->
    <div class="modal top fade" add-type="Comment" add-section="add-section" id="comment-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Comment</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->

                    <form id="comment_form" method="post">

                        <label class="form-label">What do you want the user to comment on</label>
                        <input name="section_name" type="text" class="form-control" placeholder="Please give a brief description of the question you are asking" />
                        <label class="form-label">Comment Description</label>
                        <textarea name="comment_placeholder" type="text" class="form-control" placeholder="Please explain/examples of the question you are asking"></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reference Modal -->
    <div class="modal top fade" add-type="reference" add-section="add-section" id="reference-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Reference</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3>Create a Reference to Prior User Input</h3>


                    <form method="post" id="reference_form">
                        <label for="reference-name">Title For Reference:</label>
                        <input type="text" name="reference-title" class="form-control" placeholder="Enter title for reference" required>
                        <label for="reference-description">Description For Reference (Optional):</label>
                        <textarea class="form-control" name="reference-description" id="reference-description" rows="5" placeholder="Enter a description for the reference"></textarea>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <label for="page-select">Select A Page:</label>
                                <select class="form-select form-select-sm" id="page-select" name="page_num" aria-label=".form-select-sm example" required>
                                    <option value="no_page_num" selected disabled>Please Select A Page</option>
                                    <?php
                                    $amount_pf_page = getMaxPageNum($mysqli);
                                    for ($i = 1; $amount_pf_page >= $i; $i++) {
                                        $page_name = getPageName($mysqli, $i);
                                    ?>
                                        <option value="<?php echo $i ?>">
                                            <?php echo ($page_name) ? $page_name : "Page $i" ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="reference-select">Select A User Input To Reference:</label>
                                <select class="form-select form-select-sm" id="reference-select" name="reference-num" aria-label=".form-select-sm example" required style="max-width: 221px;">
                                    <option value="no_reference_num" selected disabled>Please Select A Reference</option>

                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- // % user-input-table Modal -->
    <div class="modal top fade" add-type="user-input-table" add-section="add-section" id="user-input-table-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a User Input Table</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->
                    <form id="user-input-table-form" method="post">

                        <center>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Page
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <?php
                                    $amount_pf_page = getMaxPageNum($mysqli);
                                    for ($i = 1; $amount_pf_page >= $i; $i++) {
                                        $page_name = getPageName($mysqli, $i);
                                    ?>
                                        <li>
                                            <button class="dropdown-item" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo "collapse_$i" ?>" aria-expanded="false" aria-controls="collapseExample" page_num="<?php echo $i ?>"><?php echo ($page_name) ? $page_name : "Page $i" ?></button>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </center>

                        <?php
                        $amount_of_pages = getMaxPageNum($mysqli);
                        for ($page_num = 1; $amount_of_pages >= $page_num; $page_num++) {
                            $page_name = getPageName($mysqli, $page_num);
                        ?>
                            <div class="collapse user-input-collapse" id="<?php echo "collapse_$page_num" ?>">
                                <div class="card card-body">
                                    <?php
                                    $sql_journal_page = "SELECT jp.*, cl.id AS component_id FROM journal_page jp JOIN click_list cl ON jp.id = cl.section_id WHERE jp.page_num = ? AND jp.section_type = 'click_list' UNION ALL SELECT jp.*, cm.id AS component_id FROM journal_page jp JOIN comment cm ON jp.id = cm.section_id WHERE jp.page_num = ? AND jp.section_type = 'comment' UNION ALL SELECT jp.*, sb.id AS component_id FROM journal_page jp JOIN story_box sb ON jp.id = sb.section_id WHERE jp.page_num = ? AND jp.section_type = 'story_box'; ";
                                    $stmt_journal_page = $mysqli->prepare($sql_journal_page);

                                    if (!$stmt_journal_page) {
                                        echo "Error fetching questions";
                                    }

                                    $stmt_journal_page->bind_param("iii", $page_num, $page_num, $page_num);

                                    if (!$stmt_journal_page->execute()) {
                                        echo "Error fetching questions";
                                        $stmt_journal_page->close();
                                        exit;
                                    }

                                    $result_journal_page = $stmt_journal_page->get_result();

                                    if ($result_journal_page->num_rows > 0) {
                                        while ($journal_row = $result_journal_page->fetch_assoc()) {
                                            $section_id = $journal_row["id"];
                                            $section_name = $journal_row["section_name"];
                                            $order_num = $journal_row["order_num"];
                                            $section_type = $journal_row["section_type"];
                                            $component_id = $journal_row["component_id"];

                                            if ($section_type === "click_list") {
                                                $sql_click_list = "SELECT * FROM `click_list` WHERE section_id = ?;";
                                                $stmt_click_list = $mysqli->prepare($sql_click_list);

                                                if (!$stmt_click_list) {
                                                    echo "Error Getting Click List Items";
                                                }

                                                $stmt_click_list->bind_param("i", $section_id);

                                                if (!$stmt_click_list->execute()) {
                                                    echo "Error Getting Click List Items";
                                                    $stmt_click_list->close();
                                                    exit;
                                                }

                                                $result_click_list = $stmt_click_list->get_result();

                                                if ($result_click_list->num_rows > 0) {
                                                    while ($click_list_row = $result_click_list->fetch_assoc()) {
                                                        $click_list_id = $click_list_row["id"];
                                                        $sql_click_list_items = "SELECT * FROM `click_list_items` WHERE click_list_id = ? AND item_type = 'checkbox';";
                                                        $stmt_click_list_items = $mysqli->prepare($sql_click_list_items);

                                                        if (!$stmt_click_list_items) {
                                                            echo "Error Getting Click List Items";
                                                            exit;
                                                        }

                                                        $stmt_click_list_items->bind_param("i", $click_list_id);

                                                        if (!$stmt_click_list_items->execute()) {
                                                            echo "Error Getting Click List Items";
                                                            $stmt_click_list_items->close();
                                                            exit;
                                                        }

                                                        $result_click_list_items = $stmt_click_list_items->get_result();

                                                        if ($result_click_list_items->num_rows > 0) {
                                                            while ($click_list_item_row = $result_click_list_items->fetch_assoc()) {
                                                                $item_id = $click_list_item_row["id"];
                                                                $item_title = $click_list_item_row["item_title"];
                                                                $item_userdata_name = $click_list_item_row["item_userdata_name"];
                                                                $click_list_id = $click_list_item_row["click_list_id"];
                                    ?>
                                                                <hr>
                                                                <div class="question">
                                                                    <input id="<?php echo "question_$order_num" ?>" name="questions[]" type="checkbox" value="<?php echo $item_id . ";" . $section_type ?>">
                                                                    <label for=" <?php echo "question_$order_num" ?>"><?php echo $item_title ?></label>
                                                                </div>
                                                <?php
                                                            }
                                                        } else {
                                                            echo "No questions found";
                                                        }
                                                    }
                                                } else {
                                                    echo "No questions found";
                                                }
                                            } else {
                                                ?>
                                                <div class="question">
                                                    <input id="<?php echo "question_$order_num" ?>" name="questions[]" type="checkbox" value="<?php echo $component_id . ";" . $section_type ?>">
                                                    <label for="<?php echo "question_$order_num" ?>"><?php echo $section_name ?></label>
                                                </div>
                                    <?php
                                            }
                                        }
                                    } else {
                                        echo "No questions found";
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php } ?>

                        <script>
                            const collapse = document.querySelectorAll('.user-input-collapse');

                            collapse.forEach((item) => {
                                item.addEventListener('show.bs.collapse', (event) => {
                                    const currentCollapse = event.target;
                                    collapse.forEach((collapseItem) => {
                                        if (collapseItem !== currentCollapse) {
                                            collapseItem.classList.remove('show');
                                        }
                                    });
                                });
                            });
                        </script>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Section Confirmation Modal -->
    <div class="modal top fade" add-type="Comment" id="delete-section-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Do you want to delete this section</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->

                    <form id="delete_section_form" method="post" class="d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                            Don't Delete
                        </button>
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Page Confirmation Modal -->
    <div class="modal top fade" add-type="Comment" id="delete-page-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Do you want to delete this Page</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Content goes here -->

                    <form id="delete_page_form" method="post" class="d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                            Don't Delete
                        </button>
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Button Modal -->
    <div class="modal top fade" id="button-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog   modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Section</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-center gap-3">
                    <!-- Modal Content goes here -->

                    <button type="button" class="btn btn-primary modal_button" form_type="heading" data-mdb-toggle="modal" data-mdb-target="#heading-modal">
                        Heading
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="subheading" data-mdb-toggle="modal" data-mdb-target="#subheading-modal">
                        Subheading
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="quote" data-mdb-toggle="modal" data-mdb-target="#quote-modal">
                        Quote
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="byline" data-mdb-toggle="modal" data-mdb-target="#byline-modal">
                        Byline
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="story_box" data-mdb-toggle="modal" data-mdb-target="#story-box-modal">
                        Story Box
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="video" data-mdb-toggle="modal" data-mdb-target="#video-modal">
                        Video
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="check_box" data-mdb-toggle="modal" data-mdb-target="#check-box-modal">
                        Check Box
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="image" data-mdb-toggle="modal" data-mdb-target="#image-modal">
                        Image
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="bullets" data-mdb-toggle="modal" data-mdb-target="#bullets-modal">
                        Bullets
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="text" data-mdb-toggle="modal" data-mdb-target="#text-modal">
                        Text
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="comment" data-mdb-toggle="modal" data-mdb-target="#comment-modal">
                        Comment
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="reference" data-mdb-toggle="modal" data-mdb-target="#reference-modal">
                        Reference
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="user-input-table" data-mdb-toggle="modal" data-mdb-target="#user-input-table-modal">
                        User Input Table
                    </button>
                    <button type="button" class="btn btn-primary modal_button" form_type="user-input-table-2" data-mdb-toggle="modal" data-mdb-target="#user-input-table-modal">
                        User Input Table 2
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_GET["preview"]) && $_GET["preview"] == true) {
    display_sections($selected_page, $mysqli, false);
} else {
?>
    <div style="width: 100%; display: flex; justify-content: center;">
        <button type="button" class="add-section-btn btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#button-modal" section_id="0">Add</button>
    </div>

    <div class=admin-page-wrapper>
    <?php display_sections($selected_page, $mysqli, true);
} ?>
    </div>

    <?php
    require_once 'admin_footer.php';
    ?>