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

// بررسی وجود معامله
$check_query = "SELECT status FROM transactions WHERE id = $id";
$check_result = mysqli_query($connection, $check_query);
$transaction = mysqli_fetch_assoc($check_result);

if (!$transaction) {
    $_SESSION['error'] = "معامله مورد نظر یافت نشد";
    redirect("index.php");
}

// فقط معاملات در وضعیت 'در انتظار' قابل حذف هستند
if ($transaction['status'] != 'pending') {
    $_SESSION['error'] = "فقط معاملات در وضعیت 'در انتظار' قابل حذف هستند";
    redirect("index.php");
}

// بررسی وجود پرداخت‌های مرتبط
$payments_query = "SELECT COUNT(*) as payment_count FROM payments WHERE transaction_id = $id";
$payments_result = mysqli_query($connection, $payments_query);
$payments_count = mysqli_fetch_assoc($payments_result)['payment_count'];

if ($payments_count > 0) {
    $_SESSION['error'] = "این معامله دارای پرداخت است و قابل حذف نیست";
    redirect("index.php");
}

// حذف معامله
$delete_query = "DELETE FROM transactions WHERE id = $id";

if (mysqli_query($connection, $delete_query)) {
    // ثبت در جدول لاگ
    $user_id = $_SESSION['user_id'];
    $log_query = "INSERT INTO activity_logs 
                  (user_id, action, table_name, record_id, details) 
                  VALUES 
                  ($user_id, 'delete', 'transactions', $id, 'حذف معامله')";
    mysqli_query($connection, $log_query);

    $_SESSION['success'] = "معامله با موفقیت حذف شد";
} else {
    $_SESSION['error'] = "خطا در حذف معامله";
}

redirect("index.php");
?>