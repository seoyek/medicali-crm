<<<<<<< HEAD
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
    $query = "SELECT t.*, 
              c.name as customer_name, c.national_code,
              u.name as agent_name
              FROM transactions t
              LEFT JOIN customers c ON t.customer_id = c.id
              LEFT JOIN agents a ON t.agent_id = a.id
              LEFT JOIN users u ON a.user_id = u.id
              WHERE c.name LIKE '%$search%' 
              OR c.national_code LIKE '%$search%'
              OR u.name LIKE '%$search%'
              ORDER BY t.created_at DESC";
} else {
    $query = "SELECT t.*, 
              c.name as customer_name, c.national_code,
              u.name as agent_name
              FROM transactions t
              LEFT JOIN customers c ON t.customer_id = c.id
              LEFT JOIN agents a ON t.agent_id = a.id
              LEFT JOIN users u ON a.user_id = u.id
              ORDER BY t.created_at DESC";
}

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت معاملات</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>مدیریت معاملات</h2>
            <div class="actions">
                <a href="add.php" class="button success">ثبت معامله جدید</a>
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
                <input type="text" name="search" placeholder="جستجو بر اساس نام مشتری، کد ملی یا نماینده" value="<?php echo $search; ?>">
                <button type="submit">جستجو</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>شماره</th>
                        <th>مشتری</th>
                        <th>کد ملی</th>
                        <th>نماینده</th>
                        <th>مبلغ کل</th>
                        <th>سهم شرکت</th>
                        <th>کارمزد ماهانه</th>
                        <th>وضعیت</th>
                        <th>تاریخ ثبت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($transaction = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $transaction['id']; ?></td>
                            <td><?php echo $transaction['customer_name']; ?></td>
                            <td><?php echo $transaction['national_code']; ?></td>
                            <td><?php echo $transaction['agent_name']; ?></td>
                            <td><?php echo number_format($transaction['amount']); ?> ریال</td>
                            <td><?php echo number_format($transaction['company_share']); ?> ریال</td>
                            <td><?php echo number_format($transaction['monthly_fee']); ?> ریال</td>
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
                            <td><?php echo jdate('Y/m/d H:i', strtotime($transaction['created_at'])); ?></td>
                            <td class="actions">
                                <a href="view.php?id=<?php echo $transaction['id']; ?>" class="button small">مشاهده</a>
                                <a href="edit.php?id=<?php echo $transaction['id']; ?>" class="button small warning">ویرایش</a>
                                <?php if($transaction['status'] == 'pending'): ?>
                                    <a href="delete.php?id=<?php echo $transaction['id']; ?>" 
                                       class="button small danger" 
                                       onclick="return confirm('آیا از حذف این معامله اطمینان دارید؟')">حذف</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-size: 0.9rem;
        }

        .status-pending {
            background: #ffc107;
            color: #000;
        }

        .status-active {
            background: #28a745;
            color: #fff;
        }

        .status-completed {
            background: #6c757d;
            color: #fff;
        }
    </style>
</body>
=======
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
    $query = "SELECT t.*, 
              c.name as customer_name, c.national_code,
              u.name as agent_name
              FROM transactions t
              LEFT JOIN customers c ON t.customer_id = c.id
              LEFT JOIN agents a ON t.agent_id = a.id
              LEFT JOIN users u ON a.user_id = u.id
              WHERE c.name LIKE '%$search%' 
              OR c.national_code LIKE '%$search%'
              OR u.name LIKE '%$search%'
              ORDER BY t.created_at DESC";
} else {
    $query = "SELECT t.*, 
              c.name as customer_name, c.national_code,
              u.name as agent_name
              FROM transactions t
              LEFT JOIN customers c ON t.customer_id = c.id
              LEFT JOIN agents a ON t.agent_id = a.id
              LEFT JOIN users u ON a.user_id = u.id
              ORDER BY t.created_at DESC";
}

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت معاملات</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>مدیریت معاملات</h2>
            <div class="actions">
                <a href="add.php" class="button success">ثبت معامله جدید</a>
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
                <input type="text" name="search" placeholder="جستجو بر اساس نام مشتری، کد ملی یا نماینده" value="<?php echo $search; ?>">
                <button type="submit">جستجو</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>شماره</th>
                        <th>مشتری</th>
                        <th>کد ملی</th>
                        <th>نماینده</th>
                        <th>مبلغ کل</th>
                        <th>سهم شرکت</th>
                        <th>کارمزد ماهانه</th>
                        <th>وضعیت</th>
                        <th>تاریخ ثبت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($transaction = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $transaction['id']; ?></td>
                            <td><?php echo $transaction['customer_name']; ?></td>
                            <td><?php echo $transaction['national_code']; ?></td>
                            <td><?php echo $transaction['agent_name']; ?></td>
                            <td><?php echo number_format($transaction['amount']); ?> ریال</td>
                            <td><?php echo number_format($transaction['company_share']); ?> ریال</td>
                            <td><?php echo number_format($transaction['monthly_fee']); ?> ریال</td>
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
                            <td><?php echo jdate('Y/m/d H:i', strtotime($transaction['created_at'])); ?></td>
                            <td class="actions">
                                <a href="view.php?id=<?php echo $transaction['id']; ?>" class="button small">مشاهده</a>
                                <a href="edit.php?id=<?php echo $transaction['id']; ?>" class="button small warning">ویرایش</a>
                                <?php if($transaction['status'] == 'pending'): ?>
                                    <a href="delete.php?id=<?php echo $transaction['id']; ?>" 
                                       class="button small danger" 
                                       onclick="return confirm('آیا از حذف این معامله اطمینان دارید؟')">حذف</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-size: 0.9rem;
        }

        .status-pending {
            background: #ffc107;
            color: #000;
        }

        .status-active {
            background: #28a745;
            color: #fff;
        }

        .status-completed {
            background: #6c757d;
            color: #fff;
        }
    </style>
</body>
>>>>>>> 5a00b2c6a58ad3b9223a7e4abdfac592d975c7a6
</html>