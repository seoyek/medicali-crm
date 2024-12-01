<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = clean($_POST['name']);
    $role = clean($_POST['role']);
    
    // بررسی تکراری نبودن نام کاربری
    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($connection, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error = "این نام کاربری قبلاً ثبت شده است";
    } else {
        $query = "INSERT INTO users (username, password, name, role) 
                  VALUES ('$username', '$password', '$name', '$role')";
        
        if (mysqli_query($connection, $query)) {
            redirect("/CRM/index.php");
        } else {
            $error = "خطا در ثبت اطلاعات";
        }
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ثبت‌نام کاربر جدید</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <form method="post">
            <h2>ثبت‌نام کاربر جدید</h2>
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <div>
                <label>نام و نام خانوادگی:</label>
                <input type="text" name="name" required>
            </div>
            <div>
                <label>نام کاربری:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>رمز عبور:</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label>نقش:</label>
                <select name="role" required>
                    <option value="admin">مدیر</option>
                    <option value="agent">نماینده</option>
                    <option value="user">کاربر</option>
                </select>
            </div>
            <button type="submit">ثبت‌نام</button>
        </form>
    </div>
</body>
</html>