<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

// محاسبه آمار کلی
$stats_query = "SELECT 
    COUNT(*) as total_transactions,
    COALESCE(SUM(amount), 0) as total_amount,
    COALESCE(SUM(company_share), 0) as total_company_share,
    COALESCE(SUM(monthly_fee), 0) as total_monthly_fee
    FROM transactions";
$stats_result = mysqli_query($connection, $stats_query);
if (!$stats_result) {
    die("خطا در کوئری آمار: " . mysqli_error($connection));
}
$stats = mysqli_fetch_assoc($stats_result);

// گزارش وضعیت معاملات
$status_query = "SELECT 
    status,
    COUNT(*) as count,
    COALESCE(SUM(amount), 0) as total_amount
    FROM transactions 
    GROUP BY status";
$status_result = mysqli_query($connection, $status_query);
if (!$status_result) {
    die("خطا در کوئری وضعیت: " . mysqli_error($connection));
}

// گزارش عملکرد نمایندگان
$agents_query = "SELECT 
    a.id,
    u.name as agent_name,
    COUNT(t.id) as transaction_count,
    COALESCE(SUM(t.amount), 0) as total_amount
    FROM agents a
    LEFT JOIN users u ON a.user_id = u.id
    LEFT JOIN transactions t ON a.id = t.agent_id
    GROUP BY a.id, u.name
    HAVING transaction_count > 0
    ORDER BY total_amount DESC
    LIMIT 10";
$agents_result = mysqli_query($connection, $agents_query);
if (!$agents_result) {
    die("خطا در کوئری نمایندگان: " . mysqli_error($connection));
}

?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارشات سیستم</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>گزارشات سیستم</h2>
            <div class="actions">
                <a href="/CRM/pages/dashboard.php" class="button">داشبورد</a>
                <a href="daily.php" class="button">گزارش روزانه</a>
                <a href="monthly.php" class="button">گزارش ماهانه</a>
                <a href="agent_performance.php" class="button">عملکرد نمایندگان</a>
            </div>
        </div>

        <!-- آمار کلی -->
        <div class="stats-grid">
            <div class="stat-box">
                <h3>کل معاملات</h3>
                <div class="stat-value"><?php echo number_format($stats['total_transactions']); ?></div>
            </div>
            <div class="stat-box">
                <h3>مجموع مبالغ</h3>
                <div class="stat-value"><?php echo number_format($stats['total_amount']); ?> ریال</div>
            </div>
            <div class="stat-box">
                <h3>سهم شرکت</h3>
                <div class="stat-value"><?php echo number_format($stats['total_company_share']); ?> ریال</div>
            </div>
            <div class="stat-box">
                <h3>مجموع کارمزد</h3>
                <div class="stat-value"><?php echo number_format($stats['total_monthly_fee']); ?> ریال</div>
            </div>
        </div>

        <!-- گزارش وضعیت معاملات -->
        <div class="report-section">
            <h3>وضعیت معاملات</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>وضعیت</th>
                        <th>تعداد</th>
                        <th>مجموع مبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($status = mysqli_fetch_assoc($status_result)): ?>
                    <tr>
                        <td>
                            <span class="status-badge status-<?php echo $status['status']; ?>">
                                <?php 
                                $statuses = [
                                    'pending' => 'در انتظار',
                                    'active' => 'فعال',
                                    'completed' => 'تکمیل شده'
                                ];
                                echo $statuses[$status['status']];
                                ?>
                            </span>
                        </td>
                        <td><?php echo number_format($status['count']); ?></td>
                        <td><?php echo number_format($status['total_amount']); ?> ریال</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- گزارش عملکرد نمایندگان -->
        <div class="report-section">
            <h3>عملکرد برتر نمایندگان</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>نام نماینده</th>
                        <th>تعداد معاملات</th>
                        <th>مجموع مبالغ</th>
                        <th>نمودار</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $max_amount = 0;
                    $agents_data = [];
                    while($agent = mysqli_fetch_assoc($agents_result)) {
                        $agents_data[] = $agent;
                        $max_amount = max($max_amount, $agent['total_amount']);
                    }
                    foreach($agents_data as $agent): 
                        $percentage = ($max_amount > 0) ? ($agent['total_amount'] / $max_amount * 100) : 0;
                    ?>
                    <tr>
                        <td><?php echo $agent['agent_name']; ?></td>
                        <td><?php echo number_format($agent['transaction_count']); ?></td>
                        <td><?php echo number_format($agent['total_amount']); ?> ریال</td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .stat-box {
            background: #fff;
            padding: 1.5rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
            margin-top: 1rem;
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

        .report-section {
            background: #fff;
            padding: 1.5rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin: 2rem 0;
        }

        .report-section h3 {
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #eee;
        }
    </style>
</body>
</html>