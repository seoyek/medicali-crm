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

// دریافت اطلاعات معامله
$query = "SELECT t.*, 
          c.name as customer_name, c.national_code,
          u.name as agent_name
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = (float)clean($_POST['amount']);
    $status = clean($_POST['status']);
    
    // محاسبات خودکار
    $company_share = $amount * 0.714;
    $monthly_fee = $amount * 0.02;

    $query = "UPDATE transactions SET 
              amount = $amount,
              company_share = $company_share,
              monthly_fee = $monthly_fee,
              status = '$status'
              WHERE id = $id";
    
    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "معامله با موفقیت بروز شد";
        redirect("index.php");
    } else {
        $error = "خطا در بروزرسانی معامله";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ویرایش معامله</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>ویرایش معامله #<?php echo $id; ?></h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <div class="info-section">
            <h3>اطلاعات معامله</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>مشتری:</strong>
                    <span><?php echo $transaction['customer_name']; ?></span>
                </div>
                <div class="info-item">
                    <strong>کد ملی:</strong>
                    <span><?php echo $transaction['national_code']; ?></span>
                </div>
                <div class="info-item">
                    <strong>نماینده:</strong>
                    <span><?php echo $transaction['agent_name']; ?></span>
                </div>
                <div class="info-item">
                    <strong>تاریخ ثبت:</strong>
                    <span><?php echo jdate('Y/m/d H:i', strtotime($transaction['created_at'])); ?></span>
                </div>
            </div>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label>مبلغ کل (ریال):</label>
                    <input type="number" name="amount" required min="0" step="1000" 
                           value="<?php echo $transaction['amount']; ?>"
                           onchange="calculateShares(this.value)">
                </div>

                <div class="form-group">
                    <label>سهم شرکت (71.4%):</label>
                    <input type="text" id="company_share" readonly 
                           value="<?php echo number_format($transaction['company_share']); ?> ریال">
                </div>

                <div class="form-group">
                    <label>کارمزد ماهانه (2%):</label>
                    <input type="text" id="monthly_fee" readonly 
                           value="<?php echo number_format($transaction['monthly_fee']); ?> ریال">
                </div>

                <div class="form-group">
                    <label>وضعیت:</label>
                    <select name="status" required>
                        <option value="pending" <?php echo ($transaction['status'] == 'pending') ? 'selected' : ''; ?>>
                            در انتظار
                        </option>
                        <option value="active" <?php echo ($transaction['status'] == 'active') ? 'selected' : ''; ?>>
                            فعال
                        </option>
                        <option value="completed" <?php echo ($transaction['status'] == 'completed') ? 'selected' : ''; ?>>
                            تکمیل شده
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button success">بروزرسانی</button>
                <a href="index.php" class="button">انصراف</a>
            </div>
        </form>
    </div>

    <style>
        .info-section {
            background: #f8f9fa;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-item strong {
            color: #666;
            margin-bottom: 0.25rem;
        }
    </style>

    <script>
        function calculateShares(amount) {
            const companyShare = amount * 0.714;
            const monthlyFee = amount * 0.02;
            
            document.getElementById('company_share').value = new Intl.NumberFormat('fa-IR').format(companyShare) + ' ریال';
            document.getElementById('monthly_fee').value = new Intl.NumberFormat('fa-IR').format(monthlyFee) + ' ریال';
        }
    </script>
</body>
</html>