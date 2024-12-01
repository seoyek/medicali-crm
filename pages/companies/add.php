<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $phone = clean($_POST['phone']);
    $contact_person = clean($_POST['contact_person']);
    $email = clean($_POST['email']);
    $website = clean($_POST['website']);
    $address = clean($_POST['address']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $query = "INSERT INTO companies 
              (name, phone, contact_person, email, website, address, is_active) 
              VALUES 
              ('$name', '$phone', '$contact_person', '$email', '$website', '$address', $is_active)";

    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "شرکت جدید با موفقیت ثبت شد";
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
    <title>افزودن شرکت جدید</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>افزودن شرکت جدید</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label>نام شرکت:</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>تلفن:</label>
                    <input type="text" name="phone" required>
                </div>

                <div class="form-group">
                    <label>نام رابط:</label>
                    <input type="text" name="contact_person">
                </div>

                <div class="form-group">
                    <label>ایمیل:</label>
                    <input type="email" name="email">
                </div>

                <div class="form-group">
                    <label>وبسایت:</label>
                    <input type="url" name="website">
                </div>

                <div class="form-group span-2">
                    <label>آدرس:</label>
                    <textarea name="address" rows="3"></textarea>
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