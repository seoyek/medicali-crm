<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

// تاریخ امروز
$today = date('Y-m-d');

// بررسی و اصلاح query ها
$today_query = "SELECT 
    COUNT(*) as transactions_count,
    COALESCE(SUM(amount), 0) as total_amount,
    COALESCE(SUM(company_share), 0) as company_share,
    COALESCE(SUM(monthly_fee), 0) as monthly_fee
    FROM transactions 
    WHERE DATE(created_at) = '$today'";

$today_result = mysqli_query($connection, $today_query);
if (!$today_result) {
    die("خطا در اجرای کوئری: " . mysqli_error($connection));
}
$today_stats = mysqli_fetch_assoc($today_result);

// معاملات امروز
$transactions_query = "SELECT t.*, 
    c.name as customer_name,
    u.name as agent_name
    FROM transactions t
    LEFT JOIN customers c ON t.customer_id = c.id
    LEFT JOIN agents a ON t.agent_id = a.id
    LEFT JOIN users u ON a.user_id = u.id
    WHERE DATE(t.created_at) = '$today'
    ORDER BY t.created_at DESC";

$transactions_result = mysqli_query($connection, $transactions_query);
if (!$transactions_result) {
    die("خطا در اجرای کوئری: " . mysqli_error($connection));
}

// پرداخت‌های امروز
$payments_query = "SELECT p.*, t.amount as transaction_amount
    FROM payments p
    LEFT JOIN transactions t ON p.transaction_id = t.id
    WHERE DATE(p.payment_date) = '$today'
    ORDER BY p.payment_date DESC";

$payments_result = mysqli_query($connection, $payments_query);
if (!$payments_result) {
    die("خطا در اجرای کوئری: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش روزانه</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>گزارش روزانه - <?php echo jdate('l j F Y', strtotime($today)); ?></h2>
            <div class="actions">
                <a href="index.php" class="button">بازگشت</a>
                <button onclick="window.print()" class="button">چاپ گزارش</button>
            </div>
        </div>

        <!-- آمار روزانه -->
        <div class="stats-grid">
            <div class="stat-box">
                <h3>تعداد معاملات</h3>
                <div class="stat-value"><?php echo number_format($today_stats['transactions_count']); ?></div>
            </div>
            <div class="stat-box">
                <h3>مجموع مبالغ</h3>
                <div class="stat-value"><?php echo number_format($today_stats['total_amount']); ?> ریال</div>
            </div>
            <div class="stat-box">
                <h3>سهم شرکت</h3>
                <div class="stat-value"><?php echo number_format($today_stats['company_share']); ?> ریال</div>
            </div>
            <div class="stat-box">
                <h3>کارمزد</h3>
                <div class="stat-value"><?php echo number_format($today_stats['monthly_fee']); ?> ریال</div>
            </div>
        </div>

        <!-- معاملات امروز -->
        <div class="report-section">
            <h3>معاملات ثبت شده امروز</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ساعت</th>
                        <th>مشتری</th>
                        <th>نماینده</th>
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($transactions_result) > 0): ?>
                        <?php while($transaction = mysqli_fetch_assoc($transactions_result)): ?>
                            <tr>
                                <td><?php echo date('H:i', strtotime($transaction['created_at'])); ?></td>
                                <td><?php echo $transaction['customer_name']; ?></td>
                                <td><?php echo $transaction['agent_name']; ?></td>
                                <td><?php echo number_format($transaction['amount']); ?> ریال</td>
                                <td>
                                    <span class="status-badge status-<?php echo $transaction['status']; ?>">
                                        <?php 
                                        $statuses = [
                                            'pending' => 'در انتظار',
                                            'active' => 'فعال',
                                            'completed' => 'تکمیل شده'
                                        ];
                                        echo $statuses[$transaction['status']];
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">امروز معامله‌ای ثبت نشده است</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- پرداخت‌های امروز -->
        <div class="report-section">
            <h3>پرداخت‌های دریافتی امروز</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ساعت</th>
                        <th>مبلغ پرداختی</th>
                        <th>نوع پرداخت</th>
                        <th>مبلغ معامله</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($payments_result) > 0): ?>
                        <?php while($payment = mysqli_fetch_assoc($payments_result)): ?>
                            <tr>
                                <td><?php echo date('H:i', strtotime($payment['payment_date'])); ?></td>
                                <td><?php echo number_format($payment['amount']); ?> ریال</td>
                                <td>
                                    <?php 
                                    $payment_types = [
                                        'customer' => 'پرداخت مشتری',
                                        'agent' => 'پرداخت نماینده',
                                        'company' => 'پرداخت شرکت'
                                    ];
                                    echo $payment_types[$payment['type']];
                                    ?>
                                </td>
                                <td><?php echo number_format($payment['transaction_amount']); ?> ریال</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="no-data">امروز پرداختی ثبت نشده است</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            .actions {
                display: none;
            }
            
            .container {
                width: 100%;
                margin: 0;
                padding: 20px;
            }

            .data-table {
                font-size: 12px;
            }
        }
    </style>
</body>
</html>