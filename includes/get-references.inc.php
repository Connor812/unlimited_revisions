<?php

require_once ("../connect/db.php");

$sql = "SELECT section_name, 
CASE 
    WHEN item_type = 'checkbox' THEN userdata_name + 1 
    ELSE userdata_name 
END AS userdata_name,
page_num
FROM (
SELECT jp.section_name AS section_name, 
    sb.story_box_userdata_name AS userdata_name,
    jp.page_num AS page_num,
    NULL AS item_type 
FROM journal_page jp 
INNER JOIN story_box sb ON jp.id = sb.section_id 

UNION ALL 

SELECT cli.item_title AS section_name, 
    cli.item_userdata_name AS userdata_name,
    jp.page_num AS page_num, -- Fetching page_num from journal_page through click_list
    cli.item_type AS item_type 
FROM click_list cl 
INNER JOIN click_list_items cli ON cl.id = cli.click_list_id 
LEFT JOIN journal_page jp ON cl.section_id = jp.id

UNION ALL 

SELECT jp.section_name AS section_name, 
    c.comment_userdata_name AS userdata_name,
    jp.page_num AS page_num,
    NULL AS item_type 
FROM journal_page jp 
INNER JOIN comment c ON jp.id = c.section_id
) AS combined_results;";

$result = mysqli_query($mysqli, $sql);

if (!$result) {
    // If there's an error in the query execution
    $error = mysqli_error($mysqli);
    $response = array("error" => "Database error: " . $error);
    echo json_encode($response);
} else {
    if (mysqli_num_rows($result) > 0) {
        // If there are rows returned from the query
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        // If there are no rows returned from the query
        $response = array("error" => "No data found.");
        echo json_encode($response);
    }
}
