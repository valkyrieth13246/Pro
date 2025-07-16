<?php
require 'condb.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $email = null;
    $phone = null;
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : '';

    $level = 2; // สมมติ 2 = student, สามารถปรับตามระบบ
    $profile_img = ""; // ยังไม่รองรับอัปโหลดภาพ
    $project_id = null;
    $group_id = null;
    $class = null; // ยังไม่รองรับการเลือกคลาส

    // ตรวจสอบรหัสผ่านตรงกันหรือไม่
    if ($password !== $confirm_password) {
        $result = "password_not_match";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            // ตรวจสอบว่าอีเมลซ้ำหรือไม่
            $sqlCheck = "SELECT COUNT(*) FROM users WHERE email = ?";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bindParam(1, $email);
            $stmtCheck->execute();
            $emailExists = $stmtCheck->fetchColumn();

            if ($emailExists > 0) {
                $result = "email_exists";
            } else {
                $sql = "INSERT INTO users (student_id, fullname, password, level, email, project_id, group_id, profile_img) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $student_id, PDO::PARAM_STR);
                $stmt->bindParam(2, $fullname, PDO::PARAM_STR);
                $stmt->bindParam(3, $passwordHash, PDO::PARAM_STR);
                $stmt->bindParam(4, $level, PDO::PARAM_INT);
                $stmt->bindParam(5, $email, PDO::PARAM_STR);
                $stmt->bindParam(6, $project_id, PDO::PARAM_STR);
                $stmt->bindParam(7, $group_id, PDO::PARAM_STR);
                $stmt->bindParam(8, $profile_img, PDO::PARAM_STR);
                $stmt->execute();
                $result = "success";
            }
        } catch (Exception $e) {
            $result = "error";
        }
    }

    // SweetAlert2
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    if ($result === "success") {
        echo '<script>
            setTimeout(function() {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "สมัครสมาชิกสำเร็จ",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location = "login.php";
                });
            }, 10);
        </script>';
    } elseif ($result === "email_exists") {
        echo '<script>
            setTimeout(function() {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "อีเมลนี้ถูกใช้งานแล้ว",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location = "register.php";
                });
            }, 10);
        </script>';
    } elseif ($result === "password_not_match") {
        echo '<script>
            setTimeout(function() {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "รหัสผ่านไม่ตรงกัน",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location = "register.php";
                });
            }, 10);
        </script>';
    } else {
        echo '<script>
            setTimeout(function() {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "เกิดข้อผิดพลาด",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location = "register.php";
                });
            }, 10);
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Kanit', Arial, sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-container {
            width: 420px;
            padding: 40px 35px 30px 35px;
            background: rgba(255,255,255,0.95);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .register-container::before {
            content: '';
            position: absolute;
            top: -60px; left: -60px;
            width: 180px; height: 180px;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 50%;
            opacity: 0.25;
            z-index: 0;
        }
        .register-container h2 {
            text-align: center;
            font-size: 2.2rem;
            font-weight: 700;
            color: #2575fc;
            margin-bottom: 28px;
            letter-spacing: 1px;
            z-index: 1;
            position: relative;
        }
        .register-container form {
            z-index: 1;
            position: relative;
        }
        .input-group {
            position: relative;
            margin-bottom: 22px;
        }
        .input-group label {
            display: block;
            margin-bottom: 7px;
            color: #444;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .input-group i {
            position: absolute;
            top: 38px;
            left: 12px;
            color: #2575fc;
            font-size: 1.1rem;
        }
        .register-container input[type="text"],
        .register-container input[type="password"],
        .register-container input[type="email"] {
            width: 100%;
            padding: 12px 12px 12px 38px;
            border: 1.5px solid #d1d9e6;
            border-radius: 6px;
            background: #f7faff;
            font-size: 1rem;
            transition: border 0.2s;
            outline: none;
        }
        .register-container input[type="text"]:focus,
        .register-container input[type="password"]:focus,
        .register-container input[type="email"]:focus {
            border: 1.5px solid #2575fc;
            background: #fff;
        }
        .register-container input[type="submit"] {
            width: 100%;
            padding: 13px;
            background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(38, 135, 255, 0.10);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .register-container input[type="submit"]:hover {
            background: linear-gradient(90deg, #2575fc 0%, #6a11cb 100%);
            box-shadow: 0 6px 24px rgba(38, 135, 255, 0.18);
        }
        @media (max-width: 500px) {
            .register-container {
                width: 98vw;
                padding: 20px 5vw;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2><i class="fa-solid fa-user-plus"></i> สมัครสมาชิก</h2>
        <form action="register.php" method="post">
            <div class="input-group">
                <label for="student_id">รหัสนักศึกษา</label>
                <i class="fa-solid fa-id-card"></i>
                <input type="text" id="student_id" name="student_id" required pattern="[0-9]{1,10}" maxlength="10" inputmode="numeric" title="กรุณากรอกตัวเลขไม่เกิน 10 หลัก" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
            </div>
            <div class="input-group">
                <label for="fullname">ชื่อ-นามสกุล</label>
                <i class="fa-solid fa-user"></i>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <!-- <div class="input-group">
                <label for="email">อีเมล</label>
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name="email" required>
            </div> -->
            <!-- <div class="input-group">
                <label for="phone">เบอร์โทรศัพท์</label>
                <i class="fa-solid fa-phone"></i>
                <input type="text" id="phone" name="phone" required>
            </div> -->
            <div class="input-group">
                <label for="password">รหัสผ่าน</label>
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="password" name="password" required pattern=".{6,}" title="รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร">
            </div>
            <div class="input-group">
                <label for="confirm_password">ยืนยันรหัสผ่าน</label>
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <input type="submit" value="สมัครสมาชิก">
        </form>
        <p style="text-align: center; margin-top: 20px;">
            <a href="login.php" style="color: #2575fc; text-decoration: none;">มีบัญชีผู้ใช้แล้ว? เข้าสู่ระบบ</a> 
    </div>
</body>
</html>