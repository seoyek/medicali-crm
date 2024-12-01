<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'medicali_crm');
define('DB_PASS', 'HkNf;aEg,ASd');
define('DB_NAME', 'medicali_crm');

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if(!$connection) {
    die("خطا در اتصال به پایگاه داده: " . mysqli_connect_error());
}
mysqli_set_charset($connection, "utf8mb4");
?>