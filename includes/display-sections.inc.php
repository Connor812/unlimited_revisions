<?php

require_once(__DIR__ . "/../config-url.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

function display_sections($page_num, $mysqli, $add_button = false, $user_id = false)
{

    if (isset($_POST['selected_page']) || isset($_GET['page_num'])) {
        $page_num = $_POST['selected_page'] ?? ($_GET['page_num'] ?? 1);
    }

    $sql = "SELECT
    jp.id AS journal_page_id,
    jp.page_num,
    jp.section_type,
    jp.section_name,
    jp.order_num,
    h.id AS heading_id,
    h.heading_content,
    q.id AS quote_id,
    q.quote_content,
    b.id AS byline_id,
    b.byline_content,
    sb.id AS story_box_id,
    sb.story_box_userdata_name,
    sb.placeholder_text,
    v.id AS video_id,
    v.video_src,
    c.id AS click_list_id,
    sh.id AS subheading_id,
    sh.subheading_content,
    i.id AS image_id,
    i.image_src,
    i.image_text,
    cm.id AS comment_id, 
    cm.comment_userdata_name,
    cm.comment_placeholder,
    bt.id AS bullet_id,
    t.id AS text_id,
    t.text_content,
    r.description,
    r.userdata_reference_num
FROM journal_page AS jp
LEFT JOIN heading AS h ON jp.id = h.section_id
LEFT JOIN quote AS q ON jp.id = q.section_id
LEFT JOIN byline AS b ON jp.id = b.section_id
LEFT JOIN story_box AS sb ON jp.id = sb.section_id
LEFT JOIN video AS v ON jp.id = v.section_id
LEFT JOIN click_list AS c ON jp.id = c.section_id
LEFT JOIN subheading AS sh ON jp.id = sh.section_id
LEFT JOIN image AS i ON jp.id = i.section_id
LEFT JOIN comment AS cm ON jp.id = cm.section_id
LEFT JOIN bullet AS bt ON jp.id = bt.section_id
LEFT JOIN text AS t ON jp.id = t.section_id
LEFT JOIN reference AS r ON jp.id = r.section_id
WHERE jp.page_num = ?
ORDER BY jp.order_num ASC;";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $page_num);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['section_type'] == 'heading') {
                    heading_section($row, $add_button);
                } elseif ($row['section_type'] == 'click_list') {
                    click_list_section($row, $mysqli, $add_button, $user_id);
                } elseif ($row['section_type'] == 'quote') {
                    quote_section($row, $add_button);
                } elseif ($row['section_type'] == 'byline') {
                    byline_section($row, $add_button);
                } elseif ($row['section_type'] == 'story_box') {
                    story_box_section($row, $mysqli, $add_button, $user_id);
                } elseif ($row['section_type'] == 'video') {
                    video_section($row, $add_button);
                } elseif ($row['section_type'] == 'subheading') {
                    subheading_section($row, $add_button);
                } elseif ($row['section_type'] == 'image') {
                    image_section($row, $add_button);
                } elseif ($row['section_type'] == 'bullet') {
                    bullet_section($row, $mysqli, $add_button);
                } elseif ($row['section_type'] == 'text') {
                    text_section($row, $add_button);
                } elseif ($row['section_type'] == 'comment') {
                    comment_section($row, $mysqli, $add_button, $user_id);
                } elseif ($row['section_type'] == 'reference') {
                    reference_section($row, $mysqli, $add_button, $user_id);
                } elseif ($row['section_type'] == 'user_input_table') {
                    user_input_table_section($row, $mysqli, $add_button, $user_id);
                }
            }
        } else {
            echo "Query Error: " . mysqli_error($mysqli);
        }
    }
}

