<?php

function get_user_input_table($section_id, $mysqli)
{

    $sql = "SELECT * FROM `user_input_table` WHERE section_id = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // ! Handle the case where the prepared statement could not be created
        return false;
    }

    $stmt->bind_param("i", $section_id);

    // ? checks to see if the execute fails
    if (!$stmt->execute()) {
        $stmt->close();
        return false;
    }

    // * Gets the Result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $page_nums = [];
        while ($row = $result->fetch_assoc()) {
            // * Your Code here
            $page_nums[] = $row['page_num'];
            $part = $row["part"];
        }
        return [
            "page_nums" => $page_nums,
            "part" => $part
        ];
    } else {
        // ! No data found
        return false;
    }
}

function get_section_ids($page_nums, $mysqli)
{
    $section_ids = [];
    foreach ($page_nums as $page_num) {
        $sql = "SELECT `id`, `section_type`, `section_name` FROM journal_page WHERE page_num = ? AND `section_type` = 'comment' OR `section_type` = 'story_box' OR `section_type` = 'click_list';";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            // ! Handle the case where the prepared statement could not be created
            return false;
        }

        $stmt->bind_param("i", $page_num);

        // ? checks to see if the execute fails
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }

        // * Gets the Result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // * Your Code here
                $object = [
                    "id" => $row['id'],
                    "section_type" => $row['section_type'],
                    "section_name" => $row['section_name']
                ];

                $section_ids[] = $object;
            }
        } else {
            // ! No data found
            return false;
        }

        return $section_ids;
    }
}

function generate_table_rows($row, $mysqli, $user_id)
{

    $section_id = $row['id'];
    $section_type = $row['section_type'];
    $section_name = $row['section_name'];

    if ($user_id === false) {
        return "No User";
    }

    if ($section_type === "comment") {

        $sql = "SELECT * FROM `comment` WHERE section_id = ?;";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            // ! Handle the case where the prepared statement could not be created
            return "Error";
        }

        $stmt->bind_param("i", $section_id);

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
                $userdata_name = $row['comment_userdata_name'];

                if ($user_id) {
                    $user_input = get_user_input($userdata_name, $mysqli, $user_id);
                } else {
                    echo "No User";
                }

                if ($user_input) {
?>
                    <tr>
                        <td style="width: 30%;">
                            <button class="question-mark" type="button" data-bs-toggle="collapse" data-bs-target="#question_<?php echo $userdata_name ?>" aria-expanded="false" aria-controls="question_<?php echo $userdata_name ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94" />
                                </svg>
                            </button>
                            <?php echo $user_input ?>
                            <div class="collapse" id="question_<?php echo $userdata_name ?>">
                                <div class="card card-body">
                                    <?php echo $section_name ?>
                                </div>
                            </div>
                        </td>
                        <td style="width: 70%;">
                            <center>
                                <?php
                                $comment_data = get_user_comment($user_id, $userdata_name, $mysqli, 1);
                                $comment = $comment_data["comment"];
                                $user_comment_id = $comment_data["user_comment_id"] == "no_comment" ? "" : $comment_data["user_comment_id"];
                                $is_there_data = $comment == "no_comment" ? false : true;
                                $comment = $is_there_data ? $comment : "";
                                ?>
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
                }
            }
        } else {
            // ! No data found
            return "No Data Found";
        }
    }
}

