<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

if (!isset($_GET['id'])) {
    redirect("index.php");
}

$id = (int)$_GET['id'];

// بررسی وجود معاملات مرتبط
$check_query = "SELECT * FROM transactions WHERE customer_id = $id";
$check_result = mysqli_query($connection, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    $_SESSION['error'] = "این مشتری دارای معاملات ثبت شده است و قابل حذف نیست";
    redirect("index.php");
}

$query = "DELETE FROM customers WHERE id = $id";

if (mysqli_query($connection, $query)) {
    $_SESSION['success'] = "مشتری با موفقیت حذف شد";
} else {
    $_SESSION['error'] = "خطا در حذف مشتری";
}

redirect("index.php");
?>