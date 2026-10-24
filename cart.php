<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once("connections/conn_db.php");
require_once("php_lib.php");

$ip = $_SERVER['REMOTE_ADDR'];

// ==================== [ 處理 1:更新數量 ] ====================
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $cartid = (int)$_POST['cartid'];
    $qty = (int)$_POST['qty'];

    if ($qty > 0) {
        $stmt = $link->prepare("UPDATE cart SET qty = ? WHERE cartid = ? AND ip = ?");
        $stmt->execute([$qty, $cartid, $ip]);
    }
    header("Location: cart.php");
    exit();
}

// ==================== [ 處理 2:刪除購物車項目 ] ====================
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['cartid'])) {
    $cartid = (int)$_GET['cartid'];
    $stmt = $link->prepare("DELETE FROM cart WHERE cartid = ? AND ip = ?");
    $stmt->execute([$cartid, $ip]);
    header("Location: cart.php");
    exit();
}

// ==================== [ 處理 3:查詢購物車資料 ] ====================
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
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <?php require_once("header.php"); ?>
    <title>購物車 - ELANE</title>
</head>
<body>

    <!-- 導覽列 -->
    <?php require_once("navbar.php"); ?>

    <!-- 與 index.php 保持相同的 main 標籤與結構 -->
    <main class="container my-5">
        
        <!-- 麵包屑導覽 -->
        <?php require_once("breadcrumb.php"); ?>

        <h3 class="mb-4 fw-bold">您的購物車</h3>


        <?php if ($totalRows > 0) { ?>
            <div class="table-responsive">
                <table class="table align-middle text-center">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 120px;">圖片</th>
                            <th scope="col" class="text-start">商品名稱</th>
                            <th scope="col" style="width: 120px;">單價</th>
                            <th scope="col" style="width: 160px;">數量</th>
                            <th scope="col" style="width: 120px;">小計</th>
                            <th scope="col" style="width: 100px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $cartRs->fetch()) { 
                            $subtotal = $row['p_price'] * $row['qty'];
                            $totalAmount += $subtotal;
                        ?>
                            <tr>
                                <td>
                                    <?php if (!empty($row['img_file'])) { ?>
                                        <img src="./product_img/<?php echo $row['img_file']; ?>" alt="<?php echo htmlspecialchars($row['p_name']); ?>" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                                    <?php } else { ?>
                                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="height: 80px;">無圖片</div>
                                    <?php } ?>
                                </td>
                                <td class="text-start fw-semibold">
                                    <a href="product_detail.php?p_id=<?php echo $row['p_id']; ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($row['p_name']); ?>
                                    </a>
                                </td>
                                <td>NT$ <?php echo number_format($row['p_price']); ?></td>
                                <td>
                                    <form action="cart.php" method="post" class="d-flex justify-content-center gap-1">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="cartid" value="<?php echo $row['cartid']; ?>">
                                        <input type="number" name="qty" value="<?php echo $row['qty']; ?>" min="1" max="99" class="form-control form-control-sm text-center" style="width: 65px;">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" title="更新數量">
                                            修改
                                        </button>
                                    </form>
                                </td>
                                <td class="fw-bold">NT$ <?php echo number_format($subtotal); ?></td>
                                <td>
                                    <a href="cart.php?action=delete&cartid=<?php echo $row['cartid']; ?>" 
                                       class="btn btn-outline-danger btn-sm" 
                                       onclick="return confirm('確定要將此商品從購物車移除嗎？');">
                                        刪除
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">累計：NT$ <?php echo number_format($totalAmount); ?></td>
                        </tr>
                        <tr>
                            <td colspan="7">運費：NT$ 100</td>
                        </tr>
                        <tr>
                            <td colspan="7" class="fw-bold fs-5">總計：NT$ <?php echo number_format($totalAmount + 100); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row align-items-center mt-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <a href="index.php" class="btn btn-outline-dark">&laquo; 繼續購物</a>
                </div>
                <div class="col-md-6 text-end">
                    <a href="checkout.php" class="btn btn-dark btn-lg px-4">前往結帳 &raquo;</a>
                    <a href="shopcart_del.php?mode=2" class="btn btn-dark btn-lg px-4" onclick="btn_confirmLink('確定清空購物車?','shopcart_del.php?mode=2'); return false;">清空購物車 &raquo;</a>
                </div>
            </div>

        <?php } else { ?>
            <div class="text-center py-5 my-5">
                <h4 class=" mb-4">您的購物車目前是空的喔！</h4>
                <a href="index.php" class="btn btn-dark btn-lg">立即去逛逛</a>
            </div>
        <?php } ?>

    </main> <!-- 這裡明確關閉 main 標籤，確保 footer 獨立在外面 -->

<footer class="footer">
   <?php require_once("footer.php"); ?> 
</footer>
</body>
</html>