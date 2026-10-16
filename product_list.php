<?php
// 分頁設定
$maxRows_rs = 8; // 每頁顯示 8 筆
$pageNum_rs = 0;
if (isset($_GET['pageNum_rs'])) {
    $pageNum_rs = (int)$_GET['pageNum_rs'];
}
$startRow_rs = $pageNum_rs * $maxRows_rs;

// 基礎 SQL 條件（僅上架商品且主圖排序為 1）
$whereClause = "WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id";

// 判斷是否有搜尋關鍵字
if (isset($_GET['search']) && trim($_GET['search']) != '') {
    $search = trim($_GET['search']);
    $searchKey = $link->quote("%{$search}%");
    $whereClause .= " AND (product.p_name LIKE {$searchKey} OR product.p_intro LIKE {$searchKey})";
}
// 若點擊了分類 (無論是大分類還是小分類)
elseif (isset($_GET['classid']) && (int)$_GET['classid'] > 0) {
    $classid = (int)$_GET['classid'];

    // 檢查資料庫：看看這個 classid 底下有沒有子分類 (uplink = $classid)
    $sub_sql = "SELECT classid FROM pyclass WHERE uplink = {$classid}";
    $sub_rs = $link->query($sub_sql);

    // 如果底下有子分類 (代表它是第二層大分類，如：上身)
    if ($sub_rs && $sub_rs->rowCount() > 0) {
        // 使用 IN (SELECT classid FROM pyclass WHERE uplink = $classid)
        $whereClause .= sprintf(" AND product.classid IN (SELECT classid FROM pyclass WHERE uplink = %d)", $classid);
    } else {
        // 如果底下沒有子分類 (代表它是第三層細項，如：背心)
        $whereClause .= " AND product.classid = {$classid}";
    }
}

// 查詢總筆數
$queryFirst = "SELECT product.*, product_img.img_file FROM product, product_img {$whereClause} ORDER BY product.p_id ASC";
$all_rs = $link->query($queryFirst);
$totalRows_rs = $all_rs ? $all_rs->rowCount() : 0;
$totalPages_rs = ceil($totalRows_rs / $maxRows_rs) - 1;

// 分頁商品查詢
$query = sprintf("%s LIMIT %d, %d", $queryFirst, $startRow_rs, $maxRows_rs);
$pList01 = $link->query($query);
$i = 1;
?>

<!-- 搜尋提示 -->
<?php if (isset($_GET['search']) && trim($_GET['search']) != '') { ?>
    <div class="alert alert-light border mb-4">
        搜尋結果：關鍵字「<strong><?php echo htmlspecialchars($_GET['search']); ?></strong>」，共 <?php echo $totalRows_rs; ?> 筆商品。
    </div>
<?php } ?>

<?php if ($totalRows_rs > 0) { ?>
    <div class="row text-center mb-4">
        <?php while ($pList01_Rows = $pList01->fetch()) { ?>
            <div class="col-6 col-md-3 mb-4 d-flex align-items-stretch">
                <div class="card w-100 border-0 shadow-sm">
                    
                    <!-- 圖片與懸浮按鈕容器 -->
                    <div class="position-relative overflow-hidden product-image-container">
                        <a href="product_detail.php?p_id=<?php echo $pList01_Rows['p_id']; ?>">
                            <img src="./product_img/<?php echo htmlspecialchars($pList01_Rows['img_file']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($pList01_Rows['p_name']); ?>">
                        </a>
                        
                        <!-- 懸浮出現的加入購物車按鈕 -->
                        <div class="product-hover-overlay">
                            <button type="button" class="btn btn-dark btn-sm w-100 py-2 rounded-0 btn-add-cart" data-id="<?php echo $pList01_Rows['p_id']; ?>">
                                <i class="bi bi-cart-plus me-1"></i> 立即選購
                            </button>
                        </div>
                    </div>

                    <!-- 商品文字資訊（點擊標題可看更多資訊） -->
                    <div class="card-body d-flex flex-column justify-content-between text-start p-3">
                        <div>
                            <h5 class="card-title fs-6 text-truncate">
                                <a href="product_detail.php?p_id=<?php echo $pList01_Rows['p_id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($pList01_Rows['p_name']); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-2"><?php echo mb_substr(htmlspecialchars($pList01_Rows['p_intro']), 0, 25, "utf-8"); ?></p>
                        </div>
                        <div>
                            <p class="card-text fw-bold text-danger mb-0">NT$ <?php echo number_format($pList01_Rows['p_price']); ?></p>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>
    </div>

    <!-- 分頁選單 -->
    <div class="row mt-2">
        <?php
        $prev_rs = "&laquo;";
        $next_rs = "&raquo;";
        $separator = "";
        $max_links = 10;
        $pages_rs = buildNavigation($pageNum_rs, $totalPages_rs, $prev_rs, $next_rs, $separator, $max_links, true, 3, "rs");
        ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php echo $pages_rs[0] . $pages_rs[1] . $pages_rs[2]; ?>
            </ul>
        </nav>
    </div>
<?php } else { ?>
    <div class="text-center py-5">
        <p class="text-muted fs-5">查無相關商品！</p>
    </div>
<?php } ?>