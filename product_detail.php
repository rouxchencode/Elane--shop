<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once("connections/conn_db.php");
require_once("php_lib.php");

// 1. 取得網址傳遞的 p_id，若無則預設為 0
$p_id = isset($_GET['p_id']) ? (int)$_GET['p_id'] : 0;

// 2. 查詢該商品的詳細資料
$querySql = sprintf("SELECT * FROM product WHERE p_id = %d AND p_open = 1", $p_id);
$result = $link->query($querySql);
$row = $result ? $result->fetch() : null;

// 若找不到商品則返回首頁或提示
if (!$row) {
    header("Location: index.php");
    exit();
}

// 3. 查詢該商品的所有圖片
$imgSql = sprintf("SELECT * FROM product_img WHERE p_id = %d ORDER BY sort ASC", $p_id);
$imgResult = $link->query($imgSql);
$images = $imgResult ? $imgResult->fetchAll() : [];
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <?php require_once("header.php"); ?>
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
    <title><?php echo htmlspecialchars($row['p_name']); ?> - ELANE</title>
</head>
<body>

<div class="announcement-bar">
    ✦ 全館滿 $1,500 即享免運｜新會員首購輸入【BABYCHILL】即享 9 折優惠 ✦
</div>

<!-- 引入導覽列 -->
<?php require_once("navbar.php"); ?>

<main class="container my-5">
    
    <!-- 麵包屑導覽 -->
    <?php require_once("breadcrumb.php"); ?>

    <div class="row my-4">

        <!-- 商品圖片區 -->
        <div class="col-md-6 mb-4">
            <?php if (!empty($images)) { ?>
                <!-- 主圖展示 (點擊開啟 Fancybox 彈窗輪播) -->
                <div class="mb-3">
                    <a href="./product_img/<?php echo $images[0]['img_file']; ?>" id="main-img-link" data-fancybox="product-gallery" data-caption="<?php echo htmlspecialchars($row['p_name']); ?>">
                        <img src="./product_img/<?php echo $images[0]['img_file']; ?>" alt="<?php echo htmlspecialchars($row['p_name']); ?>" class="img-fluid rounded shadow-sm w-100" style="max-height: 450px; object-fit: cover;" id="main-product-img">
                    </a>
                </div>
                
                <!-- 縮圖列表 (點擊切換頁面主圖，彈窗保留所有圖片輪播與箭頭) -->
                <?php if (count($images) > 1) { ?>
                    <div class="d-flex gap-2 flex-wrap">
                        <?php foreach ($images as $index => $img) { ?>
                            <!-- 縮圖：點擊切換主圖 -->
                            <img src="./product_img/<?php echo $img['img_file']; ?>" 
                                 alt="<?php echo htmlspecialchars($row['p_name']); ?>" 
                                 class="rounded border thumbnail-img <?php echo $index === 0 ? 'border-dark' : ''; ?>" 
                                 style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
                                 data-full="./product_img/<?php echo $img['img_file']; ?>">
                            
                            <!-- 隱藏縮圖的 Fancybox 觸發連結（從第 2 張圖片起加入相冊隊列，避免重複） -->
                            <?php if ($index > 0) { ?>
                                <a href="./product_img/<?php echo $img['img_file']; ?>" data-fancybox="product-gallery" data-caption="<?php echo htmlspecialchars($row['p_name']); ?>" class="d-none"></a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="height: 400px;">無圖片</div>
            <?php } ?>
        </div>

        <!-- 商品資訊與購買區 -->
        <div class="col-md-6">
            <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($row['p_name']); ?></h2>
            <h3 class="text-danger fw-bold mb-4">NT$ <?php echo number_format($row['p_price']); ?></h3>
            
            <div class="mb-4">
                <h5 class="fw-semibold">商品介紹</h5>
                <p class="text-muted" style="line-height: 1.8;"><?php echo nl2br(htmlspecialchars($row['p_intro'])); ?></p>
            </div>

            <hr class="my-4">

            <!-- 數量與加入購物車 -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <label for="buy-qty" class="fw-semibold">數量：</label>
                <input type="number" id="buy-qty" value="1" min="1" max="99" class="form-control text-center" style="width: 80px;">
            </div>

            <div class="d-flex gap-3">
                <button type="button" class="btn btn-dark btn-lg px-4 btn-add-cart" data-id="<?php echo $row['p_id']; ?>">
                    加入購物車
                </button>
                <a href="index.php" class="btn btn-outline-dark btn-lg px-4">返回首頁</a>
            </div>
        </div>
    </div>

</main>

<!-- 頁尾 -->
<footer class="footer">
   <?php require_once("footer.php"); ?> 
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

<!-- 引入 Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. 初始化 Fancybox (使用專屬名稱 product-gallery)
    Fancybox.bind('[data-fancybox="product-gallery"]', {
        Infinite: true,
        Navigation: true
    });

    // 2. 點擊縮圖切換大圖 display，同時更新 Fancybox 主圖連結
    const thumbnails = document.querySelectorAll('.thumbnail-img');
    const mainImg = document.getElementById('main-product-img');
    const mainImgLink = document.getElementById('main-img-link');

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function () {
            const newSrc = this.getAttribute('data-full');

            if (mainImg) mainImg.src = newSrc;
            if (mainImgLink) mainImgLink.href = newSrc;

            thumbnails.forEach(t => t.classList.remove('border-dark'));
            this.classList.add('border-dark');
        });
    });
});
</script>


</body>
</html>