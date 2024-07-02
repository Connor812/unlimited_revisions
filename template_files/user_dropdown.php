<div class="col-6 d-flex justify-content-end">
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown"
            aria-expanded="false">
            Dropdown button
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php

            $sql = "SELECT username FROM users";
            $result = mysqli_query($mysqli, $sql);

            // Check for query errors
            if (!$result) {
                die("Query failed: " . mysqli_error($mysqli));
            }

            while ($row = mysqli_fetch_assoc($result)) {
                $username = $row['username'];
                echo '<li><a class="dropdown-item" href="#">' . $username . '</a></li>';
            }
            ?>
        </ul>
    </div>
</div>