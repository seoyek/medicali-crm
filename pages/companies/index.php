<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

$query = "SELECT c.*, 
          COUNT(DISTINCT lp.id) as plans_count,
          COUNT(DISTINCT bp.id) as partners_count
          FROM companies c
          LEFT JOIN loan_plans lp ON lp.company_id = c.id
          LEFT JOIN business_partners bp ON bp.company_id = c.id
          GROUP BY c.id
          ORDER BY c.created_at DESC";
$result = mysqli_query($connection, $query);

?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت شرکت‌ها</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>مدیریت شرکت‌ها</h2>
            <div class="actions">
                <a href="add.php" class="button success">افزودن شرکت جدید</a>
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
                        <th>نام شرکت</th>
                        <th>تلفن</th>
                        <th>نام رابط</th>
                        <th>تعداد طرح‌ها</th>
                        <th>تعداد طرف حساب‌ها</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($company = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $company['name']; ?></td>
                        <td><?php echo $company['phone']; ?></td>
                        <td><?php echo $company['contact_person']; ?></td>
                        <td><?php echo number_format($company['plans_count']); ?></td>
                        <td><?php echo number_format($company['partners_count']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $company['is_active'] ? 'active' : 'inactive'; ?>">
                                <?php echo $company['is_active'] ? 'فعال' : 'غیرفعال'; ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="view.php?id=<?php echo $company['id']; ?>" class="button small">مشاهده</a>
                            <a href="edit.php?id=<?php echo $company['id']; ?>" class="button small warning">ویرایش</a>
                            <a href="delete.php?id=<?php echo $company['id']; ?>" 
                               class="button small danger" 
                               onclick="return confirm('آیا از حذف این شرکت اطمینان دارید؟')">حذف</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>