<?php

function generate_user_tables($section_name, $section_id, $mysqli, $user_id)
{
    $sql = "SELECT * FROM `user_input_table` WHERE section_id = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $section_id);

    if (!$stmt->execute()) {
        return false;
    }

    // * Gets the Result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $page_num = $row["page_num"];
            $seciton_id = $row["section_id"];
            $part = $row["part"];
            $type = $row["type"];
            $question_id = $row["question_id"];
            $userdata_info = get_userdata_info($mysqli, $question_id, $type, $section_name);
            $new_section_name = $userdata_info["section_name"];
            $userdata_name = $userdata_info["userdata_name"];
            $user_input_data = get_user_comment($mysqli, $user_id, $userdata_name, "1");
            $user_input_data_2 = get_user_comment($mysqli, $user_id, $userdata_name, "2");
            $user_comment_id = $user_input_data["user_comment_id"];
            $comment = $user_input_data["user_comment"];
            $is_there_data = $comment == false ? 'false' : 'true';
            $user_input = get_user_input($mysqli, $user_id, $userdata_name, $type);
            if ($part == "1") {
?>
                <tr>
                    <td style="width: 30%;">
                        <button class="question-mark" type="button" data-bs-toggle="collapse" data-bs-target="#question_<?php echo $userdata_name ?>" aria-expanded="false" aria-controls="question_<?php echo $userdata_name ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94" />
                            </svg>
                        </button>
                        <?php echo empty($user_input) ? "No Comment" : $user_input ?>
                        <div class="collapse" id="question_<?php echo $userdata_name ?>">
                            <div class="card card-body">
                                <?php echo $new_section_name ?>
                            </div>
                        </div>
                    </td>
                    <td style="width: 70%;">
                        <center>
                            <div class="d-flex">
                                <textarea name="user_comment" class="form-control" rows="3" placeholder="Enter your comment here" id="user_comment_<?php echo $section_id ?>"><?php echo $comment ?></textarea>
                                <input type="hidden" name="user_id" id="user_id_<?php echo $section_id ?>" value="<?php echo $user_id ?>">
                                <input type="hidden" name="userdata_name" id="userdata_name_<?php echo $section_id ?>" value="<?php echo $userdata_name ?>">
                                <input type="hidden" name="page_num" id="page_num_<?php echo $section_id ?>" value="<?php echo $userdata_name ?>">
                                <input type="hidden" name="stage" id="stage_<?php echo $section_id ?>" value="1">
                                <button class="btn btn-primary upload-user-comments" type="button" user_input_section_id="<?php echo $section_id ?>" user_id="<?php echo $user_id ?>" userdata_name="<?php echo $userdata_name ?>" textarea_id="user_comment_<?php echo $section_id ?>" is_there_data="<?php echo $is_there_data ?>" user_comment_id="<?php echo $user_comment_id ?>" stage="1">
                                    Save
                                </button>
                            </div>
                        </center>
                    </td>

                </tr>

            <?php
            } elseif ($part == "2") {
                $user_comment_id_2 = $user_input_data_2["user_comment_id"];
                $comment_2 = $user_input_data_2["user_comment"];
                $is_there_data_2 = $comment_2 == false ? 'false' : 'true';
            ?>
                <tr>
                    <td style="width: 30%;">
                        <button class="question-mark" type="button" data-bs-toggle="collapse" data-bs-target="#question_<?php echo $userdata_name ?>" aria-expanded="false" aria-controls="question_<?php echo $userdata_name ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94" />
                            </svg>
                        </button>
                        <?php echo empty($user_input) ? "No Comment" : $user_input ?>
                        <div class="collapse" id="question_<?php echo $userdata_name ?>">
                            <div class="card card-body">
                                <?php echo $new_section_name ?>
                            </div>
                        </div>
                    </td>
                    <td style="width: 35%;">
                        <center>
                            <div class="d-flex">
                                <textarea name="user_comment" class="form-control" rows="3" placeholder="Enter your comment here" id="user_comment_<?php echo $section_id ?>"><?php echo $comment ?></textarea>
                                <input type="hidden" name="user_id" id="user_id_<?php echo $section_id ?>" value="<?php echo $user_id ?>">
                                <input type="hidden" name="userdata_name" id="userdata_name_<?php echo $section_id ?>" value="<?php echo $userdata_name ?>">
                                <input type="hidden" name="page_num" id="page_num_<?php echo $section_id ?>" value="<?php echo $userdata_name ?>">
                                <input type="hidden" name="stage" id="stage_<?php echo $section_id ?>" value="1">
                                <button class="btn btn-primary upload-user-comments" type="button" user_input_section_id="<?php echo $section_id ?>" user_id="<?php echo $user_id ?>" userdata_name="<?php echo $userdata_name ?>" textarea_id="user_comment_<?php echo $section_id ?>" is_there_data="<?php echo $is_there_data ?>" user_comment_id="<?php echo $user_comment_id ?>" stage="1">
                                    Save
                                </button>
                            </div>
                        </center>
                    </td>
                    <td style="width: 35%;">
                        <center>
                            <div class="d-flex">
                                <textarea name="user_comment" class="form-control" rows="3" placeholder="Enter your comment here" id="user_comment_<?php echo $section_id ?>_2"><?php echo $comment_2 ?></textarea>
                                <input type="hidden" name="user_id" id="user_id_<?php echo $section_id ?>_2" value="<?php echo $user_id ?>">
                                <input type="hidden" name="userdata_name" id="userdata_name_<?php echo $section_id ?>_2" value="<?php echo $userdata_name ?>">
                                <input type="hidden" name="page_num" id="page_num_<?php echo $section_id ?>_2" value="<?php echo $userdata_name ?>">
                                <input type="hidden" name="stage" id="stage_<?php echo $section_id ?>_2" value="2">
                                <button class="btn btn-primary upload-user-comments" type="button" user_input_section_id="<?php echo $section_id ?>" user_id="<?php echo $user_id ?>" userdata_name="<?php echo $userdata_name ?>" textarea_id="user_comment_<?php echo $section_id ?>_2" is_there_data="<?php echo $is_there_data_2 ?>" user_comment_id="<?php echo $user_comment_id_2 ?>" stage="2">
                                    Save
                                </button>
                            </div>
                        </center>
                    </td>

                </tr>
<?php
            }
        }
    } else {
        echo "No Questions Found";
    }
}

