<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

// جستجو
$search = '';
if(isset($_GET['search'])) {
    $search = clean($_GET['search']);
    $query = "SELECT c.*, u.name as agent_name 
              FROM customers c
              LEFT JOIN agents a ON c.agent_id = a.id
              LEFT JOIN users u ON a.user_id = u.id
              WHERE c.name LIKE '%$search%' 
              OR c.phone LIKE '%$search%'
              OR c.national_code LIKE '%$search%'
              ORDER BY c.created_at DESC";
} else {
    $query = "SELECT c.*, u.name as agent_name 
              FROM customers c
              LEFT JOIN agents a ON c.agent_id = a.id
              LEFT JOIN users u ON a.user_id = u.id
              ORDER BY c.created_at DESC";
}

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت مشتریان</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>مدیریت مشتریان</h2>
            <div class="actions">
                <a href="add.php" class="button success">افزودن مشتری جدید</a>
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

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="search-box">
            <form method="get">
                <input type="text" name="search" placeholder="جستجو بر اساس نام، تلفن یا کد ملی" value="<?php echo $search; ?>">
                <button type="submit">جستجو</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>کد</th>
                        <th>نام و نام خانوادگی</th>
                        <th>تلفن</th>
                        <th>کد ملی</th>
                        <th>نماینده</th>
                        <th>تاریخ ثبت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($customer = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $customer['id']; ?></td>
                            <td><?php echo $customer['name']; ?></td>
                            <td><?php echo $customer['phone']; ?></td>
                            <td><?php echo $customer['national_code']; ?></td>
                            <td><?php echo $customer['agent_name']; ?></td>
                            <td><?php echo jdate('Y/m/d H:i', strtotime($customer['created_at'])); ?></td>
                            <td class="actions">
                                <a href="edit.php?id=<?php echo $customer['id']; ?>" class="button small">ویرایش</a>
                                <a href="delete.php?id=<?php echo $customer['id']; ?>" 
                                   class="button small danger" 
                                   onclick="return confirm('آیا از حذف این مشتری اطمینان دارید؟')">حذف</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>