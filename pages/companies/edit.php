<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

if (!isset($_GET['id'])) {
    redirect("index.php");
}

$id = (int)$_GET['id'];

// دریافت اطلاعات شرکت
$query = "SELECT * FROM companies WHERE id = $id";
$result = mysqli_query($connection, $query);
$company = mysqli_fetch_assoc($result);

if (!$company) {
    redirect("index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $phone = clean($_POST['phone']);
    $contact_person = clean($_POST['contact_person']);
    $email = clean($_POST['email']);
    $website = clean($_POST['website']);
    $address = clean($_POST['address']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $query = "UPDATE companies SET 
              name = '$name',
              phone = '$phone',
              contact_person = '$contact_person',
              email = '$email',
              website = '$website',
              address = '$address',
              is_active = $is_active
              WHERE id = $id";

    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "اطلاعات شرکت با موفقیت بروزرسانی شد";
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
    <title>ویرایش شرکت</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>ویرایش شرکت</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label>نام شرکت:</label>
                    <input type="text" name="name" value="<?php echo $company['name']; ?>" required>
                </div>

                <div class="form-group">
                    <label>تلفن:</label>
                    <input type="text" name="phone" value="<?php echo $company['phone']; ?>" required>
                </div>

                <div class="form-group">
                    <label>نام رابط:</label>
                    <input type="text" name="contact_person" value="<?php echo $company['contact_person']; ?>">
                </div>

                <div class="form-group">
                    <label>ایمیل:</label>
                    <input type="email" name="email" value="<?php echo $company['email']; ?>">
                </div>

                <div class="form-group">
                    <label>وبسایت:</label>
                    <input type="url" name="website" value="<?php echo $company['website']; ?>">
                </div>

                <div class="form-group span-2">
                    <label>آدرس:</label>
                    <textarea name="address" rows="3"><?php echo $company['address']; ?></textarea>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" <?php echo $company['is_active'] ? 'checked' : ''; ?>>
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