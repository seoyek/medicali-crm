<?php
function clean($string) {
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
}

function redirect($location) {
    header("Location: " . $location);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>