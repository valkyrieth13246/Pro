<?php
include_once '../condb.php';

// --- Inline Delete Handler ---
if (isset($_GET['delete_id'])) {
    $user_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    if ($stmt->execute([$user_id])) {
        // ส่ง header เฉพาะผลลัพธ์ ไม่ต้องมี HTML อื่น
        header('Content-Type: text/plain');
        echo "success";
    } else {
        header('Content-Type: text/plain');
        echo "เกิดข้อผิดพลาด";
    }
    exit;
}

include_once 'sidebar.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}



$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$teachers = array_filter($users, function ($u) {
    return $u['level'] == 1;
});
$students = array_filter($users, function ($u) {
    return $u['level'] == 2;
});
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>รายการผู้ใช้งาน</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .container {
            max-width: 1400px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        h1 {
            color: #3c8dbc;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }

        th {
            background: #3c8dbc;
            color: #fff;
        }

        tr:hover {
            background: #f1f7ff;
        }

        img {
            max-width: 50px;
            border-radius: 50%;
        }

        .actions a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 2px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }

        .view-btn {
            background-color: #17a2b8;
        }

        .edit-btn {
            background-color: #ffc107;
            color: #212529;
        }

        .del-btn {
            background-color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>รายการอาจารย์ (Teacher)</h1>
        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>อีเมล</th>
                    <th>เบอร์โทร</th>
                    <!-- <th>วันที่สร้าง</th>
                    <th>รูปโปรไฟล์</th> -->
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach ($teachers as $user): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($user['fullname']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <!-- <td><?= htmlspecialchars($user['create_date']) ?></td>
                    <td>
                        <?php if (!empty($user['profile_img'])): ?>
                            <img src="../student/profile_img/<?= htmlspecialchars($user['profile_img']) ?>" alt="Profile">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td> -->
                        <td class="actions">
                            <a href="#" class="view-btn" onclick='showUserModal(<?= json_encode($user, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>); return false;'>View</a>
                            <a href="#" class="edit-btn" onclick='showEditModal(<?= json_encode($user, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>); return false;'>Edit</a>
                            <a href="#" class="del-btn" onclick="deleteUser(<?= $user['user_id'] ?>, this); return false;">Del</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h1 style="margin-top:50px;">รายการนักศึกษา (Student)</h1>
        <table>
            <thead>
                <tr>
                    <th>รหัสผู้ใช้</th>
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>อีเมล</th>
                    <th>เบอร์โทร</th>
                    <th>กลุ่ม</th>
                    <!-- <th>วันที่สร้าง</th>
                    <th>รูปโปรไฟล์</th> -->
                    <th>ชั้น</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach ($students as $user): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($user['student_id']) ?></td>
                        <td><?= htmlspecialchars($user['fullname']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= htmlspecialchars($user['group_id']) ?></td>
                        <!-- <td><?= htmlspecialchars($user['create_date']) ?></td>
                    <td>
                        <?php if (!empty($user['profile_img'])): ?>
                            <img src="../student/profile_img/<?= htmlspecialchars($user['profile_img']) ?>" alt="Profile">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td> -->
                        <td><?= htmlspecialchars($user['class']) ?></td>
                        <td class="actions">
                            <a href="#" class="view-btn" onclick='showUserModal(<?= json_encode($user, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>); return false;'>View</a>
                            <a href="#" class="edit-btn" onclick='showEditModal(<?= json_encode($user, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>); return false;'>Edit</a>
                            <a href="#" class="del-btn" onclick="deleteUser(<?= $user['user_id'] ?>, this); return false;">Del</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- User Modal Popup -->
    <!-- Edit User Modal Popup -->
    <div id="editModal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);">
        <div style="background:#fff;max-width:420px;margin:60px auto;padding:30px 20px;border-radius:10px;position:relative;box-shadow:0 2px 8px rgba(0,0,0,0.2);">
            <span onclick="closeEditModal()" style="position:absolute;top:10px;right:18px;font-size:22px;cursor:pointer;">&times;</span>
            <h2 style="color:#3c8dbc;margin-top:0;">แก้ไขข้อมูลผู้ใช้</h2>
            <form id="editUserForm" onsubmit="submitEditUser(event)">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div style="margin-bottom:10px;">
                    <label>ชื่อ-นามสกุล</label>
                    <input type="text" name="fullname" id="edit_fullname" style="width:100%;padding:6px;">
                </div>
                <div style="margin-bottom:10px;">
                    <label>อีเมล</label>
                    <input type="email" name="email" id="edit_email" style="width:100%;padding:6px;">
                </div>
                <div style="margin-bottom:10px;">
                    <label>เบอร์โทร</label>
                    <input type="text" name="phone" id="edit_phone" style="width:100%;padding:6px;">
                </div>
                <div style="margin-bottom:10px;" id="edit_student_id_row">
                    <label>รหัสนักศึกษา</label>
                    <input type="text" name="student_id" id="edit_student_id" style="width:100%;padding:6px;">
                </div>
                <div style="margin-bottom:10px;" id="edit_group_id_row">
                    <label>กลุ่ม</label>
                    <input type="text" name="group_id" id="edit_group_id" style="width:100%;padding:6px;">
                </div>
                <div style="margin-bottom:10px;" id="edit_class_row">
                    <label>ชั้น</label>
                    <input type="text" name="class" id="edit_class" style="width:100%;padding:6px;">
                </div>
                <button type="submit" style="background:#3c8dbc;color:#fff;padding:8px 18px;border:none;border-radius:5px;">บันทึก</button>
            </form>
            <div id="editUserMsg" style="color:#d00;margin-top:10px;"></div>
        </div>
    </div>
    <div id="userModal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);">
        <div style="background:#fff;max-width:400px;margin:60px auto;padding:30px 20px;border-radius:10px;position:relative;box-shadow:0 2px 8px rgba(0,0,0,0.2);">
            <span onclick="closeUserModal()" style="position:absolute;top:10px;right:18px;font-size:22px;cursor:pointer;">&times;</span>
            <h2 style="color:#3c8dbc;margin-top:0;">ข้อมูลผู้ใช้</h2>
            <div id="modalContent">
                <!-- user info will be injected here -->
            </div>
        </div>
    </div>
    <script>
        function showUserModal(user) {
            let html = '<table style="width:100%;font-size:16px;">';
            if (user.user_id) html += `<tr><td><b>รหัสผู้ใช้</b></td><td>${user.user_id}</td></tr>`;
            if (user.student_id) html += `<tr><td><b>รหัสนักศึกษา</b></td><td>${user.student_id}</td></tr>`;
            if (user.fullname) html += `<tr><td><b>ชื่อ-นามสกุล</b></td><td>${user.fullname}</td></tr>`;
            if (user.email) html += `<tr><td><b>อีเมล</b></td><td>${user.email}</td></tr>`;
            if (user.phone) html += `<tr><td><b>เบอร์โทร</b></td><td>${user.phone}</td></tr>`;
            if (user.group_id) html += `<tr><td><b>กลุ่ม</b></td><td>${user.group_id}</td></tr>`;
            if (user.class) html += `<tr><td><b>ชั้น</b></td><td>${user.class}</td></tr>`;
            if (user.level == 1) html += `<tr><td><b>สถานะ</b></td><td>อาจารย์</td></tr>`;
            if (user.level == 2) html += `<tr><td><b>สถานะ</b></td><td>นักศึกษา</td></tr>`;
            if (user.create_date) html += `<tr><td><b>วันที่สร้าง</b></td><td>${user.create_date}</td></tr>`;
            if (user.profile_img) html += `<tr><td><b>รูปโปรไฟล์</b></td><td><img src="../student/profile_img/${user.profile_img}" style="max-width:60px;border-radius:50%;"></td></tr>`;
            html += '</table>';
            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('userModal').style.display = 'block';
        }

        function closeUserModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        function showEditModal(user) {
            document.getElementById('edit_user_id').value = user.user_id || '';
            document.getElementById('edit_fullname').value = user.fullname || '';
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_phone').value = user.phone || '';
            document.getElementById('edit_student_id').value = user.student_id || '';
            document.getElementById('edit_group_id').value = user.group_id || '';
            document.getElementById('edit_class').value = user.class || '';
            // Show/hide student fields
            if (user.level == 2) {
                document.getElementById('edit_student_id_row').style.display = '';
                document.getElementById('edit_group_id_row').style.display = '';
                document.getElementById('edit_class_row').style.display = '';
            } else {
                document.getElementById('edit_student_id_row').style.display = 'none';
                document.getElementById('edit_group_id_row').style.display = 'none';
                document.getElementById('edit_class_row').style.display = 'none';
            }
            document.getElementById('editUserMsg').innerText = '';
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function submitEditUser(e) {
            e.preventDefault();
            const form = document.getElementById('editUserForm');
            const formData = new FormData(form);
            fetch('edit_user.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.text())
                .then(data => {
                    if (data.trim() === 'success') {
                        document.getElementById('editUserMsg').style.color = '#090';
                        document.getElementById('editUserMsg').innerText = 'บันทึกสำเร็จ';
                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    } else {
                        document.getElementById('editUserMsg').style.color = '#d00';
                        document.getElementById('editUserMsg').innerText = data;
                    }
                })
                .catch(() => {
                    document.getElementById('editUserMsg').style.color = '#d00';
                    document.getElementById('editUserMsg').innerText = 'เกิดข้อผิดพลาด';
                });
        }
        window.onclick = function(event) {
            var modal = document.getElementById('userModal');
            if (event.target == modal) modal.style.display = 'none';
            var editModal = document.getElementById('editModal');
            if (event.target == editModal) editModal.style.display = 'none';
        }
        // ลบผู้ใช้ด้วย SweetAlert2
        function deleteUser(user_id, el) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: 'คุณต้องการลบผู้ใช้นี้หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('alluser.php?delete_id=' + user_id, {
                            method: 'GET'
                        })
                        .then(res => res.text())
                        .then(data => {
                            const res = data.trim().toLowerCase();
                            if (res === 'success') {
                                Swal.fire({
                                    title: 'ผิดพลาด',
                                    text: data,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                }).then(() => { location.reload(); });
                            } else {
                                Swal.fire({

                                    title: 'ลบแล้ว!',
                                    text: 'ผู้ใช้ถูกลบเรียบร้อย',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => { location.reload(); });
                            }
                        })
                        .catch(() => {
                            Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการลบ', 'error');
                        });
                }
            });
        }
    </script>
</body>

</html>