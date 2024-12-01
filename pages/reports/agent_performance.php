<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

// تنظیم دوره زمانی
$period = isset($_GET['period']) ? $_GET['period'] : 'month';
$date_condition = "";

switch($period) {
    case 'week':
        $date_condition = "WHERE t.created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 WEEK)";
        break;
    case 'month':
        $date_condition = "WHERE t.created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";
        break;
    case 'year':
        $date_condition = "WHERE t.created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)";
        break;
    default:
        $date_condition = "";
}

// آمار عملکرد نمایندگان
$performance_query = "SELECT 
    a.id,
    a.name as agent_name,
    COUNT(t.id) as transaction_count,
    SUM(t.amount) as total_amount,
    SUM(t.company_share) as total_company_share,
    AVG(t.amount) as avg_amount,
    COUNT(CASE WHEN t.status = 'completed' THEN 1 END) as completed_count
    FROM agents a
    LEFT JOIN transactions t ON a.id = t.agent_id
    $date_condition
    GROUP BY a.id
    ORDER BY total_amount DESC";
$performance_result = mysqli_query($connection, $performance_query);

// جزئیات معاملات نماینده انتخاب شده
$agent_details = null;
if(isset($_GET['agent_id'])) {
    $agent_id = (int)$_GET['agent_id'];
    $details_query = "SELECT t.*, c.name as customer_name
        FROM transactions t
        LEFT JOIN customers c ON t.customer_id = c.id
        WHERE t.agent_id = $agent_id
        ORDER BY t.created_at DESC
        LIMIT 10";
    $agent_details = mysqli_query($connection, $details_query);
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش عملکرد نمایندگان</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>گزارش عملکرد نمایندگان</h2>
            <div class="actions">
                <a href="index.php" class="button">بازگشت</a>
                <button onclick="window.print()" class="button">چاپ گزارش</button>
            </div>
        </div>

        <!-- انتخاب دوره زمانی -->
        <div class="period-selector">
            <form method="get">
                <select name="period" onchange="this.form.submit()">
                    <option value="week" <?php if($period == 'week') echo 'selected'; ?>>هفته گذشته</option>
                    <option value="month" <?php if($period == 'month') echo 'selected'; ?>>ماه گذشته</option>
                    <option value="year" <?php if($period == 'year') echo 'selected'; ?>>سال گذشته</option>
                    <option value="all" <?php if($period == 'all') echo 'selected'; ?>>کل دوره</option>
                </select>
            </form>
        </div>

        <!-- جدول عملکرد -->
        <div class="report-section">
            <h3>مقایسه عملکرد نمایندگان</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>رتبه</th>
                        <th>نام نماینده</th>
                        <th>تعداد معاملات</th>
                        <th>مجموع مبالغ</th>
                        <th>میانگین مبلغ</th>
                        <th>نرخ تکمیل</th>
                        <th>نمودار عملکرد</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    $max_amount = 0;
                    $agents_data = [];
                    
                    while($agent = mysqli_fetch_assoc($performance_result)) {
                        $agents_data[] = $agent;
                        if($agent['total_amount'] > $max_amount) {
                            $max_amount = $agent['total_amount'];
                        }
                    }
                    
                    foreach($agents_data as $agent): 
                        $completion_rate = $agent['transaction_count'] > 0 ? 
                            ($agent['completed_count'] / $agent['transaction_count']) * 100 : 0;
                        $bar_width = $max_amount > 0 ? ($agent['total_amount'] / $max_amount) * 100 : 0;
                    ?>
                    <tr>
                        <td><?php echo $rank++; ?></td>
                        <td><?php echo $agent['agent_name']; ?></td>
                        <td><?php echo number_format($agent['transaction_count']); ?></td>
                        <td><?php echo number_format($agent['total_amount']); ?> ریال</td>
                        <td><?php echo number_format($agent['avg_amount']); ?> ریال</td>
                        <td><?php echo number_format($completion_rate, 1); ?>%</td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo $bar_width; ?>%"></div>
                            </div>
                        </td>
                        <td>
                            <a href="?period=<?php echo $period; ?>&agent_id=<?php echo $agent['id']; ?>" 
                               class="button small">جزئیات</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if($agent_details): ?>
        <!-- جزئیات معاملات نماینده -->
        <div class="report-section">
            <h3>آخرین معاملات نماینده</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>تاریخ</th>
                        <th>مشتری</th>
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($transaction = mysqli_fetch_assoc($agent_details)): ?>
                    <tr>
                        <td><?php echo jdate('Y/m/d', strtotime($transaction['created_at'])); ?></td>
                        <td><?php echo $transaction['customer_name']; ?></td>
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
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <style>
        .period-selector {
            margin: 2rem 0;
            text-align: center;
        }

        .period-selector select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 3px;
            width: 200px;
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
            .period-selector, .actions {
                display: none;
            }
        }
    </style>
</body>
</html>