// Takes care of the header content--------------------------------------------------->
function heading_section($heading_row, $add_button = false)
{
    $heading_content = $heading_row["heading_content"];
    $order_num = $heading_row["order_num"];
    $page_num = $heading_row["page_num"];
    $section_type = $heading_row["section_type"];

?>
    <section class='section_container' style='width: 100%;' id='<?php echo $order_num ?>'>
        <h2 id="<?php echo $heading_content ?>" class="heading" style='text-align: center;'>
            <?php echo $heading_content ?>
        </h2>
        <hr>
    </section>
<?php

    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

// Takes care of the click list content--------------------------------------------------->
function click_list_section($click_list_row, $mysqli, $add_button, $user_id = false)
{

    $section_name = $click_list_row["section_name"];
    $click_list_id = $click_list_row['click_list_id'];
    $order_num = $click_list_row["order_num"];
    $label = "label_" . $order_num;
    $page_num = $click_list_row["page_num"];
    $section_type = $click_list_row["section_type"];

?>
    <section class='accordion section_container' id='<?php echo $order_num ?>'>
        <div class='accordion-item'>
            <h2 class='accordion-header'>
                <button class='accordion-button collapsed checkbox-btn' type='button' data-mdb-toggle='collapse' data-mdb-target='#<?php echo $label ?>' aria-expanded='true' aria-controls='<?php echo $label ?>'>
                    <?php echo $section_name ?>
                </button>
            </h2>
            <div id='<?php echo $label ?>' class='accordion-collapse collapse' aria-labelledby='<?php echo $label ?>' data-mdb-parent='#<?php echo $label ?>'>
                <div class='accordion-body'>
                    <?php echo get_click_list_items($click_list_id, $mysqli, $user_id) ?>
                </div>
            </div>
        </div>
    </section>

    <?php

    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

// Takes care of the items inside of the click list --------------------------------------------------->

function get_click_list_items($click_list_id, $mysqli, $user_id)
{
    $content = ''; // Initialize an empty string to store the content

    $sql = "SELECT
    cl.id AS click_list_id,
    cl.section_id AS click_list_section_id,
    cli.id AS click_list_item_id,
    cli.item_type AS click_list_item_type,
    cli.item_title AS item_title,
    cli.placeholder_text AS placeholder_text,
    cli.item_userdata_name AS item_userdata_name
FROM click_list AS cl
LEFT JOIN click_list_items AS cli ON cl.id = cli.click_list_id
WHERE cl.id = ?;";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $click_list_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo "Failed to grab the click_list content <br>";
            exit();
        }

        foreach ($result as $click_list_item) {
            $id = $click_list_item["click_list_item_id"];
            $item_userdata_name = $click_list_item['item_userdata_name'];
            $item_title = $click_list_item['item_title'];
            $item_type = $click_list_item['click_list_item_type'];

            if ($item_type == 'checkbox') {
    ?>
                <div class="form-check">
                    <input type="hidden" name="<?php echo $item_userdata_name ?>" value="0">
                    <!-- Hidden input for unchecked checkbox -->
                    <input name="<?php echo $item_userdata_name ?>" class="form-check-input checkbox" checkbox-id="<?php echo $id ?>" type="checkbox" value="1" id="defaultCheck1" <?php
                                                                                                                                                                                    if (is_numeric($user_id)) {
                                                                                                                                                                                        echo get_click_list_user_input($item_type, $item_userdata_name, $user_id, $mysqli);
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>>

                    <label class="form-check-label" for="defaultCheck1">
                        <?php echo $item_title ?>
                    </label>
                    <input type="hidden" name="<?php echo $item_userdata_name ?>_type" value="checkbox">
                    <!-- Add a hidden input for checkbox -->
                </div>
                <br>
            <?php

            } elseif ($click_list_item['click_list_item_type'] == 'textarea') {
                $placeholder_text = $click_list_item['placeholder_text'];
            ?>
                <div id="textarea-<?php echo $id ?>" class="form-check textarea hidden">
                    <label for="comment">
                        <?php echo $item_title ?>
                    </label>
                    <textarea name="<?php echo $item_userdata_name ?>" class="form-control textarea" rows="7" id="comment" placeholder="<?php echo $placeholder_text ?>"><?php if (is_numeric($user_id)) {
                                                                                                                                                                                echo get_click_list_user_input($item_type, $item_userdata_name, $user_id, $mysqli);
                                                                                                                                                                            } ?></textarea>
                    <input type="hidden" name="<?php echo $item_userdata_name ?>_type" value="textarea">
                    <!-- Add a hidden input for checkbox -->
                </div>
                <br>
    <?php
            }
        }
    } else {
        echo "Prepare failed: " . $mysqli->error;
        exit;
    }
}

// If there is userdata, this function will fetch that data

function get_click_list_user_input($item_type, $item_userdata_name, $user_id, $mysqli)
{

    if ($item_type == 'checkbox') {
        $sql = "SELECT `$item_userdata_name` FROM `user_input` WHERE user_id = ?;";

        $stmt = $mysqli->prepare($sql);
        if ($stmt) {

            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if ($row[$item_userdata_name] == 1) {
                    return 'checked';
                } else {
                    return '';
                }
            }
        }
    } elseif ($item_type == 'textarea') {
        $sql = "SELECT `$item_userdata_name` FROM `user_input` WHERE user_id = ?;";

        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $user_data = $row[$item_userdata_name];
            }
        }
    }
}

