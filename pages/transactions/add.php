<<<<<<< HEAD
<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

// دریافت لیست مشتریان با اطلاعات نماینده
$customers_query = "SELECT c.*, u.name as agent_name 
                   FROM customers c
                   LEFT JOIN agents a ON c.agent_id = a.id
                   LEFT JOIN users u ON a.user_id = u.id
                   ORDER BY c.name ASC";
$customers_result = mysqli_query($connection, $customers_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = (int)$_POST['customer_id'];
    
    // دریافت agent_id از اطلاعات مشتری
    $customer_query = "SELECT agent_id FROM customers WHERE id = $customer_id";
    $customer_result = mysqli_query($connection, $customer_query);
    $customer = mysqli_fetch_assoc($customer_result);
    $agent_id = $customer['agent_id'];

    $amount = (float)clean($_POST['amount']);
    
    // محاسبات خودکار
    $company_share = $amount * 0.714; // 71.4% مبلغ کارت
    $monthly_fee = $amount * 0.02;    // 2% کارمزد ماهانه

    $query = "INSERT INTO transactions 
              (customer_id, agent_id, amount, company_share, monthly_fee, status) 
              VALUES 
              ($customer_id, $agent_id, $amount, $company_share, $monthly_fee, 'pending')";
    
    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "معامله جدید با موفقیت ثبت شد";
        redirect("index.php");
    } else {
        $error = "خطا در ثبت معامله";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ثبت معامله جدید</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>ثبت معامله جدید</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label>انتخاب مشتری:</label>
                    <select name="customer_id" required class="select2">
                        <option value="">انتخاب کنید</option>
                        <?php while($customer = mysqli_fetch_assoc($customers_result)): ?>
                            <option value="<?php echo $customer['id']; ?>">
                                <?php echo $customer['name'] . ' - ' . $customer['national_code'] . 
                                        ' (نماینده: ' . $customer['agent_name'] . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>مبلغ کل (ریال):</label>
                    <input type="number" name="amount" required min="0" step="1000" 
                           onchange="calculateShares(this.value)">
                </div>

                <div class="form-group">
                    <label>سهم شرکت (71.4%):</label>
                    <input type="text" id="company_share" readonly>
                </div>

                <div class="form-group">
                    <label>کارمزد ماهانه (2%):</label>
                    <input type="text" id="monthly_fee" readonly>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button success">ثبت معامله</button>
                <a href="index.php" class="button">انصراف</a>
            </div>
        </form>
    </div>

    <style>
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .form-group input[readonly] {
            background: #f5f5f5;
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
=======
<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

// دریافت لیست مشتریان با اطلاعات نماینده
$customers_query = "SELECT c.*, u.name as agent_name 
                   FROM customers c
                   LEFT JOIN agents a ON c.agent_id = a.id
                   LEFT JOIN users u ON a.user_id = u.id
                   ORDER BY c.name ASC";
$customers_result = mysqli_query($connection, $customers_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = (int)$_POST['customer_id'];
    
    // دریافت agent_id از اطلاعات مشتری
    $customer_query = "SELECT agent_id FROM customers WHERE id = $customer_id";
    $customer_result = mysqli_query($connection, $customer_query);
    $customer = mysqli_fetch_assoc($customer_result);
    $agent_id = $customer['agent_id'];

    $amount = (float)clean($_POST['amount']);
    
    // محاسبات خودکار
    $company_share = $amount * 0.714; // 71.4% مبلغ کارت
    $monthly_fee = $amount * 0.02;    // 2% کارمزد ماهانه

    $query = "INSERT INTO transactions 
              (customer_id, agent_id, amount, company_share, monthly_fee, status) 
              VALUES 
              ($customer_id, $agent_id, $amount, $company_share, $monthly_fee, 'pending')";
    
    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "معامله جدید با موفقیت ثبت شد";
        redirect("index.php");
    } else {
        $error = "خطا در ثبت معامله";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ثبت معامله جدید</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>ثبت معامله جدید</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label>انتخاب مشتری:</label>
                    <select name="customer_id" required class="select2">
                        <option value="">انتخاب کنید</option>
                        <?php while($customer = mysqli_fetch_assoc($customers_result)): ?>
                            <option value="<?php echo $customer['id']; ?>">
                                <?php echo $customer['name'] . ' - ' . $customer['national_code'] . 
                                        ' (نماینده: ' . $customer['agent_name'] . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>مبلغ کل (ریال):</label>
                    <input type="number" name="amount" required min="0" step="1000" 
                           onchange="calculateShares(this.value)">
                </div>

                <div class="form-group">
                    <label>سهم شرکت (71.4%):</label>
                    <input type="text" id="company_share" readonly>
                </div>

                <div class="form-group">
                    <label>کارمزد ماهانه (2%):</label>
                    <input type="text" id="monthly_fee" readonly>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button success">ثبت معامله</button>
                <a href="index.php" class="button">انصراف</a>
            </div>
        </form>
    </div>

    <style>
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .form-group input[readonly] {
            background: #f5f5f5;
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
>>>>>>> 5a00b2c6a58ad3b9223a7e4abdfac592d975c7a6
</html>