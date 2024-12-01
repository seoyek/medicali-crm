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

// دریافت اطلاعات طرف حساب
$query = "SELECT * FROM business_partners WHERE id = $id";
$result = mysqli_query($connection, $query);
$partner = mysqli_fetch_assoc($result);

if (!$partner) {
    redirect("index.php");
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

    $query = "UPDATE business_partners SET 
              company_id = $company_id,
              name = '$name',
              phone = '$phone',
              mobile = '$mobile',
              national_code = '$national_code',
              bank_account = '$bank_account',
              bank_card = '$bank_card',
              sheba = '$sheba',
              address = '$address',
              notes = '$notes',
              is_active = $is_active
              WHERE id = $id";

    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "اطلاعات طرف حساب با موفقیت بروزرسانی شد";
        redirect("index.php");
    } else {
        $error = "خطا در بروزرسانی اطلاعات";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ویرایش طرف حساب</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>ویرایش طرف حساب</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label>نام طرف حساب:</label>
                    <input type="text" name="name" value="<?php echo $partner['name']; ?>" required>
                </div>

                <div class="form-group">
                    <label>شرکت:</label>
                    <select name="company_id" required>
                        <option value="">انتخاب کنید</option>
                        <?php while($company = mysqli_fetch_assoc($companies_result)): ?>
                            <option value="<?php echo $company['id']; ?>" 
                                    <?php echo ($company['id'] == $partner['company_id']) ? 'selected' : ''; ?>>
                                <?php echo $company['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>تلفن:</label>
                    <input type="text" name="phone" value="<?php echo $partner['phone']; ?>">
                </div>

                <div class="form-group">
                    <label>موبایل:</label>
                    <input type="text" name="mobile" value="<?php echo $partner['mobile']; ?>" required>
                </div>

                <div class="form-group">
                    <label>کد ملی:</label>
                    <input type="text" name="national_code" value="<?php echo $partner['national_code']; ?>" required>
                </div>

                <div class="form-group">
                    <label>شماره حساب:</label>
                    <input type="text" name="bank_account" value="<?php echo $partner['bank_account']; ?>">
                </div>

                <div class="form-group">
                    <label>شماره کارت:</label>
                    <input type="text" name="bank_card" value="<?php echo $partner['bank_card']; ?>">
                </div>

                <div class="form-group">
                    <label>شماره شبا:</label>
                    <input type="text" name="sheba" value="<?php echo $partner['sheba']; ?>">
                </div>

                <div class="form-group span-2">
                    <label>آدرس:</label>
                    <textarea name="address" rows="2"><?php echo $partner['address']; ?></textarea>
                </div>

                <div class="form-group span-2">
                    <label>یادداشت‌ها:</label>
                    <textarea name="notes" rows="3"><?php echo $partner['notes']; ?></textarea>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" <?php echo $partner['is_active'] ? 'checked' : ''; ?>>
                        فعال
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button success">بروزرسانی</button>
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