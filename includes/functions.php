<<<<<<< HEAD
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
=======
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
>>>>>>> 5a00b2c6a58ad3b9223a7e4abdfac592d975c7a6
?>