<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

require_once ("header.php");
require_once ("connect/db.php");

// ? Update statement
$sql = "UPDATE `messages` SET `user_read_status` = 'reaD' WHERE user_id = ?;";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    echo "Prepared Error";
    $stmt->close();
}

$stmt->bind_param("i", $user_id);

// ? This checks the execute statement
if ($stmt->execute()) {
    // ? Check the number of affected rows
    if ($stmt->affected_rows > 0) {
        // * Successful update
        $stmt->close();
    } else {
        // ! No rows were updated
        $stmt->close();
    }
} else {
    // ! Failed update
    echo "Failed Update";
    $stmt->close();
}

?>
<main style="padding: 5%; height: 100%;"
      class="d-flex justify-content-center align-items-center">
    <div id="chat-box"
         class="chat-box border border-2">
        <div class="message-wrapper">

            <?php

            $sql = "SELECT * FROM `messages` WHERE `user_id` = ?;";
            $stmt = $mysqli->prepare($sql);

            if (!$stmt) {
                echo "Prepared Error";
            }

            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {

                        $message = $row["message"];
                        $from = $row["who_from"];
                        $time = $row["time_stamp"];
                        if ($from === "admin") {
                            ?>
                            <div class="message-container admin-message">
                                <div>
                                    <?php echo $message ?>
                                </div>
                            </div>
                            <div class="admin-time-stamp">
                                <?php echo $time ?>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="message-container user-message">
                                <div>
                                    <?php echo $message ?>
                                </div>
                            </div>
                            <div class="user-time-stamp">
                                <?php echo $time ?>
                            </div>
                            <?php
                        }
                    }
                } else {
                    echo "No Messages Yet";
                }
            } else {
                echo "Error Getting Messages";
            }

            ?>

        </div>
        <div class="send-message">
            <form action="includes/add-message-user.php?"
                  method="post">
                <div class="input-group mb-3">
                    <input type="hidden"
                           name="user_id"
                           value="<?php echo $user_id ?>">
                    <input type="hidden"
                           name="from"
                           value="user">
                    <textarea type="text"
                              name="message"
                              class="form-control message-input"
                              placeholder="Recipient's username"
                              aria-label="Recipient's username"
                              aria-describedby="button-addon2"></textarea>
                    <button class="btn btn-outline-secondary"
                            type="submit"
                            id="button-addon2"
                            style="width: 80px">
                        <svg width="25"
                             height="25"
                             fill="currentColor"
                             class="bi bi-send"
                             viewBox="0 0 16 16">
                            <path
                                  d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        window.onload = function () {
            var messageWrapper = document.querySelector('.message-wrapper');
            messageWrapper.scrollTop = messageWrapper.scrollHeight;
        }
    </script>
</main>

<?php
require_once ("footer.php");
?>