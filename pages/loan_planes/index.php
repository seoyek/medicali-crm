<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

// دریافت لیست طرح‌ها
$query = "SELECT lp.*, c.name as company_name 
          FROM loan_plans lp
          LEFT JOIN companies c ON lp.company_id = c.id
          ORDER BY lp.created_at DESC";
$result = mysqli_query($connection, $query);

?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت طرح‌های وام</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>مدیریت طرح‌های وام</h2>
            <div class="actions">
                <a href="add.php" class="button success">افزودن طرح جدید</a>
                <a href="/CRM/pages/dashboard.php" class="button">بازگشت به داشبورد</a>
            </div>
        </div>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>عنوان طرح</th>
                        <th>شرکت</th>
                        <th>مبلغ وام</th>
                        <th>مبلغ نقدشوندگی</th>
                        <th>درصد کارمزد</th>
                        <th>درصد سهم شرکت</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($plan = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $plan['title']; ?></td>
                        <td><?php echo $plan['company_name']; ?></td>
                        <td><?php echo number_format($plan['loan_amount']); ?> ریال</td>
                        <td><?php echo number_format($plan['cash_amount']); ?> ریال</td>
                        <td><?php echo $plan['commission_rate']; ?>%</td>
                        <td><?php echo $plan['company_share_rate']; ?>%</td>
                        <td>
                            <span class="status-badge status-<?php echo $plan['is_active'] ? 'active' : 'inactive'; ?>">
                                <?php echo $plan['is_active'] ? 'فعال' : 'غیرفعال'; ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="edit.php?id=<?php echo $plan['id']; ?>" class="button small">ویرایش</a>
                            <a href="delete.php?id=<?php echo $plan['id']; ?>" 
                               class="button small danger" 
                               onclick="return confirm('آیا از حذف این طرح اطمینان دارید؟')">حذف</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
    .status-badge.status-active {
        background: #28a745;
        color: white;
    }
    .status-badge.status-inactive {
        background: #dc3545;
        color: white;
    }
    </style>
</body>
</html>