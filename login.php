<?php
session_start();
require 'condb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; // ในที่นี้คือ fullname หรือ student_id
    $password = $_POST['password'];

    // ตรวจสอบจาก fullname หรือ student_id ก็ได้ (ตัวอย่างนี้ใช้ student_id)
    $sql = "SELECT * FROM users WHERE student_id = :username OR fullname = :username LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['level'] = $user['level'];

        // ตรวจสอบ role
        if ($user['level'] == 0) {
            // Admin
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin/index.php");
            exit();
        } elseif ($user['level'] == 1) {
            // Teacher
            $_SESSION['teacher_logged_in'] = true;
            header("Location: teacher/index.php");
            exit();
        } elseif ($user['level'] == 2) {
            // Student
            $_SESSION['student_logged_in'] = true;
            header("Location: student/index.php");
            exit();
        } else {
            $error = "สิทธิ์ผู้ใช้ไม่ถูกต้อง";
        }
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap');
        body {
            font-family: 'Kanit', Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 400px;
            padding: 40px 35px 30px 35px;
            background: rgba(255,255,255,0.95);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,0.18);
            position: relative;
            overflow: hidden;
        }
        .login-container::before {
            content: '';
            position: absolute;
            top: -60px; left: -60px;
            width: 180px; height: 180px;
            opacity: 0.18;
            z-index: 0;
        }
        .login-container h2 {
            text-align: center;
            font-size: 2.2rem;
            font-weight: 700;
            color: #1e3c72;
            margin-bottom: 28px;
            letter-spacing: 1px;
            z-index: 1;
            position: relative;
        }
        .login-container label {
            font-weight: 600;
            color: #2a5298;
            margin-bottom: 6px;
            display: block;
            z-index: 1;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            margin: 10px 0 22px 0;
            border: 1.5px solid #b0c4de;
            border-radius: 8px;
            font-size: 1.05rem;
            background: #f7faff;
            transition: border 0.2s;
            z-index: 1;
        }
        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            border: 1.5px solid #007bff;
            outline: none;
            background: #eaf1fb;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #007bff 0%, #2a5298 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(44, 62, 80, 0.12);
            transition: background 0.2s, transform 0.1s;
            z-index: 1;
        }
        .login-container input[type="submit"]:hover {
            background: linear-gradient(90deg, #0056b3 0%, #1e3c72 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .error {
            color: #e74c3c;
            background: #fff0f0;
            border: 1px solid #e0b4b4;
            border-radius: 6px;
            padding: 10px 0;
            margin-bottom: 18px;
            text-align: center;
            font-weight: 600;
            z-index: 2;
        }
        @media (max-width: 500px) {
            .login-container {
                width: 95vw;
                padding: 25px 8vw 20px 8vw;
            }
            .login-container h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>เข้าสู่ระบบ</h2>
        <?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
        <form action="login.php" method="post">
            <label for="username">รหัสนักศึกษา</label>
            <input type="text" id="username" name="username" required pattern="[0-9]{1,10}" maxlength="10" inputmode="numeric" title="กรุณากรอกตัวเลขไม่เกิน 10 หลัก" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
            <label for="password">รหัสผ่าน</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="เข้าสู่ระบบ">
        </form>
        <h3 style="text-align: center;">&</h3>
        <div style="text-align: center; font-size: 0.9rem; color: #555;">
            <p>หากคุณยังไม่มีบัญชีผู้ใช้ กรุณาติดต่อเจ้าหน้าที่</p>
            <p>หรือ <a href="register.php" style="color: #007bff; text-decoration: none;">สมัครสมาชิกที่นี่</a></p>
    </div>
</body>
</html>
