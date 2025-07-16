<?php
session_start();
require_once '../condb.php';

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit();
}

$error = '';
$success = '';


// ตรวจสอบ group_id ของผู้ใช้ที่ login
$stmt = $conn->prepare("SELECT group_id FROM users WHERE user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$my_group_id = $stmt->fetchColumn();

if ($my_group_id && $my_group_id !== '' && $my_group_id !== null) {
    // ถ้ามี group_id แล้ว ดึงรายชื่อสมาชิกในกลุ่มเดียวกัน
    $stmt = $conn->prepare("SELECT fullname, student_id FROM users WHERE group_id = :gid");
    $stmt->execute([':gid' => $my_group_id]);
    $my_group_members = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // ดึงรายชื่อนักศึกษาที่ยังไม่อยู่ในกลุ่ม (group_id เป็น NULL หรือว่าง)
    $stmt = $conn->prepare("SELECT user_id, fullname, student_id FROM users WHERE level = 2 AND (group_id IS NULL OR group_id = '')");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (!$my_group_id || $my_group_id === '' || $my_group_id === null) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $groupname = trim($_POST['groupname']);
        $members = isset($_POST['members']) ? $_POST['members'] : [];
        if (empty($groupname)) {
            $error = 'กรุณากรอกชื่อกลุ่ม';
        } elseif (count($members) < 1 || count($members) > 4) {
            $error = 'เลือกสมาชิกได้ 1-4 คน';
        } else {
            try {
                $conn->beginTransaction();
                // เพิ่มกลุ่มใหม่ (เปลี่ยนชื่อฟิลด์ create เป็น `create` เพราะเป็น reserved word ต้องใส่ backtick)
                $stmt = $conn->prepare("INSERT INTO `group` (groupname, `create`, status) VALUES (:groupname, :created, 'active')");
                $stmt->execute([
                    ':groupname' => $groupname,
                    ':created' => date('Y-m-d')
                ]);
                $g_id = $conn->lastInsertId();
                // อัปเดต group_id ให้สมาชิก
                $stmt = $conn->prepare("UPDATE users SET group_id = :g_id WHERE user_id = :user_id");
                foreach ($members as $uid) {
                    $stmt->execute([':g_id' => $g_id, ':user_id' => $uid]);
                }
                $conn->commit();
                $success = 'สร้างกลุ่มสำเร็จ!';
            } catch (Exception $e) {
                $conn->rollBack();
                $error = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
            }
        }
    }
}
?>
<?php require_once 'sidebar.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สร้างกลุ่ม</title>
    <link rel="stylesheet" href="../../style/css/style.css">
    <style>
        body {
            background: #e6e9e6ff;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-content {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 40px 30px;
            /* จัดให้อยู่กลาง */
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 { color: #3c8dbc; }
        .form-group { margin-bottom: 22px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; }
        .member-list { margin-top: 10px; }
        .member-list label { font-weight: normal; }
        .btn { background: #3c8dbc; color: #fff; border: none; padding: 10px 28px; border-radius: 4px; font-size: 1em; cursor: pointer; }
        .btn:hover { background: #245a8d; }
        .alert { padding: 10px 18px; border-radius: 4px; margin-bottom: 18px; }
        .alert-danger { background: #ffe6e6; color: #a00; }
        .alert-success { background: #e6ffe6; color: #0a0; }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>สร้างกลุ่ม</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px; text-align:center; max-width:350px; margin-left:auto; margin-right:auto;"> <?= $error ?> </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success" style="margin-bottom: 20px; text-align:center; max-width:350px; margin-left:auto; margin-right:auto;"> <?= $success ?> </div>
        <?php endif; ?>
        <?php if ($my_group_id && $my_group_id !== '' && $my_group_id !== null): ?>
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center;">
                <div class="alert alert-danger" style="max-width: 350px; text-align: center; margin-bottom: 24px;">คุณอยู่ในกลุ่มแล้ว ไม่สามารถสร้างกลุ่มใหม่ได้</div>
                <div style="width: 100%; max-width: 350px; background: #f7fafd; border-radius: 8px; padding: 18px 22px; box-shadow: 0 1px 6px rgba(60,140,188,0.07);">
                    <label style="font-weight: bold; color: #2563eb; margin-bottom: 10px;">สมาชิกในกลุ่มของคุณ</label>
                    <ul style="padding-left: 22px; margin: 0;">
                        <?php foreach ($my_group_members as $mem): ?>
                            <li><?= htmlspecialchars($mem['fullname']) ?> (<?= htmlspecialchars($mem['student_id']) ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <form method="post" style="display: flex; flex-direction: column; align-items: center;">
                <div class="form-group" style="width: 100%; max-width: 350px;">
                    <label for="groupname">ชื่อกลุ่ม</label>
                    <input type="text" name="groupname" id="groupname" required>
                </div>
                <div class="form-group" style="width: 100%; max-width: 350px;">
                    <label>เลือกสมาชิก (สูงสุด 4 คน)</label>
                    <div class="member-list">
                        <?php foreach ($students as $stu): ?>
                            <label style="display: flex; align-items: center; gap: 7px; margin-bottom: 4px; background: #f3f7fa; border-radius: 5px; padding: 7px 12px; cursor: pointer;">
                                <input type="checkbox" name="members[]" value="<?= $stu['user_id'] ?>"> <?= htmlspecialchars($stu['fullname']) ?> (<?= htmlspecialchars($stu['student_id']) ?>)
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" class="btn" style="margin-top: 10px; width: 100%; max-width: 350px;">สร้างกลุ่ม</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
