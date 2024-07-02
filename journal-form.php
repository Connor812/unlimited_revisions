<?php

require_once 'connect/db.php';
require_once 'config-url.php';
require_once 'includes/display-sections.inc.php';

$user_id = $_SESSION['user_id'];

?>

<nav class="user-pages-nav">
    <?php

    $sql = "SELECT permission.user_id, permission.page_num, page_name.page_name
    FROM permission
    LEFT JOIN page_name ON permission.page_num = page_name.page_num
    WHERE permission.user_id = ?;";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // ! Handle the case where the prepared statement could not be created
        echo "No Pages";
    }

    $stmt->bind_param("i", $user_id);

    // ? checks to see if the execute fails
    if (!$stmt->execute()) {
        echo "No Pages";
        $stmt->close();
    }

    // * Gets the Result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $users_page_num = 0;
        while ($row = $result->fetch_assoc()) {
            // * Your Code here
            $users_page_num++;
            $page_num = $row['page_num'];
            $page_name = $row['page_name'];
            $active = "";
            if ($_GET["page_num"] == $page_num) {
                $active = "active";
            }
            ?>
            <div>
                <a href="?page_num=<?php echo "$page_num#form"; ?>">
                    <h6 class="<?php echo $active ?> text-center">
                        <?php echo ($page_name) ? $page_name : "Page $users_page_num"; ?>
                    </h6>
                </a>
            </div>

            <?php
        }
    } else {
        // ! No data found
        echo "No Pages";
    }
    ?>
</nav>

<?php

// If the user has selected a page from the bottom, then it will display here

if (isset ($_GET['page_num'])) {
    $page_num = $_GET['page_num'];
    $user_id = $_SESSION['user_id'];

    // Checks to see if the user has permissions so they cant just select any page from the url
    $sql = "SELECT * FROM permission WHERE user_id = ? AND page_num = ?;";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ii', $user_id, $page_num);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo "Prepare failed: " . $mysqli->error;
        exit;
    }

    $page_num = $result->fetch_assoc();

    // If have permissions to more then one page_num, display the first one in the list
    if ($page_num > 0) {

        // Calls function in journal-display that displays all the pieces inside a page
        display_sections($page_num['page_num'], $mysqli, false, $user_id);

    } else {
        // ERROR
        echo 'You have no permissions';
    }
} else {
    // This is going to get the first page the user has permissions for
    $sql = "SELECT * FROM permission WHERE user_id = ? ORDER BY page_num ASC LIMIT 1;";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo "Prepare failed: " . $mysqli->error;
        exit;
    }

    $result = $result->fetch_assoc();

    // If have permissions to more then one result, display the first one in the list
    if ($result > 0) {

        $page_num = $result["page_num"]

            ?>
        <script>window.location.href = "<?php echo BASE_URL . "journal.php?page_num=$page_num" ?>" </script>
        <?php

    } else {
        // ERROR
        echo 'You have no permissions';
    }
}
?>