<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

// دریافت لیست شرکت‌ها
$companies_query = "SELECT * FROM companies WHERE is_active = 1 ORDER BY name ASC";
$companies_result = mysqli_query($connection, $companies_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $company_id = (int)$_POST['company_id'];
    $phone = clean($_POST['phone']);
    $mobile = clean($_POST['mobile']);
    $national_code = clean($_POST['national_code']);
    $bank_account = clean($_POST['bank_account']);
    $bank_card = clean($_POST['bank_card']);
    $sheba = clean($_POST['sheba']);
    $address = clean($_POST['address']);
    $notes = clean($_POST['notes']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $query = "INSERT INTO business_partners 
              (company_id, name, phone, mobile, national_code, bank_account, bank_card, sheba, address, notes, is_active) 
              VALUES 
              ($company_id, '$name', '$phone', '$mobile', '$national_code', '$bank_account', '$bank_card', '$sheba', 
               '$address', '$notes', $is_active)";

    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "طرف حساب جدید با موفقیت ثبت شد";
        redirect("index.php");
    } else {
        $error = "خطا در ثبت اطلاعات";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>افزودن طرف حساب جدید</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>افزودن طرف حساب جدید</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label>نام طرف حساب:</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>شرکت:</label>
                    <select name="company_id" required>
                        <option value="">انتخاب کنید</option>
                        <?php while($company = mysqli_fetch_assoc($companies_result)): ?>
                            <option value="<?php echo $company['id']; ?>">
                                <?php echo $company['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>تلفن:</label>
                    <input type="text" name="phone">
                </div>

                <div class="form-group">
                    <label>موبایل:</label>
                    <input type="text" name="mobile" required>
                </div>

                <div class="form-group">
                    <label>کد ملی:</label>
                    <input type="text" name="national_code" required>
                </div>

                <div class="form-group">
                    <label>شماره حساب:</label>
                    <input type="text" name="bank_account">
                </div>

                <div class="form-group">
                    <label>شماره کارت:</label>
                    <input type="text" name="bank_card">
                </div>

                <div class="form-group">
                    <label>شماره شبا:</label>
                    <input type="text" name="sheba">
                </div>

                <div class="form-group span-2">
                    <label>آدرس:</label>
                    <textarea name="address" rows="2"></textarea>
                </div>

                <div class="form-group span-2">
                    <label>یادداشت‌ها:</label>
                    <textarea name="notes" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" checked>
                        فعال
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button success">ذخیره</button>
                <a href="index.php" class="button">انصراف</a>
            </div>
        </form>
    </div>

    <style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .span-2 {
        grid-column: span 2;
    }
    </style>
</body>
</html>