// Takes care of the quote_section --------------------------------------------------->
function quote_section($quote_row, $add_button)
{

    $quote_content = $quote_row["quote_content"];
    $order_num = $quote_row['order_num'];
    $page_num = $quote_row["page_num"];
    $section_type = $quote_row["section_type"];

    ?>
    <section class="section_container" id="<?php echo $order_num ?>">
        <h5 class="px-5 quote" style="padding: 0% 0%; text-align: center;"><i>"
                <?php echo $quote_content ?>"
            </i></h5>
    </section>
<?php
    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

// Takes care of the byline_section --------------------------------------------------->
function byline_section($byline_row, $add_button)
{
    $section_name = $byline_row['section_name'];
    $byline_content = $byline_row['byline_content'];
    $order_num = $byline_row['order_num'];
    $page_num = $byline_row["page_num"];
    $section_type = $byline_row["section_type"];
?>
    <section class="section_container" id="<?php echo $order_num ?>">
        <h5 class="byline" id="<?php echo $section_name ?>" style="padding: 0% 0%; text-align: center;"><b>
                <?php echo $byline_content ?>
            </b> </h5>
    </section>
<?php
    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

// Takes care of the subheading_section --------------------------------------------------->

function subheading_section($subheading_row, $add_button)
{
    $section_name = $subheading_row['section_name'];
    $subheading_content = $subheading_row['subheading_content'];
    $order_num = $subheading_row['order_num'];
    $page_num = $subheading_row["page_num"];
    $section_type = $subheading_row["section_type"];
?>
    <section id='<?php echo $order_num ?>' class='section_container'>
        <h4 class='d-flex justify-content-center subheading' id='<?php echo $section_name ?>' style='padding: 10px;'><b>
                <?php echo $subheading_content ?>
            </b></h4>
    </section>
<?php
    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}


// Takes care of the story_box_section --------------------------------------------------->
function story_box_section($story_box_row, $mysqli, $add_button, $user_id = false)
{
    $section_name = $story_box_row['section_name'];
    $story_box_userdata_name = $story_box_row['story_box_userdata_name'];
    $placeholder_text = $story_box_row['placeholder_text'];
    $order_num = $story_box_row['order_num'];
    $label = "label_" . $order_num;
    $page_num = $story_box_row["page_num"];
    $section_type = $story_box_row["section_type"];

?>
    <section class="accordion section_container story_box" id="<?php echo $order_num ?>">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed story_box-btn" type="button" data-mdb-toggle="collapse" data-mdb-target="#<?php echo $label ?>" aria-expanded="true" aria-controls="<?php echo $label ?>">
                    <?php echo $section_name ?>
                </button>
            </h2>
            <div id="<?php echo $label ?>" class="accordion-collapse collapse" aria-labelledby="<?php echo $label ?>" data-mdb-parent="#parent_<?php echo $order_num ?>">
                <div class="accordion-body">
                    <textarea name="<?php echo $story_box_userdata_name ?>" class="form-control story-box-textarea" rows="5" id="comment" placeholder="<?php echo $placeholder_text ?>"><?php
                                                                                                                                                                                        if (is_numeric($user_id)) {
                                                                                                                                                                                            echo get_story_box_userdata($user_id, $story_box_userdata_name, $mysqli);
                                                                                                                                                                                        } ?></textarea>
                    <br>
                </div>
            </div>
        </div>
    </section>

<?php
    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

// Gets the story box data if the user id is passes

function get_story_box_userdata($user_id, $story_box_userdata_name, $mysqli)
{
    $sql = "SELECT `$story_box_userdata_name` FROM `user_input` WHERE user_id = ?;";

    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row[$story_box_userdata_name];
        }
    }
}

// Takes care of the video_section --------------------------------------------------->
function video_section($video_row, $add_button)
{
    $video_src = $video_row['video_src'];
    $order_num = $video_row['order_num'];
    $page_num = $video_row["page_num"];
    $section_type = $video_row["section_type"];

?>
    <section id="<?php echo $order_num ?>" class="videobg d-flex justify-content-center section_container">
        <video class="video" width="80%" height="auto" poster="/videos/URposter.png" controls>
            <source src="<?php echo BASE_URL . "/" . $video_src ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </section>

<?php

    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

// Handles displaying the image
function image_section($image_row, $add_button)
{
    $section_name = $image_row['section_name'];
    $image_src = $image_row['image_src'];
    $order_num = $image_row['order_num'];
    $image_text = $image_row['image_text'];
    $page_num = $image_row["page_num"];
    $section_type = $image_row["section_type"];

?>
    <section id="<?php echo $order_num ?>" class="row p-3 section_container">
        <div class="col-sm-8 image_text" style="display: flex; flex-direction: column; justify-content: space-between; word-wrap: break-word;">
            <?php echo $image_text ?>
        </div>
        <div class="col-sm-4">
            <div>
                <img src="<?php echo BASE_URL . "/" . $image_src ?>" class="img-rounded image-responsive image" alt="<?php echo $section_name ?>" width="100%" height="auto">
            </div>
        </div>
    </section>
<?php

    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

// Handles displaying a bullet section
function bullet_section($bullet_row, $mysqli, $add_button)
{

    $section_name = $bullet_row['section_name'];
    $bullet_id = $bullet_row['bullet_id'];
    $order_num = $bullet_row['order_num'];
    $page_num = $bullet_row["page_num"];
    $section_type = $bullet_row["section_type"];

    $sql = 'SELECT * FROM `bullet_point` WHERE bullet_id = ?';

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        // Bind the parameter
        $stmt->bind_param("i", $bullet_id); // Assuming $bullet_id is the value you want to match

        // Execute the prepared statement
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        if ($result) {
            // Start the bullet point list
            echo '<section id="' . $order_num . '" class="section_container"><ul>';
            // Fetch data from the result set
            while ($row = $result->fetch_assoc()) {
                // Access data in $row
                $bullet_content = $row['bullet_content'];

                echo '<li>' . $bullet_content . '</li>';
            }
            // End the bullet point list
            echo '</ul></section>';
            // Close the statement
        } else {
            echo "Error getting result set: " . $mysqli->error;
            // Handle the error
        }
    } else {
        echo "Prepare failed: " . $mysqli->error;
        // Handle the error
    }

    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

// Handles displaying a text section
function text_section($text_row, $add_button)
{

    $text_content = $text_row['text_content'];
    $order_num = $text_row['order_num'];
    $page_num = $text_row["page_num"];
    $section_type = $text_row["section_type"];

?>

    <section id="<?php echo $order_num ?>" class="d-flex justify-content-center section_container text">
        <?php echo $text_content ?>
    </section>

<?php
    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

// This handles the comment section
function comment_section($comment_row, $mysqli, $add_button, $user_id = false)
{
    $section_name = $comment_row['section_name'];
    $comment_userdata_name = $comment_row['comment_userdata_name'];
    $placeholder_text = $comment_row['comment_placeholder'];
    $order_num = $comment_row['order_num'];
    $page_num = $comment_row["page_num"];
    $section_type = $comment_row["section_type"];



?>
    <section class="section_container" id="<?php echo $order_num ?>">
        <label class="comment_title" for="<?php echo $section_name ?>">
            <?php echo $section_name ?>
        </label>
        <textarea id="<?php echo $section_name ?>" name="<?php echo $comment_userdata_name ?>" class="form-control comment_input" rows="4" placeholder="<?php echo $placeholder_text ?>"><?php
                                                                                                                                                                                            if (is_numeric($user_id)) {
                                                                                                                                                                                                echo get_comment_userdata($user_id, $comment_userdata_name, $mysqli);
                                                                                                                                                                                            } ?></textarea>
    </section>

    <?php
    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

function get_comment_userdata($user_id, $comment_userdata_name, $mysqli)
{
    $sql = "SELECT `$comment_userdata_name` FROM `user_input` WHERE user_id = ?;";

    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row[$comment_userdata_name];
        }
    }
}

function reference_section($reference_row, $mysqli, $add_button, $user_id)
{

    $section_name = $reference_row['section_name'];
    $description = $reference_row['description'];
    $userdata_reference_num = $reference_row['userdata_reference_num'];
    $order_num = $reference_row['order_num'];
    $page_num = $reference_row["page_num"];
    $section_type = $reference_row["section_type"];
    if ($user_id) {
        $userdata = get_reference_userdata($user_id, $userdata_reference_num, $mysqli);
    } else {
        $userdata = $userdata_reference_num;
    }

    if (!empty($userdata)) {
    ?>

        <section class="section_container" id="<?php echo $order_num ?>">
            <h3 class="text-center">
                <?php echo $section_name ?>
            </h3>
            <hr>
            <p class="text-center">
                <?php echo $description ?>
            </p>
            <div class="reference-userdata border border-2">
                <?php echo $userdata ?>
            </div>
        </section>

    <?php
    }
    echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
}

function get_reference_userdata($user_id, $userdata_reference_num, $mysqli)
{

    $sql = "SELECT `$userdata_reference_num` FROM `user_input` WHERE user_id = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // ! Handle the case where the prepared statement could not be created
        return "Error";
    }

    $stmt->bind_param("i", $user_id);

    // ? checks to see if the execute fails
    if (!$stmt->execute()) {
        $stmt->close();
        return "Error";
    }

    // * Gets the Result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // * Your Code here
            return $row[$userdata_reference_num];
        }
    } else {
        // ! No data found
        return "No Data Found";
    }
}