function get_userdata_info($mysqli, $question_id, $type, $section_name)
{

    if ($type == "click_list") { // ^ This is the click list ------------->

        $question_name = get_click_list_items_userdata($mysqli, $question_id, "item_title");
        $question_userdata_name = get_click_list_items_userdata($mysqli, $question_id++, "item_userdata_name");

        return array(
            "section_name" => $question_name,
            "userdata_name" => $question_userdata_name
        );
    } elseif ($type == "story_box") { // ^ This is the story Box ------------->

        $sql = "SELECT * FROM `story_box` WHERE id = ?;";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $question_id);
        if (!$stmt->execute()) {
            return false;
        }


        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $story_box_userdata_name = $row["story_box_userdata_name"];
                return array(
                    "section_name" => $section_name,
                    "userdata_name" => $story_box_userdata_name
                );
            }
        } else {
            return false;
        }
    } elseif ($type == "comment") { // ^ This is the comment ------------->
        $sql = "SELECT * FROM `comment` WHERE id = ?;";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $question_id);
        if (!$stmt->execute()) {
            return false;
        }


        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $comment_userdata_name = $row["comment_userdata_name"];
                return array(
                    "section_name" => $section_name,
                    "userdata_name" => $comment_userdata_name
                );
            }
        } else {
            return false;
        }
    }
}

function get_click_list_items_userdata($mysqli, $id, $type)
{

    $sql = "SELECT * FROM `click_list_items` WHERE id = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $id);


    if (!$stmt->execute()) {
        return false;
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $result = $row[$type];
            return $result;
        }
    } else {
        return false;
    }
}


function get_user_comment($mysqli, $user_id, $userdata_name, $stage)
{

    if (!$user_id) {
        return array(
            "user_comment_id" => false,
            "user_comment" => ""
        );
    }

    $sql = "SELECT * FROM `user_input_comments` WHERE `user_id` = ? AND `userdata_name_reference` = ? AND `stage` = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return array(
            "user_comment_id" => false,
            "user_comment" => ""
        );
    }

    $stmt->bind_param("isi", $user_id, $userdata_name, $stage);


    if (!$stmt->execute()) {
        return array(
            "user_comment_id" => false,
            "user_comment" => ""
        );
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $user_input = $row["user_comment"];
            return array(
                "user_comment_id" => $id,
                "user_comment" => $user_input
            );
        }
    } else {
        return array(
            "user_comment_id" => false,
            "user_comment" => ""
        );
    }
}

function get_user_input($mysqli, $user_id, $userdata_name, $type)
{

    if ($type == "click_list") {
        $userdata_name = intval($userdata_name) + 1;
    }

    $sql = "SELECT * FROM `user_input` WHERE `user_id` = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $user_id);

    if (!$stmt->execute()) {
        return false;
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user_input = $row[$userdata_name];
            return $user_input;
        }
    } else {
        return false;
    }
}
