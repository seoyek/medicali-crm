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
    $query = "SELECT a.*, u.username, u.name FROM agents a 
              LEFT JOIN users u ON a.user_id = u.id
              WHERE u.name LIKE '%$search%' 
              OR u.username LIKE '%$search%'
              ORDER BY a.id DESC";
} else {
    $query = "SELECT a.*, u.username, u.name FROM agents a 
              LEFT JOIN users u ON a.user_id = u.id 
              ORDER BY a.id DESC";
}

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت نمایندگان</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>مدیریت نمایندگان</h2>
            <div class="actions">
                <a href="add.php" class="button success">افزودن نماینده جدید</a>
                <a href="/CRM/pages/dashboard.php" class="button">بازگشت به داشبورد</a>
            </div>
        </div>

        <div class="search-box">
            <form method="get">
                <input type="text" name="search" placeholder="جستجو..." value="<?php echo $search; ?>">
                <button type="submit">جستجو</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>کد</th>
                        <th>نام و نام خانوادگی</th>
                        <th>نام کاربری</th>
                        <th>درصد کارمزد</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($agent = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $agent['id']; ?></td>
                            <td><?php echo $agent['name']; ?></td>
                            <td><?php echo $agent['username']; ?></td>
                            <td><?php echo $agent['commission_rate']; ?>%</td>
                            <td class="actions">
                                <a href="edit.php?id=<?php echo $agent['id']; ?>" class="button small">ویرایش</a>
                                <a href="delete.php?id=<?php echo $agent['id']; ?>" 
                                   class="button small danger" 
                                   onclick="return confirm('آیا از حذف این نماینده اطمینان دارید؟')">حذف</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>