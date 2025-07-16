<?php
session_start();
require_once '../condb.php';
// ดึง user_id จาก session
$user_id = $_SESSION['user_id'] ?? null;
$group_id = null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT group_id FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $group_id = $stmt->fetchColumn();
}

if (!$group_id) {
    echo "<script>alert('ไม่พบกลุ่มของคุณ กรุณาเข้าสู่ระบบใหม่หรือเข้าร่วมกลุ่มก่อน'); window.location='../index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = $_POST['project_name'];
    $project_desc = $_POST['project_desc'];

    $target_dir = "../../upload/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_tmp = $_FILES["project_file"]["tmp_name"];
    $original_name = basename($_FILES["project_file"]["name"]);
    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'docx'];
    if (!in_array($ext, $allowed)) {
        echo "<script>alert('อนุญาตเฉพาะไฟล์ PDF หรือ DOCX เท่านั้น'); window.location='index.php';</script>";
        exit;
    }
    $new_name = time() . "_" . $original_name;
    $target_file = $target_dir . $new_name;

    if (move_uploaded_file($file_tmp, $target_file)) {
        $db_path = "../upload/" . $new_name;
        $stmt = $conn->prepare("INSERT INTO project (project_name, project_desc, project_file, group1_id) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$project_name, $project_desc, $db_path, $group_id])) {
            echo "<script>alert('บันทึกสำเร็จ'); window.location='index.php';</script>";
            exit;
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกฐานข้อมูล'); window.location='index.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('อัปโหลดไฟล์ไม่สำเร็จ'); window.location='index.php';</script>";
        exit;
    }
}
?>
<?php require_once 'sidebar.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สร้างโครงงาน</title>
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
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #bfc9d1;
            border-radius: 5px;
            font-size: 1em;
            resize: vertical;
        }
        textarea {
            min-height: 90px;
        }
        input[type="file"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #bfc9d1;
            font-size: 1em;
            background: #f8fafc;
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
        <div class="form-title">ชื่อโครงงาน</div>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="project_name">ชื่อโครงงาน</label>
                <input type="text" id="project_name" name="project_name" placeholder="กรอกชื่อโครงงาน" required>
            </div>
            <div class="form-group">
                <label for="project_file">ไฟล์โครงงาน</label>
                <input type="file" name="project_file" id="project_file" accept=".pdf,.docx" required>
            </div>
            <div class="form-group">
                <label for="project_desc">คำชี้แจง</label>
                <textarea id="project_desc" name="project_desc" placeholder="กรอกคำชี้แจงเกี่ยวกับโครงงาน" required></textarea>
            </div>
            <input type="submit" value="บันทึก">
        </form>
    </div>
</body>
</html>
