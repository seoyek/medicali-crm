<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

// تنظیم ماه مورد نظر
$current_month = date('Y-m');
if(isset($_GET['month'])) {
    $current_month = $_GET['month'];
}

// آمار ماهانه
$monthly_query = "SELECT 
    COUNT(*) as transactions_count,
    SUM(amount) as total_amount,
    SUM(company_share) as company_share,
    SUM(monthly_fee) as monthly_fee,
    status,
    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_count,
    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_count
    FROM transactions 
    WHERE DATE_FORMAT(created_at, '%Y-%m') = '$current_month'";
$monthly_result = mysqli_query($connection, $monthly_query);
$monthly_stats = mysqli_fetch_assoc($monthly_result);

// آمار روزانه در ماه
$daily_query = "SELECT 
    DATE(created_at) as date,
    COUNT(*) as count,
    SUM(amount) as total_amount
    FROM transactions 
    WHERE DATE_FORMAT(created_at, '%Y-%m') = '$current_month'
    GROUP BY DATE(created_at)
    ORDER BY date DESC";
$daily_result = mysqli_query($connection, $daily_query);

// پرداخت‌های ماهانه
$payments_query = "SELECT 
    type,
    COUNT(*) as count,
    SUM(amount) as total_amount
    FROM payments 
    WHERE DATE_FORMAT(payment_date, '%Y-%m') = '$current_month'
    GROUP BY type";
$payments_result = mysqli_query($connection, $payments_query);
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش ماهانه</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>گزارش ماهانه - <?php echo jdate('F Y', strtotime($current_month.'-01')); ?></h2>
            <div class="actions">
                <a href="index.php" class="button">بازگشت</a>
                <button onclick="window.print()" class="button">چاپ گزارش</button>
            </div>
        </div>

        <!-- انتخاب ماه -->
        <div class="month-selector">
            <form method="get">
                <input type="month" name="month" value="<?php echo $current_month; ?>" onchange="this.form.submit()">
            </form>
        </div>

        <!-- آمار ماهانه -->
        <div class="stats-grid">
            <div class="stat-box">
                <h3>تعداد کل معاملات</h3>
                <div class="stat-value"><?php echo number_format($monthly_stats['transactions_count']); ?></div>
                <div class="stat-details">
                    <span class="pending">در انتظار: <?php echo $monthly_stats['pending_count']; ?></span>
                    <span class="active">فعال: <?php echo $monthly_stats['active_count']; ?></span>
                    <span class="completed">تکمیل شده: <?php echo $monthly_stats['completed_count']; ?></span>
                </div>
            </div>
            <div class="stat-box">
                <h3>مجموع مبالغ</h3>
                <div class="stat-value"><?php echo number_format($monthly_stats['total_amount']); ?> ریال</div>
            </div>
            <div class="stat-box">
                <h3>سهم شرکت</h3>
                <div class="stat-value"><?php echo number_format($monthly_stats['company_share']); ?> ریال</div>
            </div>
            <div class="stat-box">
                <h3>مجموع کارمزد</h3>
                <div class="stat-value"><?php echo number_format($monthly_stats['monthly_fee']); ?> ریال</div>
            </div>
        </div>

        <!-- آمار روزانه -->
        <div class="report-section">
            <h3>آمار روزانه در ماه</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>تاریخ</th>
                        <th>تعداد معاملات</th>
                        <th>مجموع مبالغ</th>
                        <th>نمودار</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $max_amount = 0;
                    $daily_stats = [];
                    while($day = mysqli_fetch_assoc($daily_result)) {
                        $daily_stats[] = $day;
                        if($day['total_amount'] > $max_amount) {
                            $max_amount = $day['total_amount'];
                        }
                    }
                    
                    foreach($daily_stats as $day): 
                        $bar_width = ($day['total_amount'] / $max_amount) * 100;
                    ?>
                    <tr>
                        <td><?php echo jdate('l j F', strtotime($day['date'])); ?></td>
                        <td><?php echo number_format($day['count']); ?></td>
                        <td><?php echo number_format($day['total_amount']); ?> ریال</td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo $bar_width; ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- آمار پرداخت‌ها -->
        <div class="report-section">
            <h3>آمار پرداخت‌ها</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>نوع پرداخت</th>
                        <th>تعداد</th>
                        <th>مجموع مبالغ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($payment = mysqli_fetch_assoc($payments_result)): ?>
                    <tr>
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
                        <td><?php echo number_format($payment['count']); ?></td>
                        <td><?php echo number_format($payment['total_amount']); ?> ریال</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .month-selector {
            margin: 2rem 0;
            text-align: center;
        }

        .month-selector input {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .stat-details {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .stat-details span {
            margin: 0 0.5rem;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
        }

        .stat-details .pending {
            background: #ffc107;
            color: #000;
        }

        .stat-details .active {
            background: #28a745;
            color: #fff;
        }

        .stat-details .completed {
            background: #6c757d;
            color: #fff;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: #f5f5f5;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            background: #007bff;
            transition: width 0.3s ease;
        }

        @media print {
            .month-selector {
                display: none;
            }
        }
    </style>
</body>
</html>