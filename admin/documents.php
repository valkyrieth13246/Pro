
<?php
include_once '../condb.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}
include_once 'sidebar.php';

$sql = "SELECT * FROM project";
$stmt = $conn->prepare($sql);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการเอกสารโครงงาน</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 30px; }
        h1 { color: #3c8dbc; text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #e0e0e0; text-align: left; }
        th { background: #3c8dbc; color: #fff; }
        tr:hover { background: #f1f7ff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>รายการเอกสารโครงงานทั้งหมด</h1>
        <table>
            <thead>
                <tr>
                    <th>รหัสโครงงาน</th>
                    <th>ชื่อโครงงาน</th>
                    <th>รายละเอียด</th>
                    <th>ไฟล์</th>
                    <th>กลุ่ม</th>
                    <th>วันที่ส่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $pj): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pj['pj_id']); ?></td>
                    <td><?php echo htmlspecialchars($pj['project_name']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($pj['project_desc'])); ?></td>
                    <td>
                        <?php if (!empty($pj['project_file'])): ?>
                            <a href="../upload<?php echo urlencode($pj['project_file']); ?>" target="_blank">ดาวน์โหลด</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($pj['group1_id']); ?></td>
                    <td>
                        <?php
                        $thai_months = [
                            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
                            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
                            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                        ];
                        if (!empty($pj['datetime'])) {
                            $dt = DateTime::createFromFormat('Y-m-d H:i:s', $pj['datetime']);
                            if ($dt) {
                                $th_year = (int)$dt->format('Y') + 543;
                                $th_month = (int)$dt->format('m');
                                $th_day = (int)$dt->format('d');
                                $th_month_name = $thai_months[$th_month];
                                echo $th_day . ' ' . $th_month_name . ' ' . $th_year;
                            } else {
                                echo htmlspecialchars($pj['datetime']);
                            }
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
