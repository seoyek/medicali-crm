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

// بررسی استفاده از طرح در معاملات
$check_query = "SELECT COUNT(*) as used_count FROM transactions WHERE loan_plan_id = $id";
$check_result = mysqli_query($connection, $check_query);
$check_data = mysqli_fetch_assoc($check_result);

if ($check_data['used_count'] > 0) {
    $_SESSION['error'] = "این طرح در معاملات استفاده شده و قابل حذف نیست";
    redirect("index.php");
}

$query = "DELETE FROM loan_plans WHERE id = $id";

if (mysqli_query($connection, $query)) {
    $_SESSION['success'] = "طرح با موفقیت حذف شد";
} else {
    $_SESSION['error'] = "خطا در حذف طرح";
}

redirect("index.php");
?>