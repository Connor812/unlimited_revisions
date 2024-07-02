<?php

function update_journal_page($section_id, $page_num, $mysqli)
{

    // Updates the order_num to fit the new section 
    $sql = "UPDATE journal_page SET order_num = order_num + 1 WHERE order_num > ? AND page_num = ?;";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $section_id, $page_num);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // echo "Update was successful. Affected rows: " . $stmt->affected_rows;
            } else {
                // echo "No rows were updated.";
            }
        } else {
            echo "Execution failed: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare statement failed: " . $mysqli->error;
    }
}