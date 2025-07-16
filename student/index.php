<?php
require_once 'sidebar.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #e6e9e6ff;
            margin: 0;
        }

        .navbar {
            /* background: #7a1b18; */
            background: #98221f;
            color: #fff;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            font-size: 1.4em;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .navbar .menu a {
            color: #fff;
            text-decoration: none;
            margin-left: 30px;
            font-size: 1em;
        }

        .navbar .menu a:hover {
            text-decoration: underline;
        }

        .main-content {
            max-width: 1200px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            padding: 40px 30px;
            margin-left: 270px;
            /* ระยะห่างเท่ากับ sidebar width */
            transition: margin-left 0.2s;
        }

        @media (max-width: 700px) {
            .main-content {
                margin-left: 0;
            }
        }

        .main-content h1 {
            color: #3c8dbc;
            margin-bottom: 10px;
        }

        .main-content p {
            color: #555;
            margin-bottom: 30px;
        }

        .dashboard-cards {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .card {
            background: #f4f6f9;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            padding: 25px;
            flex: 1 1 180px;
            min-width: 180px;
            text-align: center;
        }

        .card h3 {
            margin: 0 0 8px 0;
            color: #3c8dbc;
        }

        .card .stat {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 28px;
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
    <div class="navbar">
        <div class="logo">Student Panel</div>
        <div class="menu">
            <a href="index.php">หน้าหลัก</a>
            <a href="edit_profile.php">โปรไฟล์</a>
            <a href="../login.php">ออกจากระบบ</a>
        </div>
    </div>
    <div class="main-content">
        <h1>แดชบอร์ดนักศึกษา</h1>
        <p>สวัสดี, ยินดีต้อนรับเข้าสู่ระบบนักศึกษา คุณสามารถดูข้อมูลและจัดการเอกสารของคุณได้ที่นี่</p>
        <div class="dashboard-cards">
            <div class="card">
                <div class="stat">12</div>
                <h3>เอกสารของฉัน</h3>
            </div>
            <div class="card">
                <div class="stat">3</div>
                <h3>ข่าวสารล่าสุด</h3>
            </div>
            <div class="card">
                <div class="stat">1</div>
                <h3>หมวดหมู่ที่ติดตาม</h3>
            </div>

        </div>
    </div>

</body>

</html>