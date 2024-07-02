<?php

function get_user_input_list_data($mysqli, $userdata_name, $user_id)
{

    if ($user_id === false) {
        // Returns if on the admin, page, and there is no user_id
        return "";
    }

    $sql = "SELECT `$userdata_name` AS 'user_data' FROM `user_input` WHERE user_id = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // ! Handle the case where the prepared statement could not be created

        return "Prepare Error";
    }

    $stmt->bind_param("i", $user_id);

    // ? checks to see if the execute fails
    if (!$stmt->execute()) {
        $stmt->close();
        return "Execute Error";
    }

    // * Gets the Result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // * Your Code here
            if ($row["user_data"]) {
                return $row["user_data"];
            } else {
                return "No Answer Yet";
            }
        }
    } else {
        // ! No data found
        return "No Data Found";
    }

}

function get_user_input_list_notes($mysqli, $userdata_name, $user_id)
{

    if ($user_id === false) {
        // Returns if on the admin, page, and there is no user_id
        return "";
    }

    $sql = "SELECT * FROM `user_input_notes` WHERE user_id = ? AND userdata_name = ?;";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // ! Handle the case where the prepared statement could not be created

        return "Prepare Error";
    }

    $stmt->bind_param("is", $user_id, $userdata_name);

    // ? checks to see if the execute fails
    if (!$stmt->execute()) {
        $stmt->close();
        return "Execute Error";
    }

    // * Gets the Result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $notes = "";
        while ($row = $result->fetch_assoc()) {
            // * Your Code here
            if ($row["note"]) {
                $note = $row["note"];
                $notes .= "<li>$note</li>";
            } else {
                return "No Notes Yet";
            }
        }
        return $notes;
    } else {
        // ! No data found
        return "";
    }

}
