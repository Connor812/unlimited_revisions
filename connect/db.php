<?php
$servername = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$dbname = "unlimited_revisions";
// Create connection
$mysqli = new mysqli($servername, $USERNAME, $PASSWORD, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} else {
    // echo "Connected successfully!";
}
