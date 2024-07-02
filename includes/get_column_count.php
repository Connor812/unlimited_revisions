<?php

require_once("../../config-url.php");

function get_column_count($mysqli)
{
    // ? This will get the max number of columns in the table, and use the number as the column name in the userinput
    $sql = "SELECT COUNT(*) AS column_count 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = 'user_input';";

    $result = mysqli_query($mysqli, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            return $row["column_count"];

        }
    } else {
        // ! No data found
        header("Location: " . BASE_URL . "/admin_pages.php?error=column_name");
        exit;
    }
}
