<<<<<<< HEAD
<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            redirect("/CRM/index.php");
        }
    }
    
    $error = "نام کاربری یا رمز عبور اشتباه است";
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ورود به سیستم</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <form method="post">
            <h2>ورود به سیستم</h2>
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <div>
                <label>نام کاربری:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>رمز عبور:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">ورود</button>
        </form>
    </div>
</body>
=======
<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            redirect("/CRM/index.php");
        }
    }
    
    $error = "نام کاربری یا رمز عبور اشتباه است";
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ورود به سیستم</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <form method="post">
            <h2>ورود به سیستم</h2>
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <div>
                <label>نام کاربری:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>رمز عبور:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">ورود</button>
        </form>
    </div>
</body>
>>>>>>> 5a00b2c6a58ad3b9223a7e4abdfac592d975c7a6
</html>