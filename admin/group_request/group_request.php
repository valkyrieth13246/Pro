<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = $_POST['group_name'];
    $member_names = $_POST['member_names'];

    // ตัวอย่าง: แสดงผลข้อมูลที่รับมา
    echo "<h2>ข้อมูลที่ได้รับ</h2>";
    echo "<strong>ชื่อกลุ่ม:</strong> " . htmlspecialchars($group_name) . "<br>";
    echo "<strong>ชื่อสมาชิก:</strong> " . htmlspecialchars($member_names) . "<br>";

    // คุณสามารถเพิ่มโค้ดบันทึกลงฐานข้อมูลหรือแจ้งเตือน admin ได้ที่นี่
} else {
    echo "ไม่พบข้อมูลที่ส่งมา";
}
?>
