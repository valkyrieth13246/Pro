<?php
session_start();

// ล้าง session ทั้งหมด
$_SESSION = [];
session_unset();
session_destroy();

// ส่งกลับไปยังหน้า login
header("Location: login.php");
exit();
?>
