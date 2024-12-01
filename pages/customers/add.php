<?php
require_once "../../includes/config.php";
require_once "../../includes/functions.php";
require_once "../../includes/jdf.php";

if (!isLoggedIn()) {
    redirect("/CRM/index.php");
}

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
    
    $query = "INSERT INTO customers (name, phone, address, national_code, agent_id) 
              VALUES ('$name', '$phone', '$address', '$national_code', $agent_id)";
    
    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "مشتری جدید با موفقیت ثبت شد";
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
    <title>افزودن مشتری جدید</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>افزودن مشتری جدید</h2>
        
        <form method="post" class="data-form">
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label>نام و نام خانوادگی:</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>تلفن:</label>
                <input type="text" name="phone" required>
            </div>
            
            <div class="form-group">
                <label>کد ملی:</label>
                <input type="text" name="national_code" required>
            </div>
            
            <div class="form-group">
                <label>آدرس:</label>
                <textarea name="address" required></textarea>
            </div>

            <div class="form-group">
                <label>انتخاب نماینده:</label>
                <select name="agent_id" required>
                    <option value="">انتخاب کنید</option>
                    <?php while($agent = mysqli_fetch_assoc($agents_result)): ?>
                        <option value="<?php echo $agent['agent_id']; ?>">
                            <?php echo $agent['name'] . ' (' . $agent['username'] . ')'; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="button success">ذخیره</button>
                <a href="index.php" class="button">انصراف</a>
            </div>
        </form>
    </div>
</body>
</html>