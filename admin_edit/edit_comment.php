<?php
require_once("../admin-header.php");
require_once("../connect/db.php");
require_once("../config-url.php");
require_once("../includes/display-sections.inc.php");
require_once("../includes/admin_errors.inc.php");

if (!isset($_GET["page_num"]) || !isset($_GET["order_num"])) {
    echo "Error! Cannot edit Section";
    header("Location: " . BASE_URL . "/admin_pages.php?error=missing_section_params");
}

?>
<main class="edit-main">
    <div>
        <?php

        $page_num = $_GET["page_num"];
        $order_num = $_GET["order_num"];
        // This query will get all the header information
        $sql = "SELECT jp.id AS journal_page_id,
        jp.section_type,
        jp.section_name,
        jp.order_num,
        jp.page_num,
        c.id AS comment_id,
        c.comment_userdata_name,
        c.comment_placeholder
 FROM journal_page jp
 LEFT JOIN comment c ON jp.id = c.section_id
 WHERE jp.page_num = ? AND jp.order_num = ?;";

        $stmt = $mysqli->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ii", $page_num, $order_num);

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Fetch data and do something with it
        if ($row = $result->fetch_assoc()) {
            // Process the data
        
            comment_section($row, false, false);

        } else {
            // No rows found
            echo "No results found.";
        }

        ?>
    </div>
    <form class="edit-form" method="post"
        action="../includes/edit_update/update_comment.php?page_num=<?php echo $page_num ?>&order_num=<?php echo $order_num ?>">
        <div class="edit-form-content">
            <h2>Edit Text</h2>
            <label for="#section_name">Please Enter Comment Title Text:</label>
            <textarea rows="5" name="section_name" id="section_name" class="edit-input" placeholder="Please Enter Text"
                value=""><?php echo $row["section_name"] ?></textarea>
            <label for="#comment_placeholder">Please Enter Placeholder Text:</label>
            <textarea rows="5" name="comment_placeholder"
                id="comment_placeholder"><?php echo $row["comment_placeholder"] ?></textarea>
            <button type="submit" class="btn btn-primary update">Update</button>
        </div>
    </form>

</main>

<script src="../js/edit_admin.js"></script>

<?php
require_once("../admin_footer.php");
?>