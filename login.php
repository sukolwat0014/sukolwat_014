<?php
session_start();
require 'connect.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน';
    } else {
        // ดึงแถวข้อมูลผู้ใช้
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, password FROM users WHERE username = ?"
        );
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $hash);
        if (mysqli_stmt_fetch($stmt)) {
            // ตรวจสอบรหัสผ่าน
            if (password_verify($password, $hash)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'ไม่พบชื่อผู้ใช้นี้';
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <h1>เข้าสู่ระบบ</h1>
        <?php if ($error): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <fieldset>
                <legend>ข้อมูลเข้าสู่ระบบ</legend>
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" id="username" name="username" required>

                <label for="password">รหัสผ่าน</label>
                <input type="password" id="password" name="password" required>
            </fieldset>

            <div class="button-group">
                <input type="submit" value="เข้าสู่ระบบ">
            </div>
        </form>
        <p>ยังไม่มีบัญชี? <a href="register.php>สมัครสมาชิกที่นี่</a></p>
        </div>
</body>
</html>