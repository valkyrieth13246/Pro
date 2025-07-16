<?php
?>
<div class="sidebar">
    <h2><span style="font-size:1.5em;vertical-align:middle;"></span> 🅰️Admin Menu</h2>
    <ul>
        <li><a href="index.php">🏡หน้าหลัก</a></li>
        <li><a href="alluser.php">👥รายชื่อผู้ใช้ทั้งหมด</a></li>
        <li><a href="documents.php">🧾เอกสารทัั้งหมด</a></li>
        <li><a href="group.php">🎪กลุ่มทั้งหมด</a></li>
        <li><a href="../login.php">🚪ออกจากระบบ</a></li>
    </ul>
</div>
<style>
@import url('https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap');
.sidebar {
    width: 240px;
    background: #7a1b18;
    background: linear-gradient(135deg, #98221f 70%, #f0000cff 100%);
    color: #fff;
    height: 100vh;
    position: fixed;
    left: 0; top: 0;
    padding-top: 30px;
    box-shadow: 2px 0 18px 0 rgba(60, 80, 120, 0.15);
    font-family: 'Kanit', 'Segoe UI', Arial, sans-serif;
    z-index: 1000;
}
.sidebar h2 {
    text-align: center;
    margin-bottom: 32px;
    font-size: 1.5em;
    letter-spacing: 1px;
    font-weight: 700;
    color: #fff;
    text-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar ul li {
    padding: 0;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    transition: background 0.2s;
}
.sidebar ul li:last-child {
    border-bottom: none;
}
.sidebar ul li a {
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 16px 32px;
    font-size: 1.08em;
    font-weight: 500;
    letter-spacing: 0.5px;
    border-radius: 8px 0 0 8px;
    transition: background 0.2s, padding-left 0.2s, color 0.2s;
    position: relative;
}
.sidebar ul li a:hover {
    background: rgba(255,255,255,0.13);
    color: #ffe082;
    padding-left: 40px;
    box-shadow: 2px 4px 16px 0 rgba(36,90,141,0.08);
}
.sidebar ul li a::before {
    content: '';
    display: block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #ffe082;
    margin-right: 12px;
    opacity: 0;
    transition: opacity 0.2s;
}
.sidebar ul li a:hover::before {
    opacity: 1;
}
@media (max-width: 700px) {
    .sidebar {
        width: 100vw;
        height: auto;
        position: relative;
        border-radius: 0 0 18px 18px;
        box-shadow: 0 2px 12px 0 rgba(60, 80, 120, 0.10);
    }
    .sidebar ul li a {
        padding: 14px 18px;
        font-size: 1em;
    }
}
</style>
