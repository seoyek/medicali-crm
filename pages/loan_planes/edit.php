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

// دریافت اطلاعات طرح
$query = "SELECT * FROM loan_plans WHERE id = $id";
$result = mysqli_query($connection, $query);
$plan = mysqli_fetch_assoc($result);

if (!$plan) {
    redirect("index.php");
}

// دریافت لیست شرکت‌ها
$companies_query = "SELECT * FROM companies WHERE is_active = 1 ORDER BY name ASC";
$companies_result = mysqli_query($connection, $companies_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean($_POST['title']);
    $company_id = (int)$_POST['company_id'];
    $loan_amount = (float)clean($_POST['loan_amount']);
    $cash_amount = (float)clean($_POST['cash_amount']);
    $commission_rate = (float)clean($_POST['commission_rate']);
    $company_share_rate = (float)clean($_POST['company_share_rate']);
    $description = clean($_POST['description']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $query = "UPDATE loan_plans SET 
              title = '$title',
              company_id = $company_id,
              loan_amount = $loan_amount,
              cash_amount = $cash_amount,
              commission_rate = $commission_rate,
              company_share_rate = $company_share_rate,
              description = '$description',
              is_active = $is_active
              WHERE id = $id";

    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "طرح با موفقیت بروزرسانی شد";
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
    <title>ویرایش طرح</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>ویرایش طرح</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label>عنوان طرح:</label>
                    <input type="text" name="title" value="<?php echo $plan['title']; ?>" required>
                </div>

                <div class="form-group">
                    <label>شرکت:</label>
                    <select name="company_id" required>
                        <option value="">انتخاب کنید</option>
                        <?php while($company = mysqli_fetch_assoc($companies_result)): ?>
                            <option value="<?php echo $company['id']; ?>" 
                                    <?php echo ($company['id'] == $plan['company_id']) ? 'selected' : ''; ?>>
                                <?php echo $company['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>مبلغ وام (ریال):</label>
                    <input type="number" name="loan_amount" value="<?php echo $plan['loan_amount']; ?>" required min="0" step="1000">
                </div>

                <div class="form-group">
                    <label>مبلغ نقدشوندگی (ریال):</label>
                    <input type="number" name="cash_amount" value="<?php echo $plan['cash_amount']; ?>" required min="0" step="1000">
                </div>

                <div class="form-group">
                    <label>درصد کارمزد:</label>
                    <input type="number" name="commission_rate" value="<?php echo $plan['commission_rate']; ?>" required min="0" max="100" step="0.01">
                </div>

                <div class="form-group">
                    <label>درصد سهم شرکت:</label>
                    <input type="number" name="company_share_rate" value="<?php echo $plan['company_share_rate']; ?>" required min="0" max="100" step="0.01">
                </div>

                <div class="form-group span-2">
                    <label>توضیحات:</label>
                    <textarea name="description" rows="4"><?php echo $plan['description']; ?></textarea>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" <?php echo $plan['is_active'] ? 'checked' : ''; ?>>
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
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .checkbox-label input {
        width: auto;
    }
    </style>
</body>
</html>