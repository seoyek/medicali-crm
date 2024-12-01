<<<<<<< HEAD
<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

if (!isset($_GET['id'])) {
    redirect("index.php");
}

$id = (int)$_GET['id'];

// دریافت اطلاعات کامل معامله
$query = "SELECT t.*, 
          c.name as customer_name, c.phone as customer_phone, c.national_code, c.address,
          u.name as agent_name, u.username as agent_username
          FROM transactions t
          LEFT JOIN customers c ON t.customer_id = c.id
          LEFT JOIN agents a ON t.agent_id = a.id
          LEFT JOIN users u ON a.user_id = u.id
          WHERE t.id = $id";
          
$result = mysqli_query($connection, $query);
$transaction = mysqli_fetch_assoc($result);

if (!$transaction) {
    redirect("index.php");
}

// دریافت پرداخت‌های مرتبط
$payments_query = "SELECT p.*, t.amount as transaction_amount 
                  FROM payments p
                  LEFT JOIN transactions t ON p.transaction_id = t.id 
                  WHERE p.transaction_id = $id 
                  ORDER BY p.payment_date DESC";
$payments_result = mysqli_query($connection, $payments_query);

// محاسبه جمع پرداخت‌ها
$total_payments_query = "SELECT SUM(amount) as total FROM payments WHERE transaction_id = $id";
$total_payments_result = mysqli_query($connection, $total_payments_query);
$total_payments = mysqli_fetch_assoc($total_payments_result)['total'];
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>جزئیات معامله #<?php echo $id; ?></title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>جزئیات معامله #<?php echo $id; ?></h2>
            <div class="actions">
                <a href="index.php" class="button">بازگشت به لیست</a>
                <?php if($transaction['status'] != 'completed'): ?>
                    <a href="edit.php?id=<?php echo $id; ?>" class="button warning">ویرایش معامله</a>
                <?php endif; ?>
                <?php if($transaction['status'] == 'pending'): ?>
                    <a href="delete.php?id=<?php echo $id; ?>" 
                       class="button danger"
                       onclick="return confirm('آیا از حذف این معامله اطمینان دارید؟')">حذف معامله</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="info-grid">
            <!-- اطلاعات معامله -->
            <div class="info-box">
                <h3>اطلاعات معامله</h3>
                <table class="info-table">
                    <tr>
                        <th>تاریخ ثبت:</th>
                        <td><?php echo jdate('l j F Y H:i', strtotime($transaction['created_at'])); ?></td>
                    </tr>
                    <tr>
                        <th>وضعیت:</th>
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
                    <tr>
                        <th>مبلغ کل:</th>
                        <td><?php echo number_format($transaction['amount']); ?> ریال</td>
                    </tr>
                    <tr>
                        <th>سهم شرکت:</th>
                        <td><?php echo number_format($transaction['company_share']); ?> ریال</td>
                    </tr>
                    <tr>
                        <th>کارمزد ماهانه:</th>
                        <td><?php echo number_format($transaction['monthly_fee']); ?> ریال</td>
                    </tr>
                    <tr>
                        <th>جمع پرداخت‌ها:</th>
                        <td><?php echo number_format($total_payments); ?> ریال</td>
                    </tr>
                    <tr>
                        <th>مانده:</th>
                        <td><?php echo number_format($transaction['amount'] - $total_payments); ?> ریال</td>
                    </tr>
                </table>
            </div>

            <!-- اطلاعات مشتری -->
            <div class="info-box">
                <h3>اطلاعات مشتری</h3>
                <table class="info-table">
                    <tr>
                        <th>نام مشتری:</th>
                        <td><?php echo $transaction['customer_name']; ?></td>
                    </tr>
                    <tr>
                        <th>کد ملی:</th>
                        <td><?php echo $transaction['national_code']; ?></td>
                    </tr>
                    <tr>
                        <th>تلفن:</th>
                        <td><?php echo $transaction['customer_phone']; ?></td>
                    </tr>
                    <tr>
                        <th>آدرس:</th>
                        <td><?php echo $transaction['address']; ?></td>
                    </tr>
                </table>
            </div>

            <!-- اطلاعات نماینده -->
            <div class="info-box">
                <h3>اطلاعات نماینده</h3>
                <table class="info-table">
                    <tr>
                        <th>نام نماینده:</th>
                        <td><?php echo $transaction['agent_name']; ?></td>
                    </tr>
                    <tr>
                        <th>نام کاربری:</th>
                        <td><?php echo $transaction['agent_username']; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- پرداخت‌ها -->
        <div class="payments-section">
            <div class="section-header">
                <h3>پرداخت‌ها</h3>
                <a href="../payments/add.php?transaction_id=<?php echo $id; ?>" class="button success">ثبت پرداخت جدید</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>تاریخ</th>
                        <th>مبلغ</th>
                        <th>نوع پرداخت</th>
                        <th>توضیحات</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($payments_result) > 0): ?>
                        <?php while($payment = mysqli_fetch_assoc($payments_result)): ?>
                            <tr>
                                <td><?php echo jdate('Y/m/d', strtotime($payment['payment_date'])); ?></td>
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
                                <td><?php echo $payment['description'] ?? '-'; ?></td>
                                <td>
                                    <a href="../payments/delete.php?id=<?php echo $payment['id']; ?>&transaction_id=<?php echo $id; ?>" 
                                       class="button small danger"
                                       onclick="return confirm('آیا از حذف این پرداخت اطمینان دارید؟')">حذف</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">هیچ پرداختی ثبت نشده است</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .info-box {
            background: #fff;
            padding: 1.5rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .info-box h3 {
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #eee;
        }

        .info-table {
            width: 100%;
        }

        .info-table th,
        .info-table td {
            padding: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .info-table th {
            text-align: right;
            color: #666;
            width: 40%;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .payments-section {
            margin-top: 2rem;
            background: #fff;
            padding: 1.5rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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

if (!isset($_GET['id'])) {
    redirect("index.php");
}

$id = (int)$_GET['id'];

// دریافت اطلاعات کامل معامله
$query = "SELECT t.*, 
          c.name as customer_name, c.phone as customer_phone, c.national_code, c.address,
          u.name as agent_name, u.username as agent_username
          FROM transactions t
          LEFT JOIN customers c ON t.customer_id = c.id
          LEFT JOIN agents a ON t.agent_id = a.id
          LEFT JOIN users u ON a.user_id = u.id
          WHERE t.id = $id";
          
$result = mysqli_query($connection, $query);
$transaction = mysqli_fetch_assoc($result);

if (!$transaction) {
    redirect("index.php");
}

// دریافت پرداخت‌های مرتبط
$payments_query = "SELECT p.*, t.amount as transaction_amount 
                  FROM payments p
                  LEFT JOIN transactions t ON p.transaction_id = t.id 
                  WHERE p.transaction_id = $id 
                  ORDER BY p.payment_date DESC";
$payments_result = mysqli_query($connection, $payments_query);

// محاسبه جمع پرداخت‌ها
$total_payments_query = "SELECT SUM(amount) as total FROM payments WHERE transaction_id = $id";
$total_payments_result = mysqli_query($connection, $total_payments_query);
$total_payments = mysqli_fetch_assoc($total_payments_result)['total'];
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>جزئیات معامله #<?php echo $id; ?></title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>جزئیات معامله #<?php echo $id; ?></h2>
            <div class="actions">
                <a href="index.php" class="button">بازگشت به لیست</a>
                <?php if($transaction['status'] != 'completed'): ?>
                    <a href="edit.php?id=<?php echo $id; ?>" class="button warning">ویرایش معامله</a>
                <?php endif; ?>
                <?php if($transaction['status'] == 'pending'): ?>
                    <a href="delete.php?id=<?php echo $id; ?>" 
                       class="button danger"
                       onclick="return confirm('آیا از حذف این معامله اطمینان دارید؟')">حذف معامله</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="info-grid">
            <!-- اطلاعات معامله -->
            <div class="info-box">
                <h3>اطلاعات معامله</h3>
                <table class="info-table">
                    <tr>
                        <th>تاریخ ثبت:</th>
                        <td><?php echo jdate('l j F Y H:i', strtotime($transaction['created_at'])); ?></td>
                    </tr>
                    <tr>
                        <th>وضعیت:</th>
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
                    <tr>
                        <th>مبلغ کل:</th>
                        <td><?php echo number_format($transaction['amount']); ?> ریال</td>
                    </tr>
                    <tr>
                        <th>سهم شرکت:</th>
                        <td><?php echo number_format($transaction['company_share']); ?> ریال</td>
                    </tr>
                    <tr>
                        <th>کارمزد ماهانه:</th>
                        <td><?php echo number_format($transaction['monthly_fee']); ?> ریال</td>
                    </tr>
                    <tr>
                        <th>جمع پرداخت‌ها:</th>
                        <td><?php echo number_format($total_payments); ?> ریال</td>
                    </tr>
                    <tr>
                        <th>مانده:</th>
                        <td><?php echo number_format($transaction['amount'] - $total_payments); ?> ریال</td>
                    </tr>
                </table>
            </div>

            <!-- اطلاعات مشتری -->
            <div class="info-box">
                <h3>اطلاعات مشتری</h3>
                <table class="info-table">
                    <tr>
                        <th>نام مشتری:</th>
                        <td><?php echo $transaction['customer_name']; ?></td>
                    </tr>
                    <tr>
                        <th>کد ملی:</th>
                        <td><?php echo $transaction['national_code']; ?></td>
                    </tr>
                    <tr>
                        <th>تلفن:</th>
                        <td><?php echo $transaction['customer_phone']; ?></td>
                    </tr>
                    <tr>
                        <th>آدرس:</th>
                        <td><?php echo $transaction['address']; ?></td>
                    </tr>
                </table>
            </div>

            <!-- اطلاعات نماینده -->
            <div class="info-box">
                <h3>اطلاعات نماینده</h3>
                <table class="info-table">
                    <tr>
                        <th>نام نماینده:</th>
                        <td><?php echo $transaction['agent_name']; ?></td>
                    </tr>
                    <tr>
                        <th>نام کاربری:</th>
                        <td><?php echo $transaction['agent_username']; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- پرداخت‌ها -->
        <div class="payments-section">
            <div class="section-header">
                <h3>پرداخت‌ها</h3>
                <a href="../payments/add.php?transaction_id=<?php echo $id; ?>" class="button success">ثبت پرداخت جدید</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>تاریخ</th>
                        <th>مبلغ</th>
                        <th>نوع پرداخت</th>
                        <th>توضیحات</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($payments_result) > 0): ?>
                        <?php while($payment = mysqli_fetch_assoc($payments_result)): ?>
                            <tr>
                                <td><?php echo jdate('Y/m/d', strtotime($payment['payment_date'])); ?></td>
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
                                <td><?php echo $payment['description'] ?? '-'; ?></td>
                                <td>
                                    <a href="../payments/delete.php?id=<?php echo $payment['id']; ?>&transaction_id=<?php echo $id; ?>" 
                                       class="button small danger"
                                       onclick="return confirm('آیا از حذف این پرداخت اطمینان دارید؟')">حذف</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">هیچ پرداختی ثبت نشده است</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .info-box {
            background: #fff;
            padding: 1.5rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .info-box h3 {
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #eee;
        }

        .info-table {
            width: 100%;
        }

        .info-table th,
        .info-table td {
            padding: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .info-table th {
            text-align: right;
            color: #666;
            width: 40%;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .payments-section {
            margin-top: 2rem;
            background: #fff;
            padding: 1.5rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</body>
>>>>>>> 5a00b2c6a58ad3b9223a7e4abdfac592d975c7a6
</html>