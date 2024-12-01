<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

$query = "SELECT bp.*, c.name as company_name
          FROM business_partners bp
          LEFT JOIN companies c ON bp.company_id = c.id
          ORDER BY bp.created_at DESC";
$result = mysqli_query($connection, $query);

?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت طرف حساب‌ها</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>مدیریت طرف حساب‌ها</h2>
            <div class="actions">
                <a href="add.php" class="button success">افزودن طرف حساب جدید</a>
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
                        <th>نام</th>
                        <th>شرکت</th>
                        <th>تلفن</th>
                        <th>موبایل</th>
                        <th>کد ملی</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($partner = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $partner['name']; ?></td>
                        <td><?php echo $partner['company_name']; ?></td>
                        <td><?php echo $partner['phone']; ?></td>
                        <td><?php echo $partner['mobile']; ?></td>
                        <td><?php echo $partner['national_code']; ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $partner['is_active'] ? 'active' : 'inactive'; ?>">
                                <?php echo $partner['is_active'] ? 'فعال' : 'غیرفعال'; ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="view.php?id=<?php echo $partner['id']; ?>" class="button small">مشاهده</a>
                            <a href="edit.php?id=<?php echo $partner['id']; ?>" class="button small warning">ویرایش</a>
                            <a href="delete.php?id=<?php echo $partner['id']; ?>" 
                               class="button small danger" 
                               onclick="return confirm('آیا از حذف این طرف حساب اطمینان دارید؟')">حذف</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>