<?php
include_once '../condb.php';
if (!isset($_GET['id'])) {
    echo "ไม่พบข้อมูลผู้ใช้";
    exit;
}
$user_id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
if ($stmt->execute([$user_id])) {
    echo "success";
} else {
    echo "เกิดข้อผิดพลาด";
}