<<<<<<< HEAD
<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = clean($_POST['name']);
    $commission_rate = clean($_POST['commission_rate']);

    // ایجاد کاربر جدید
    $user_query = "INSERT INTO users (username, password, name, role) 
                   VALUES ('$username', '$password', '$name', 'agent')";
    
    if (mysqli_query($connection, $user_query)) {
        $user_id = mysqli_insert_id($connection);
        
        // ایجاد نماینده
        $agent_query = "INSERT INTO agents (user_id, commission_rate) 
                       VALUES ($user_id, $commission_rate)";
        
        if (mysqli_query($connection, $agent_query)) {
            $_SESSION['success'] = "نماینده جدید با موفقیت ثبت شد";
            redirect("index.php");
        } else {
            $error = "خطا در ثبت اطلاعات نماینده";
        }
    } else {
        $error = "خطا در ثبت اطلاعات کاربری";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>افزودن نماینده جدید</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>افزودن نماینده جدید</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label>نام و نام خانوادگی:</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>نام کاربری:</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>رمز عبور:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>درصد کارمزد:</label>
                <input type="number" name="commission_rate" step="0.1" min="0" max="100" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="button success">ثبت نماینده</button>
                <a href="index.php" class="button">انصراف</a>
            </div>
        </form>
    </div>
</body>
=======
<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = clean($_POST['name']);
    $commission_rate = clean($_POST['commission_rate']);

    // ایجاد کاربر جدید
    $user_query = "INSERT INTO users (username, password, name, role) 
                   VALUES ('$username', '$password', '$name', 'agent')";
    
    if (mysqli_query($connection, $user_query)) {
        $user_id = mysqli_insert_id($connection);
        
        // ایجاد نماینده
        $agent_query = "INSERT INTO agents (user_id, commission_rate) 
                       VALUES ($user_id, $commission_rate)";
        
        if (mysqli_query($connection, $agent_query)) {
            $_SESSION['success'] = "نماینده جدید با موفقیت ثبت شد";
            redirect("index.php");
        } else {
            $error = "خطا در ثبت اطلاعات نماینده";
        }
    } else {
        $error = "خطا در ثبت اطلاعات کاربری";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>افزودن نماینده جدید</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>افزودن نماینده جدید</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label>نام و نام خانوادگی:</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>نام کاربری:</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>رمز عبور:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>درصد کارمزد:</label>
                <input type="number" name="commission_rate" step="0.1" min="0" max="100" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="button success">ثبت نماینده</button>
                <a href="index.php" class="button">انصراف</a>
            </div>
        </form>
    </div>
</body>
>>>>>>> 5a00b2c6a58ad3b9223a7e4abdfac592d975c7a6
</html>