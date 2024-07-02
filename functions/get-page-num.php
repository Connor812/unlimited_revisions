<?php
// Function to get the maximum page number
function getMaxPageNum($mysqli)
{
    $sql = "SELECT MAX(page_num) AS max_page_num FROM journal_page;";
    $result = mysqli_query($mysqli, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['max_page_num'];
    } else {
        return false;
    }
}

// Function to get the page name based on page number
function getPageName($mysqli, $pageNum)
{
    $sql = "SELECT * FROM page_name WHERE page_num = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $pageNum);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && ($row = $result->fetch_assoc())) {
            return $row['page_name'];
        } else {
            return false;
        }

    } else {
        return false;
    }
}