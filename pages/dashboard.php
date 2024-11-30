<?php
require_once "../includes/config.php";
require_once "../includes/functions.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

// آمار کلی
$stats = [
    'customers' => mysqli_num_rows(mysqli_query($connection, "SELECT * FROM customers")),
    'agents' => mysqli_num_rows(mysqli_query($connection, "SELECT * FROM agents")),
    'transactions' => mysqli_num_rows(mysqli_query($connection, "SELECT * FROM transactions")),
    'total_amount' => 0
];

$amount_query = mysqli_query($connection, "SELECT SUM(amount) as total FROM transactions");
if ($row = mysqli_fetch_assoc($amount_query)) {
    $stats['total_amount'] = $row['total'];
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>داشبورد مدیریت</title>
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="dashboard">
            <h2>داشبورد مدیریت</h2>
            
            <div class="stats-grid">
                <div class="stat-box">
                    <h3>تعداد مشتریان</h3>
                    <p><?php echo $stats['customers']; ?></p>
                </div>
                <div class="stat-box">
                    <h3>تعداد نمایندگان</h3>
                    <p><?php echo $stats['agents']; ?></p>
                </div>
                <div class="stat-box">
                    <h3>تعداد معاملات</h3>
                    <p><?php echo $stats['transactions']; ?></p>
                </div>
                <div class="stat-box">
                    <h3>مجموع مبالغ</h3>
                    <p><?php echo number_format($stats['total_amount']); ?> ریال</p>
                </div>
            </div>

            <div class="quick-actions">
                <h3>دسترسی سریع</h3>
                <a href="customers/add.php" class="button">ثبت مشتری جدید</a>
                <a href="transactions/add.php" class="button">ثبت معامله جدید</a>
                <a href="reports/index.php" class="button">گزارشات</a>
            </div>
        </div>
    </div>
</body>
</html>