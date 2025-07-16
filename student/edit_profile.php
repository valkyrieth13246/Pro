<?php
session_start();
require_once '../condb.php';
require_once 'sidebar.php';

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
// ดึงข้อมูลผู้ใช้
$stmt = $conn->prepare("SELECT student_id, fullname, email, phone, profile_img, class FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $class = $_POST['class'];

    // อัปโหลดรูปโปรไฟล์ถ้ามี
    $profile_img = $user['profile_img'];
    if (!empty($_FILES['profile_img']['name'])) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $img_name = time() . "_" . basename($_FILES["profile_img"]["name"]);
        $target_file = $target_dir . $img_name;
        if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
            $profile_img = $target_file;
        }
    }

    // อัปเดตข้อมูล
    $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, phone=?, profile_img=?, class=? WHERE user_id=?");
    if ($stmt->execute([$fullname, $email, $phone, $profile_img, $class, $user_id])) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>Swal.fire({title: 'บันทึกข้อมูลสำเร็จ', icon: 'success'}).then(()=>{window.location='edit_profile.php';});</script>";
        exit;
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>Swal.fire({title: 'เกิดข้อผิดพลาด', icon: 'error'});</script>";
    }
    // ดึงข้อมูลใหม่หลังอัปเดต
    $stmt = $conn->prepare("SELECT student_id, fullname, email, phone, profile_img, class FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขโปรไฟล์</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #e6e9e6ff; margin: 0; }
        .container {
            max-width: 420px;
            margin: 60px auto;
            padding: 30px 25px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .form-title {
            text-align: center;
            color: #3c8dbc;
            margin-bottom: 25px;
            font-size: 1.3em;
            letter-spacing: 1px;
        }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; color: #333; font-weight: 500; }
        input[type="text"], input[type="email"] {
            width: 100%; padding: 10px; border: 1px solid #bfc9d1; border-radius: 5px; font-size: 1em;
        }
        input[type="file"] { margin-top: 6px; }
        .profile-img {
            display: block;
            width: 90px; height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #3c8dbc;
        }
        .profile-img-wrapper {
            position: relative;
            width: 90px;
            height: 90px;
            margin: 0 auto 18px auto;
            display: block;
        }
        .profile-img-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.55);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1em;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            font-weight: 500;
            letter-spacing: 1px;
        }
        .profile-img-wrapper:hover .profile-img-overlay {
            opacity: 1;
            pointer-events: auto;
        }
        .save-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #3c8dbc 60%, #5bc0de 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            margin-top: 16px;
            box-shadow: 0 2px 8px rgba(60,139,188,0.10);
            transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
        }
        .save-btn:hover, .save-btn:focus {
            background: linear-gradient(90deg, #337ab7 60%, #31b0d5 100%);
            box-shadow: 0 4px 16px rgba(60,139,188,0.18);
            transform: translateY(-2px) scale(1.02);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-title">แก้ไขโปรไฟล์</div>
        <form method="post" enctype="multipart/form-data">
            <?php if (!empty($user['profile_img'])): ?>
            <div class="profile-img-wrapper">
                <img src="<?= htmlspecialchars($user['profile_img']) ?>" class="profile-img" alt="Profile Image" id="profileImg" style="cursor:pointer;">
                <div class="profile-img-overlay">เปลี่ยนรูป</div>
            </div>
            <?php endif; ?>
            <input type="file" id="profile_img" name="profile_img" accept="image/*" style="display:none;">
            <div class="form-group">
                <label>รหัสนักศึกษา</label>
                <input type="text" value="<?= htmlspecialchars($user['student_id']) ?>" disabled>
            </div>
            <div class="form-group">
                <label for="fullname">ชื่อ-นามสกุล</label>
                <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">อีเมล</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">เบอร์โทรศัพท์</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
            </div>
            <div class="form-group">
                <label for="class">ชั้นปี/ห้อง</label>
                <input type="text" id="class" name="class" value="<?= htmlspecialchars($user['class']) ?>">
            </div>
            <!-- input file ถูกย้ายไปไว้ด้านบนและซ่อนแล้ว -->
            <button type="button" id="saveBtn" class="save-btn">บันทึก</button>
        </form>
    </div>
    <script>
    const profileImg = document.getElementById('profileImg');
    const fileInput = document.getElementById('profile_img');
    const saveBtn = document.getElementById('saveBtn');
    const form = document.querySelector('form');
    const profileImgOverlay = document.querySelector('.profile-img-overlay');
    function handleProfileImgClick() {
        Swal.fire({
            title: 'ต้องการเปลี่ยนรูปโปรไฟล์ไหม?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fileInput.click();
            }
        });
    }
    if(profileImg && fileInput) {
        profileImg.addEventListener('click', handleProfileImgClick);
        if(profileImgOverlay) {
            profileImgOverlay.addEventListener('click', handleProfileImgClick);
        }
        fileInput.addEventListener('change', function() {
            if(fileInput.files.length > 0) {
                form.submit();
            }
        });
    }
    if(saveBtn && form) {
        saveBtn.addEventListener('click', function(e) {
            Swal.fire({
                title: 'ต้องการบันทึกข้อมูลหรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
    </script>
</body>