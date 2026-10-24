<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once("connections/conn_db.php");
require_once("php_lib.php");

$ip = $_SERVER['REMOTE_ADDR'];

// ==================== [ 查詢購物車資料 ] ====================
$cartRs = $link->prepare(
    "SELECT cart.cartid, cart.qty, product.p_id, product.p_name, product.p_price, product_img.img_file
     FROM cart
     JOIN product ON cart.p_id = product.p_id
     LEFT JOIN product_img ON product.p_id = product_img.p_id AND product_img.sort = 1
     WHERE cart.ip = ? AND cart.status = 1
     ORDER BY cart.cartid DESC"
);
$cartRs->execute([$ip]);
$totalRows = $cartRs->rowCount();
$totalAmount = 0;
$shippingFee = 100; // 固定運費

// 若購物車無商品，自動引導回購物車
if ($totalRows == 0) {
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <?php require_once("header.php"); ?>
    <title>會員結帳作業 - ELANE</title>
</head>
<body>

    <!-- 頂部公告欄與導覽列 -->
    <div class="announcement-bar">
        ✦ 全館滿 $1,500 即享免運｜新會員首購輸入【BABYCHILL】即享 9 折優惠 ✦
    </div>
    <?php require_once("navbar.php"); ?>

    <main class="container my-5">
        
        <!-- 麵包屑導覽 -->
        <?php require_once("breadcrumb.php"); ?>

        <!-- 頁面標題 -->
        <div class="border-bottom border-danger pb-2 mb-4">
            <h2 class="fw-bold text-center m-0">測試執行</h2>
            <p class="text-center text-muted mb-0 mt-1">Elane：會員結帳作業</p>
        </div>

        <!-- 資訊卡片區 -->
        <div class="row g-4 mb-4">
            <!-- 配送資訊 (左) -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm bg-light">
                    <div class="card-header bg-white border-bottom border-light fw-bold py-3">
                        <i class="fas fa-truck me-2 text-danger"></i> 配送資訊
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">收件人資訊：</h5>
                        <p class="mb-1"><strong>姓名：</strong>李小明</p>
                        <p class="mb-1"><strong>電話：</strong>0912345678</p>
                        <p class="mb-1"><strong>郵遞區號：</strong>407台中市西屯區</p>
                        <p class="mb-3"><strong>地址：</strong>中正路1號</p>
                        <button type="button" class="btn btn-outline-primary btn-sm">選擇其他收件人</button>
                    </div>
                </div>
            </div>

            <!-- 付款資訊 (右) -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm bg-light">
                    <div class="card-header bg-white border-bottom border-light fw-bold py-3">
                        <i class="fas fa-credit-card me-2 text-danger"></i> 付款資訊
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">付款方式資訊：</h5>
                        <p class="mb-1"><strong>付款方式：</strong>信用卡一次付清</p>
                        <p class="mb-1"><strong>卡號末四碼：</strong>8888</p>
                        <p class="mb-1"><strong>發票開立：</strong>個人電子發票</p>
                        <p class="mb-3"><strong>載具號碼：</strong>/ABC1234</p>
                        <button type="button" class="btn btn-outline-primary btn-sm">變更付款方式</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 結帳商品明細列表 -->
        <div class="table-responsive mb-4 shadow-sm rounded">
            <table class="table align-middle text-center mb-0">
                <thead class="table-primary text-white" style="background-color: #007bff;">
                    <tr>
                        <th scope="col" style="width: 100px;">產品編號</th>
                        <th scope="col" style="width: 100px;">圖片</th>
                        <th scope="col" class="text-start">名稱</th>
                        <th scope="col" style="width: 120px;">價格</th>
                        <th scope="col" style="width: 100px;">數量</th>
                        <th scope="col" style="width: 120px;">小計</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    while ($row = $cartRs->fetch()) { 
                        $subtotal = $row['p_price'] * $row['qty'];
                        $totalAmount += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td>
                                <?php if (!empty($row['img_file'])) { ?>
                                    <img src="./product_img/<?php echo $row['img_file']; ?>" alt="<?php echo htmlspecialchars($row['p_name']); ?>" class="img-fluid rounded" style="max-height: 50px; object-fit: cover;">
                                <?php } else { ?>
                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="height: 50px; font-size: 12px;">無圖片</div>
                                <?php } ?>
                            </td>
                            <td class="text-start fw-semibold">
                                <?php echo htmlspecialchars($row['p_name']); ?>
                            </td>
                            <td class="text-danger fw-bold">NT$ <?php echo number_format($row['p_price']); ?></td>
                            <td><?php echo $row['qty']; ?></td>
                            <td class="text-danger fw-bold">NT$ <?php echo number_format($subtotal); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- 結帳總計與確認按鈕區 -->
        <div class="card border-0 bg-transparent text-center mb-5">
            <div class="py-2">
                <p class="mb-1 text-muted">累計：NT$ <?php echo number_format($totalAmount); ?></p>
                <p class="mb-1 text-muted">運費：NT$ <?php echo number_format($shippingFee); ?></p>
                <h4 class="fw-bold text-danger my-2">總計：NT$ <?php echo number_format($totalAmount + $shippingFee); ?></h4>
            </div>
            <div class="mt-2">
                <form action="order_add.php" method="post">
                    <button type="submit" class="btn btn-dark btn-lg px-5 shadow-sm fw-bold">
                        <i class="fas fa-shopping-bag me-1"></i> 確認結帳
                    </button>
                </form>
            </div>
        </div>

    </main>

    <!-- 頁尾 -->
    <footer class="footer">
       <?php require_once("footer.php"); ?> 
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

</body>
</html>