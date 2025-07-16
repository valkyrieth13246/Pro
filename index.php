<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>IT Digital Collections Dashboard</title>
    <link rel="stylesheet" href="/style/css/style.css">
</head>

<body>
    <div class="header">
        <div class="logo-area">
            <img src="/acces/img/IT_Logo.png" alt="logo" class="logo">
            <div class="logo-text">
                <span class="en">INFORMATION TECHNOLOGY</span>
                <span class="th">เทคโนโลยีสารสนเทศ</span>
            </div>
        </div>
        <div class="login-area">
            <a href="login.php">เข้าสู่ระบบ</a>
            <span>&nbsp; |</span>
            <a href="register.php">สมัครสมาชิก</a>
        </div>
    </div>
    <nav class="navbar">
        <ul>
            <li><a href="#">หน้าแรก</a></li>
            <li></li><li></li>
            <li><a href="#">เอกสาร ▼</a>
                <div class="dropdown">
                    <a href="#">ประเภทที่ 1</a>
                    <a href="#">ประเภทที่ 2</a>
                </div>
            </li>
            <li></li><li></li>
            <li><a href="#">เกี่ยวกับ ▼</a>
                <div class="dropdown">
                    <a href="#">เกี่ยวกับเรา</a>
                    <a href="#">ติดต่อ</a>
                </div>
            </li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </nav>
    <div class="linepink">

    </div>
    <div class="main-content">
        <div class="side-image" id="sideImage"></div>
        <div class="center-content">
            <h1>Information Technology ( IT ) Digital Collections</h1>
            <p>คลังสารสนเทศดิจิทัล สาขา เทคโนโลยีสารสนเทศ</p>
            <div class="stats">
                <div>
                    <div class="stat">38,8xx</div>
                    <div class="stat-label">ชิ้นงานดิจิทัล</div>
                </div>
                <div>
                    <div class="stat">151,242</div>
                    <div class="stat-label">รายการข้อมูล</div>
                </div>
                <div>
                    <div class="stat">7</div>
                    <div class="stat-label">ผู้ใช้งาน</div>
                </div>
                <div>
                    <div class="stat">xx</div>
                    <div class="stat-label">บริการ/หมวดหมู่</div>
                </div>
            </div>
        </div>
    </div>
    <section class="middle">
        <div class="middle-content">
            <h2>ข่าวสารและกิจกรรม</h2>
            <p>ติดตามข่าวสารและกิจกรรมล่าสุดจาก IT Digital Collections</p>
            <ul class="card-list single-row">
                <li class="card vertical-card">
                    <div class="card-img-area-vertical">
                        <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80"
                            alt="ข่าวสารที่ 1" class="card-img-vertical">
                    </div>
                    <div class="card-info-area-vertical">
                        <h3>ข่าวสารที่ 1</h3>
                        <p>รายละเอียดสั้น ๆ ของข่าวสารที่ 1</p>
                        <a href="#" class="card-link">อ่านต่อ</a>
                    </div>
                </li>
                <li class="card vertical-card">
                    <div class="card-img-area-vertical">
                        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80"
                            alt="ข่าวสารที่ 2" class="card-img-vertical">
                    </div>
                    <div class="card-info-area-vertical">
                        <h3>ข่าวสารที่ 2</h3>
                        <p>รายละเอียดสั้น ๆ ของข่าวสารที่ 2</p>
                        <a href="#" class="card-link">อ่านต่อ</a>
                    </div>
                </li>
                <li class="card vertical-card">
                    <div class="card-img-area-vertical">
                        <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=400&q=80"
                            alt="กิจกรรมที่ 1" class="card-img-vertical">
                    </div>
                    <div class="card-info-area-vertical">
                        <h3>กิจกรรมที่ 1</h3>
                        <p>รายละเอียดสั้น ๆ ของกิจกรรมที่ 1</p>
                        <a href="#" class="card-link">อ่านต่อ</a>
                    </div>
                </li>
                <li class="card vertical-card">
                    <div class="card-img-area-vertical">
                        <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=400&q=80"
                            alt="กิจกรรมที่ 2" class="card-img-vertical">
                    </div>
                    <div class="card-info-area-vertical">
                        <h3>กิจกรรมที่ 2</h3>
                        <p>รายละเอียดสั้น ๆ ของกิจกรรมที่ 2</p>
                        <a href="#" class="card-link">อ่านต่อ</a>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <footer>
        <p>© 2025 | Information Technology ( IT ) Digital Collections</p>
        <p>คลังสารสนเทศดิจิทัล สาขา เทคโนโลยีสารสนเทศ</p>
        <p>| Powered by NPRU Digital Team |</p>
    </footer>

    <script>
    // Array ของรูปภาพสำหรับ side-image
    const sideImages = [
      "https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80",
      "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTRq-WPUkDooNL5OOKAWfsDwlKKCyfj0Ev7_w&s&fit=crop&w=400&q=80",
      "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUlsbg7g09V-t9rhZXmVPAjXoiO6LTdHnikg&s&fit=crop&w=400&q=80"
    ];
    let sideIndex = 0;
    function showSideImage() {
      const el = document.getElementById('sideImage');
      el.style.backgroundImage = `url('${sideImages[sideIndex]}')`;
    }
    function autoSlideSideImage() {
      sideIndex++;
      if(sideIndex >= sideImages.length) sideIndex = 0;
      showSideImage();
    }
    window.addEventListener('DOMContentLoaded', function() {
      showSideImage();
      setInterval(autoSlideSideImage, 5000);
    });
    </script>
</body>

</html>