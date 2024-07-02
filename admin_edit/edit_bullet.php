<?php

require_once("../config-url.php");
require_once("../connect/db.php");

if (!isset($_GET["page_num"]) || !isset($_GET["order_num"])) {
    header("Location: " . BASE_URL . "/admin_pages.php?error=missing_section_params");
    exit;
}

$page_num = $_GET["page_num"];
$order_num = $_GET["order_num"];

$sql = "SELECT journal_page.id, bullet.id AS bullet_id, journal_page.*, bullet.*
FROM journal_page
LEFT JOIN bullet ON journal_page.id = bullet.section_id
WHERE journal_page.page_num = ? AND journal_page.order_num = ?;";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    // ! Handle the case where the prepared statement could not be created
    header("Location: " . BASE_URL . "admin_pages.php?error=prepare_fail");
    exit;
}

$stmt->bind_param("ii", $page_num, $order_num);

// ? checks to see if the execute fails
if (!$stmt->execute()) {
    header("Location: " . BASE_URL . "admin_pages.php?error=failed_getting_information");
    $stmt->close();
    exit;
}

// * Gets the Result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $bullet_id = $row["bullet_id"];

    }
} else {
    // ! No data found
    echo "No Data Found";
}

require_once("../admin-header.php");
require_once("../includes/display-sections.inc.php");
require_once("../includes/admin_errors.inc.php");

?>
<main class="edit-main">
    <div>

    </div>
    <form class="edit-form" method="post"
        action="../includes/edit_update/update_bullet.php?page_num=<?php echo $page_num ?>&order_num=<?php echo $order_num ?>">
        <div class="edit-form-content">
            <h2>Edit Text</h2>
            <?php

            $sql = "SELECT * FROM `bullet_point` WHERE bullet_id = ?;";
            $stmt = $mysqli->prepare($sql);

            if (!$stmt) {
                // ! Handle the case where the prepared statement could not be created
                header("Location: admin_pages.php?error=failed_prepare_statement");
                exit;
            }

            $stmt->bind_param("i", $bullet_id);

            // ? checks to see if the execute fails
            if (!$stmt->execute()) {
                header("Location: " . BASE_URL . "admin_pages.php?error=failed_getting_information");
                $stmt->close();
                exit;
            }

            // * Gets the Result
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $bullet_content = $row["bullet_content"];
                    $bullet_point_id = $row["id"];
                    ?>
                    <input class="form-control" name="bullet_point[<?php echo $bullet_point_id ?>]" type="text"
                        value="<?php echo $bullet_content ?>">
                    <?php
                }
            } else {
                // ! No data found
                header("Location: " . BASE_URL . "admin_pages.php?error=no_bullet_info");
                exit;
            }

            ?>
            <button type="submit" class="btn btn-primary update">Update</button>
        </div>
    </form>

</main>

<script src="../js/edit_admin.js"></script>

<?php
require_once("../admin_footer.php");
?>