// require_once("functions/user_input_table_functions.php");
if (strpos($_SERVER['REQUEST_URI'], 'admin_edit') !== false) {
    require_once("../functions/user_input_table_functions.php");
} else {
    require_once("functions/user_input_table_functions.php");
}

function user_input_table_section($user_input_row, $mysqli, $add_button, $user_id)
{
    $section_name = $user_input_row['section_name'];
    $order_num = $user_input_row['order_num'];
    $page_num = $user_input_row["page_num"];
    $section_type = $user_input_row["section_type"];
    $section_id = $user_input_row['journal_page_id'];

    ?>
    <section class="section_container" id="<?php echo $order_num ?>">

        <?php
        $result = get_user_input_table($section_id, $mysqli);
        // get all the questions asked in the pages that were provided
        $page_nums = $result['page_nums'];
        $part = $result['part'];

        if ($page_num === false) {
            return "Error getting page numbers";
        }

        $user_input_rows = get_section_ids($page_nums, $mysqli);

        if ($user_input_rows === false) {
            return "Error getting section ids";
        }

        if ($part === 1) {
        ?>
            <table class="user-input-table">
                <thead>
                    <tr>
                        <th>Your Answer</th>
                        <th>Please Elaborate On Your Answer</th>
                    </tr>
                </thead>

                <tbody>
                    <?php

                    foreach ($user_input_rows as $row) {
                        generate_table_rows($row, $mysqli, $user_id);
                    }

                    ?>
                </tbody>

            </table>
    </section>

<?php
        } else {
?>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>Your Answer</th>
                <th>Please Elaborate On Your Answer</th>
                <th>Final Overview</th>
            </tr>
        </thead>

        <tbody>
            <?php

            foreach ($user_input_rows as $row) {
                generate_table_rows_2($row, $mysqli, $user_id);
            }

            ?>
        </tbody>

    </table>
    </section>
<?php
        }
        echo $add_button ? add_button($order_num, $page_num, $section_type) : '';
    }

    function add_button($id, $page_num, $section_type)
    {

        $page_name = "";
        // This is for the action buttons. We need the section type, so the link will take them to the correct section to either edit add or delete
        if ($section_type == "heading") {
            $page_name = "heading";
        } elseif (($section_type == "subheading")) {
            $page_name = "subheading";
        } elseif (($section_type == "quote")) {
            $page_name = "quote";
        } elseif (($section_type == "byline")) {
            $page_name = "byline";
        } elseif (($section_type == "video")) {
            $page_name = "video";
        } elseif (($section_type == "image")) {
            $page_name = "image";
        } elseif (($section_type == "comment")) {
            $page_name = "comment";
        } elseif (($section_type == "text")) {
            $page_name = "text";
        } elseif (($section_type == "click_list")) {
            $page_name = "click_list";
        } elseif (($section_type == "story_box")) {
            $page_name = "story_box";
        } elseif (($section_type == "bullet")) {
            $page_name = "bullet";
        } elseif (($section_type == "reference")) {
            $page_name = "reference";
        }

?>
<div id="add<?php echo $id; ?>" class="add_section_line hide"></div>
<div class="action-btn-container">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            <svg width="20" height="20" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
            </svg>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li>
                <button type="button" class="add-section-btn dropdown-item" data-mdb-toggle="modal" data-mdb-target="#button-modal" section_id="<?php echo $id; ?>">Add</button>
            </li>
            <li>
                <a href="<?php echo BASE_URL ?>/admin_edit/edit_<?php echo $page_name ?>.php?page_num=<?php echo $page_num ?>&order_num=<?php echo $id ?>" class="edit-section-btn dropdown-item" section_id="<?php echo $id; ?>">Edit</a>
            </li>
            <li>
                <button type="button" class="delete-section-btn dropdown-item" data-mdb-toggle="modal" data-mdb-target="#delete-section-modal" section_id="<?php echo $id; ?>">Delete</button>
            </li>
        </ul>
    </div>

</div>
<?php
    }
