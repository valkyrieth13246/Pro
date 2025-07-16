<?php
include_once '../condb.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}
include_once 'sidebar.php';

// ดึงข้อมูลกลุ่ม
$sql = "SELECT * FROM `group`";
$stmt = $conn->prepare($sql);
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลผู้ใช้ทั้งหมด
$sql2 = "SELECT * FROM users";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$users = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// จัดกลุ่มผู้ใช้ตาม group_id
$users_by_group = [];
foreach ($users as $user) {
    $gid = $user['group_id'];
    if (!isset($users_by_group[$gid])) {
        $users_by_group[$gid] = [];
    }
    $users_by_group[$gid][] = $user;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายชื่อกลุ่ม</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 30px; }
        h1 { color: #3c8dbc; text-align: center; margin-bottom: 30px; }
        .group-box { border: 1px solid #e0e0e0; border-radius: 8px; margin-bottom: 30px; padding: 20px; background: #f9f9f9; }
        .group-title { font-size: 18px; color: #337ab7; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px 10px; border-bottom: 1px solid #e0e0e0; text-align: left; }
        th { background: #3c8dbc; color: #fff; }
        tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>รายชื่อกลุ่มและสมาชิก</h1>
        <?php foreach ($groups as $group): ?>
            <div class="group-box">
                <div class="group-title">
                    กลุ่ม: <?php echo htmlspecialchars($group['groupname']); ?> (g_id: <?php echo $group['g_id']; ?>)
                </div>
                <div>วันที่สร้าง: <?php 
                    $dt = DateTime::createFromFormat('Y-m-d', $group['create']);
                    if ($dt) {
                        $th_year = (int)$dt->format('Y') + 543;
                        $th_months = [1=>'มกราคม',2=>'กุมภาพันธ์',3=>'มีนาคม',4=>'เมษายน',5=>'พฤษภาคม',6=>'มิถุนายน',7=>'กรกฎาคม',8=>'สิงหาคม',9=>'กันยายน',10=>'ตุลาคม',11=>'พฤศจิกายน',12=>'ธันวาคม'];
                        $th_month = $th_months[(int)$dt->format('m')];
                        echo $dt->format('d ') . $th_month . ' ' . $th_year;
                    } else {
                        echo htmlspecialchars($group['create']);
                    }
                ?></div>
                <div>สถานะ: <?php echo htmlspecialchars($group['status']); ?></div>
                <div style="margin-top:10px;">
                    <b>สมาชิกในกลุ่ม:</b>
                    <table>
                        <thead>
                            <tr>
                                <th>รหัสผู้ใช้</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>อีเมล</th>
                                <th>เบอร์โทร</th>
                                <th>ชั้น</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($users_by_group[$group['g_id']])): ?>
                            <?php foreach ($users_by_group[$group['g_id']] as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($user['class']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="color:#888;">ไม่มีสมาชิกในกลุ่มนี้</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
