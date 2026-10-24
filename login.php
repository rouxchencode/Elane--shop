<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once("connections/conn_db.php");
require_once("php_lib.php");
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <?php require_once("header.php"); ?>
    <title>會員登入 - ELANE</title>
    
    <!-- 會員登入專用 CSS 設定（符合網站莫蘭迪粉/奶茶色風格） -->
    <style type="text/css">
        body {
            background-color: var(--color-bg-cream, #FAF8F5);
        }
        
        /* 登入卡片容器 */
        .mycard.mycard-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px 30px 40px;
            background-color: #ffffff;
            border: 1.5px solid var(--color-dark-brown, #3D2314);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(61, 35, 20, 0.08);
        }

        /* 頭像 / Logo 區塊 */
        .profile-img-card {
            width: 90px;
            height: 90px;
            margin: 0 auto 15px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--color-milk-pink, #F7D6DF);
            border-radius: 50%;
            color: var(--color-dark-brown, #3D2314);
            font-size: 36px;
        }

        .profile-name-card {
            font-size: 20px;
            font-weight: 700;
            text-align: center;
            color: var(--color-dark-brown, #3D2314);
            margin-bottom: 25px;
        }

        /* 表單輸入框風格 */
        .form-signin input[type="email"],
        .form-signin input[type="text"],
        .form-signin input[type="password"] {
            width: 100%;
            height: 45px;
            font-size: 15px;
            border-radius: 6px;
            border: 1px solid #e0c9cc;
            padding: 0 15px;
            margin-bottom: 15px;
            background-color: #fafafa;
            transition: all 0.2s ease;
        }

        .form-signin input:focus {
            outline: none;
            border-color: var(--color-dark-brown, #3D2314);
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(247, 214, 223, 0.5);
        }

        /* 登入按鈕風格 */
        .btn.btn-signin {
            width: 100%;
            height: 45px;
            font-size: 16px;
            font-weight: 700;
            color: var(--color-dark-brown, #3D2314);
            background-color: var(--color-milk-pink, #F7D6DF);
            border: 1px solid var(--color-dark-brown, #3D2314);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .btn.btn-signin:hover,
        .btn.btn-signin:focus {
            background-color: #f0ced1;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        /* 底部連結風格 */
        .other {
            text-align: center;
            font-size: 14px;
        }

        .other a {
            color: var(--color-dark-brown, #3D2314);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .other a:hover {
            color: #8c5861;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- 頂部公告欄與導覽列 -->
    <div class="announcement-bar">
        ✦ 全館滿 $1,500 即享免運｜新會員首購輸入【BABYCHILL】即享 9 折優惠 ✦
    </div>
    <?php require_once("navbar.php"); ?>

    <main class="container my-5">
        


        <!-- 會員登入卡片區塊 -->
        <div class="mycard mycard-container">
            <!-- 上方圖示 / Logo -->
            <div class="profile-img-card">
                <i class="fas fa-user"></i>
            </div>
            
            <p class="profile-name-card">會員登入</p>
            
            <!-- 登入表單 -->
            <form class="form-signin" action="login_check.php" method="post">
                <input type="text" name="account" class="form-control" placeholder="請輸入帳號 / Email" required autofocus>
                <input type="password" name="password" class="form-control" placeholder="請輸入密碼" required>
                
                <button class="btn btn-signin" type="submit">Sign in</button>
            </form>

            <!-- 忘記密碼 / 註冊新會員連結 -->
            <div class="other">
                <a href="register.php">註冊新會員</a> │ <a href="forgot.php">忘記密碼？</a>
            </div>
        </div>

    </main>

    <!-- 頁尾 -->
    <footer class="footer">
       <?php require_once("footer.php"); ?> 
    </footer>

    <!-- 登入時 Loading 遮罩功能（依據講義規格建立） -->
    <div id="loading" name="loading" style="display:none; position:fixed; width:100%; height:100%; top:0; left:0; background-color:rgba(255,255,255,0.7); z-index:9999;">
        <i class="fas fa-spinner fa-spin fa-3x fa-fw" style="position:absolute; top:50%; left:50%; color:var(--color-dark-brown, #3D2314); transform:translate(-50%, -50%);"></i>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <script>
        // 送出表單時自動顯示 Loading 遮罩
        document.querySelector('.form-signin').addEventListener('submit', function() {
            document.getElementById('loading').style.display = 'block';
        });
    </script>
</body>
</html>