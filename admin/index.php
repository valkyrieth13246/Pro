<?php
include_once '../condb.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}
include_once 'sidebar.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #e6e9e6ff; margin: 0; }
        .main-content {
            margin-left: 220px;
            padding: 40px 30px;
        }
        .admin-header {
            background: #3c8dbc;
            color: #fff;
            padding: 20px 30px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 30px;
        }
        .dashboard-cards {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 30px;
            flex: 1 1 220px;
            min-width: 220px;
            text-align: center;
        }
        @media (max-width: 900px) {
            .dashboard-cards {
                gap: 16px;
            }
            .card {
                padding: 18px;
                min-width: 150px;
            }
        }
        @media (max-width: 600px) {
            .main-content {
                margin-left: 0;
                padding: 15px 5px;
            }
            .dashboard-cards {
                flex-direction: column;
                gap: 12px;
            }
            .card {
                min-width: 0;
                width: 100%;
                padding: 14px 6px;
            }
            .admin-header {
                padding: 12px 8px;
                font-size: 0.98em;
            }
        }
        .card h3 {
            margin: 0 0 10px 0;
            color: #3c8dbc;
        }
        .card .stat {
            font-size: 2.2em;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .logout-btn {
            display: block;
            margin: 30px auto 0 auto;
            padding: 10px 30px;
            background: #d9534f;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: #c9302c;
        }
    </style>
</head>
<body>

    <div class="main-content">
        <div class="admin-header">
            <h1>แดชบอร์ดผู้ดูแลระบบ</h1>
            <p>สวัสดี, <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'ผู้ดูแลระบบ'; ?>!</p>
        </div>
        <?php
        // ดึงจำนวนผู้ใช้ทั้งหมด
        $userCount = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
        // ดึงจำนวนเอกสาร (นับไฟล์ในโฟลเดอร์ upload/)
        $docCount = 0;
        $uploadDir = realpath(__DIR__ . '/../upload');
        if ($uploadDir && is_dir($uploadDir)) {
            $files = scandir($uploadDir);
            $docCount = count(array_filter($files, function($f) use ($uploadDir) {
                return is_file($uploadDir . '/' . $f) && $f !== '.' && $f !== '..';
            }));
        }
        // ดึงจำนวนกลุ่ม (นับ group_id ที่ไม่ว่างใน users)
        $groupCount = $conn->query("SELECT COUNT(DISTINCT group_id) FROM users WHERE group_id IS NOT NULL AND group_id != ''")->fetchColumn();
        ?>
        <div class="dashboard-cards">
            <div class="card">
                <div class="stat"><?php echo $userCount; ?></div>
                <a href="alluser.php"><h3>ผู้ใช้งานทั้งหมด</h3></a>
            </div>
            <div class="card">
                <div class="stat"><?php echo $groupCount; ?></div>
                <a href="group.php"><h3>กลุ่ม</h3></a>
            </div>
        </div>