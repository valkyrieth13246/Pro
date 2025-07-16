<?php
require_once '../condb.php';
require_once 'sidebar.php';
session_start();

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit();
}

// ดึง group_id ของผู้ใช้
$stmt = $conn->prepare("SELECT group_id FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$group_id = $stmt->fetchColumn();

// ดึงรายชื่ออาจารย์ (level = 1)
$stmt = $conn->prepare("SELECT user_id, fullname FROM users WHERE level = 1");
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงโครงงานของกลุ่มตัวเอง
$projects = [];
if ($group_id) {
    $stmt = $conn->prepare("SELECT pj_id, project_name, project_desc FROM project WHERE group1_id = ?");
    $stmt->execute([$group_id]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// รับค่าจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = $_POST['teacher_id'];
    $pj_id = $_POST['pj_id'];
    $project_desc = $_POST['project_desc'];

    // ตัวอย่าง: แสดงผลข้อมูลที่รับมา
    echo "<script>alert('ส่งคำขอเรียบร้อยแล้ว'); window.location='request_for_teacher.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เลือกอาจารย์ที่ปรึกษา</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #e6e9e6ff;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 420px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.10);
            padding: 40px 35px 35px 35px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-title {
            text-align: center;
            color: #3c8dbc;
            margin-bottom: 25px;
            font-size: 1.7em;
            letter-spacing: 1px;
        }
        .form-group {
            margin-bottom: 22px;
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #bfc9d1;
            border-radius: 5px;
            font-size: 1em;
        }
        textarea {
            min-height: 70px;
            resize: vertical;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #3c8dbc;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.15em;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background: #337ab7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-title">เลือกอาจารย์ที่ปรึกษา</div>
        <form method="post" action="">
            <div class="form-group">
                <label for="teacher_id">เลือกชื่ออาจารย์</label>
                <select name="teacher_id" id="teacher_id" required>
                    <option value="">-- กรุณาเลือกอาจารย์ --</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= htmlspecialchars($teacher['user_id']) ?>">
                            <?= htmlspecialchars($teacher['fullname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="pj_id">เลือกชื่อโครงงานของกลุ่ม</label>
                <select name="pj_id" id="pj_id" required>
                    <option value="">-- กรุณาเลือกโครงงาน --</option>
                    <?php foreach ($projects as $pj): ?>
                        <option value="<?= htmlspecialchars($pj['pj_id']) ?>">
                            <?= htmlspecialchars($pj['project_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="project_desc">คำชี้แจง</label>
                <textarea id="project_desc" name="project_desc" placeholder="กรอกคำชี้แจงเพิ่มเติม"></textarea>
            </div>
            <input type="submit" value="ส่งคำขอ">
        </form>
    </div>
</body>
</html>