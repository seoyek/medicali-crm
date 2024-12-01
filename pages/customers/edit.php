<<<<<<< HEAD
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

// دریافت لیست نمایندگان
$agents_query = "SELECT a.id as agent_id, u.name, u.username 
                 FROM agents a 
                 LEFT JOIN users u ON a.user_id = u.id 
                 ORDER BY u.name ASC";
$agents_result = mysqli_query($connection, $agents_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $phone = clean($_POST['phone']);
    $address = clean($_POST['address']);
    $national_code = clean($_POST['national_code']);
    $agent_id = (int)$_POST['agent_id'];
    
    $query = "UPDATE customers SET 
              name = '$name',
              phone = '$phone',
              address = '$address',
              national_code = '$national_code',
              agent_id = $agent_id
              WHERE id = $id";
    
    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "اطلاعات مشتری با موفقیت بروز شد";
        redirect("index.php");
    } else {
        $error = "خطا در بروزرسانی اطلاعات";
    }
}

// دریافت اطلاعات مشتری
$query = "SELECT * FROM customers WHERE id = $id";
$result = mysqli_query($connection, $query);
$customer = mysqli_fetch_assoc($result);

if (!$customer) {
    redirect("index.php");
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ویرایش مشتری</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>ویرایش مشتری</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label>نام و نام خانوادگی:</label>
                <input type="text" name="name" value="<?php echo $customer['name']; ?>" required>
            </div>

            <div class="form-group">
                <label>تلفن:</label>
                <input type="text" name="phone" value="<?php echo $customer['phone']; ?>" required>
            </div>

            <div class="form-group">
                <label>کد ملی:</label>
                <input type="text" name="national_code" value="<?php echo $customer['national_code']; ?>" required>
            </div>

            <div class="form-group">
                <label>آدرس:</label>
                <textarea name="address" required><?php echo $customer['address']; ?></textarea>
            </div>

            <div class="form-group">
                <label>انتخاب نماینده:</label>
                <select name="agent_id" required>
                    <option value="">انتخاب کنید</option>
                    <?php mysqli_data_seek($agents_result, 0); ?>
                    <?php while($agent = mysqli_fetch_assoc($agents_result)): ?>
                        <option value="<?php echo $agent['agent_id']; ?>" 
                                <?php echo ($agent['agent_id'] == $customer['agent_id']) ? 'selected' : ''; ?>>
                            <?php echo $agent['name'] . ' (' . $agent['username'] . ')'; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="button success">بروزرسانی</button>
                <a href="index.php" class="button">انصراف</a>
            </div>
        </form>
    </div>
</body>
=======
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

// دریافت لیست نمایندگان
$agents_query = "SELECT a.id as agent_id, u.name, u.username 
                 FROM agents a 
                 LEFT JOIN users u ON a.user_id = u.id 
                 ORDER BY u.name ASC";
$agents_result = mysqli_query($connection, $agents_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $phone = clean($_POST['phone']);
    $address = clean($_POST['address']);
    $national_code = clean($_POST['national_code']);
    $agent_id = (int)$_POST['agent_id'];
    
    $query = "UPDATE customers SET 
              name = '$name',
              phone = '$phone',
              address = '$address',
              national_code = '$national_code',
              agent_id = $agent_id
              WHERE id = $id";
    
    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "اطلاعات مشتری با موفقیت بروز شد";
        redirect("index.php");
    } else {
        $error = "خطا در بروزرسانی اطلاعات";
    }
}

// دریافت اطلاعات مشتری
$query = "SELECT * FROM customers WHERE id = $id";
$result = mysqli_query($connection, $query);
$customer = mysqli_fetch_assoc($result);

if (!$customer) {
    redirect("index.php");
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ویرایش مشتری</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2>ویرایش مشتری</h2>
            <a href="index.php" class="button">بازگشت به لیست</a>
        </div>

        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label>نام و نام خانوادگی:</label>
                <input type="text" name="name" value="<?php echo $customer['name']; ?>" required>
            </div>

            <div class="form-group">
                <label>تلفن:</label>
                <input type="text" name="phone" value="<?php echo $customer['phone']; ?>" required>
            </div>

            <div class="form-group">
                <label>کد ملی:</label>
                <input type="text" name="national_code" value="<?php echo $customer['national_code']; ?>" required>
            </div>

            <div class="form-group">
                <label>آدرس:</label>
                <textarea name="address" required><?php echo $customer['address']; ?></textarea>
            </div>

            <div class="form-group">
                <label>انتخاب نماینده:</label>
                <select name="agent_id" required>
                    <option value="">انتخاب کنید</option>
                    <?php mysqli_data_seek($agents_result, 0); ?>
                    <?php while($agent = mysqli_fetch_assoc($agents_result)): ?>
                        <option value="<?php echo $agent['agent_id']; ?>" 
                                <?php echo ($agent['agent_id'] == $customer['agent_id']) ? 'selected' : ''; ?>>
                            <?php echo $agent['name'] . ' (' . $agent['username'] . ')'; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="button success">بروزرسانی</button>
                <a href="index.php" class="button">انصراف</a>
            </div>
        </form>
    </div>
</body>
>>>>>>> 5a00b2c6a58ad3b9223a7e4abdfac592d975c7a6
</html>