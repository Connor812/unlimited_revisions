<?php
require_once 'config-url.php';

session_start();
session_destroy();
header("Location:" . BASE_URL . "/journal.php?logout=logged_out");
?>