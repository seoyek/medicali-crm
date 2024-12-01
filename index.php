<<<<<<< HEAD
<?php
require_once "includes/config.php";
require_once "includes/functions.php";
require_once "includes/jdf.php";
?>
<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم مدیریت وام خرید کالا</title>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1>سیستم مدیریت وام خرید کالا</h1>
            <?php if(isLoggedIn()): ?>
                <nav>
                    <ul>
                        <li><a href="pages/users">کاربران</a></li>
                        <li><a href="pages/customers">مشتریان</a></li>
                        <li><a href="pages/agents">نمایندگان</a></li>
                        <li><a href="pages/transactions">معاملات</a></li>
                        <li><a href="pages/reports">گزارشات</a></li>
                    </ul>
                </nav>
            <?php endif; ?>
        </header>
        <main>
            <?php if(!isLoggedIn()): ?>
                <!-- فرم ورود -->
                <form method="post" action="pages/users/login.php">
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
            <?php else: ?>
                <div class="dashboard">
                    <!-- داشبورد اصلی -->
                </div>
            <?php endif; ?>
        </main>
    </div>
    <script src="assets/js/main.js"></script>
</body>
=======
<?php
require_once "includes/config.php";
require_once "includes/functions.php";
require_once "../../includes/jdf.php";
?>
<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم مدیریت وام خرید کالا</title>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1>سیستم مدیریت وام خرید کالا</h1>
            <?php if(isLoggedIn()): ?>
                <nav>
                    <ul>
                        <li><a href="pages/users">کاربران</a></li>
                        <li><a href="pages/customers">مشتریان</a></li>
                        <li><a href="pages/agents">نمایندگان</a></li>
                        <li><a href="pages/transactions">معاملات</a></li>
                        <li><a href="pages/reports">گزارشات</a></li>
                    </ul>
                </nav>
            <?php endif; ?>
        </header>
        <main>
            <?php if(!isLoggedIn()): ?>
                <!-- فرم ورود -->
                <form method="post" action="pages/users/login.php">
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
            <?php else: ?>
                <div class="dashboard">
                    <!-- داشبورد اصلی -->
                </div>
            <?php endif; ?>
        </main>
    </div>
    <script src="assets/js/main.js"></script>
</body>
>>>>>>> 5a00b2c6a58ad3b9223a7e4abdfac592d975c7a6
</html>