function generate_table_rows_2($row, $mysqli, $user_id)
{

    $section_id = $row['id'];
    $section_type = $row['section_type'];
    $section_name = $row['section_name'];

    if ($user_id === false) {
        return "No User";
    }

    if ($section_type === "comment") {

        $sql = "SELECT * FROM `comment` WHERE section_id = ?;";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            // ! Handle the case where the prepared statement could not be created
            return "Error";
        }

        $stmt->bind_param("i", $section_id);

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
                $userdata_name = $row['comment_userdata_name'];

                if ($user_id) {
                    $user_input = get_user_input($userdata_name, $mysqli, $user_id);
                } else {
                    echo "No User";
                }
                if ($user_input) {
                ?>
                    <tr>
                        <td style="width: 30%;">
                            <button class="question-mark" type="button" data-bs-toggle="collapse" data-bs-target="#question_<?php echo $userdata_name ?>" aria-expanded="false" aria-controls="question_<?php echo $userdata_name ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94" />
                                </svg>
                            </button>
                            <?php echo $user_input ?>
                            <div class="collapse" id="question_<?php echo $userdata_name ?>">
                                <div class="card card-body">
                                    <?php echo $section_name ?>
                                </div>
                            </div>
                        </td>
                        <td style="width: 35%;">
                            <center>
                                <div class="d-flex align-items-center">
                                    <?php
                                    $comment_data = get_user_comment($user_id, $userdata_name, $mysqli, 1);
                                    $comment = $comment_data["comment"];
                                    $user_comment_id = $comment_data["user_comment_id"] == "no_comment" ? "" : $comment_data["user_comment_id"];
                                    $is_there_data = $comment == "no_comment" ? false : true;
                                    $comment = $is_there_data ? $comment : "";
                                    ?>
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
                                <?php
                                $comment_data = get_user_comment($user_id, $userdata_name, $mysqli, 2);
                                $comment = $comment_data["comment"];
                                $user_comment_id = $comment_data["user_comment_id"] == "no_comment" ? "" : $comment_data["user_comment_id"];
                                $is_there_data = $comment == "no_comment" ? false : true;
                                $comment = $is_there_data ? $comment : "";

                                ?>
                                <div class="d-flex align-items-center">
                                    <textarea name="user_comment" class="form-control" rows="3" placeholder="Enter your comment here" id="user_comment_<?php echo $section_id ?>_2"><?php echo $comment ?></textarea>
                                    <input type="hidden" name="user_id" id="user_id_<?php echo $section_id ?>_2" value="<?php echo $user_id ?>_2">
                                    <input type="hidden" name="userdata_name" id="userdata_name_<?php echo $section_id ?>_2" value="<?php echo $userdata_name ?>">
                                    <input type="hidden" name="page_num" id="page_num_<?php echo $section_id ?>_2" value="<?php echo $userdata_name ?>_2">
                                    <input type="hidden" name="stage" id="stage_<?php echo $section_id ?>_2" value="2">
                                    <button class="btn btn-primary upload-user-comments" type="button" user_input_section_id="<?php echo $section_id ?>" user_id="<?php echo $user_id ?>" userdata_name="<?php echo $userdata_name ?>" textarea_id="user_comment_<?php echo $section_id ?>_2" is_there_data="<?php echo $is_there_data ?>" user_comment_id="<?php echo $user_comment_id ?>" stage="2">
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
            // ! No data found
            return "No Data Found";
        }
    }
}


function get_user_input($userdata_name, $mysqli, $user_id)
{

    $sql = "SELECT `$userdata_name` AS userdata FROM `user_input` WHERE user_id = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // ! Handle the case where the prepared statement could not be created
        return "Error";
    }

    $stmt->bind_param("i", $user_id);

    // ? checks to see if the execute fails
    if (!$stmt->execute()) {
        $stmt->close();
        return "Execution Error";
    }

    // * Gets the Result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // * Your Code here
            return $row["userdata"];
        }
    } else {
        // ! No data found
        return "No Data Found";
    }
}

function get_user_comment($user_id, $userdata_name, $mysqli, $stage)
{
    $sql = "SELECT * FROM `user_input_comments` WHERE user_id = ? AND userdata_name_reference = ? AND stage = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // ! Handle the case where the prepared statement could not be created
        return false;
    }

    $stmt->bind_param("isi", $user_id, $userdata_name, $stage);

    // ? checks to see if the execute fails
    if (!$stmt->execute()) {
        $stmt->close();
        return false;
    }

    // * Gets the Result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // * Your Code here
            $comment = $row["user_comment"];
            $user_comment_id = $row["id"];
            return [
                "comment" => $comment,
                "user_comment_id" => $user_comment_id
            ];
        }
    } else {
        // ! No data found
        return [
            "comment" => "no_comment",
            "user_comment_id" => "no_comment"
        ];
    }
}
