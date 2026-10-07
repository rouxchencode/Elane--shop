<?php
// ==================== [ 麵包屑導覽邏輯完整修正 ] ====================
$level1Open = "";
$level2Open = "";
$level3Open = "";

$currentPage = basename($_SERVER['PHP_SELF']);

if ($currentPage == 'checkout.php') {
    // 0. 結帳頁面
    $level1Open = '<li class="breadcrumb-item"><a href="cart.php">購物車</a></li>';
    $level2Open = '<li class="breadcrumb-item active" aria-current="page">會員結帳作業</li>';

} elseif (isset($_GET['p_id']) && $_GET['p_id'] != "") {
    // 1. 商品細節頁
    $p_id = (int)$_GET['p_id'];
    $prodSql = sprintf("SELECT * FROM product WHERE p_id = %d", $p_id);
    $prodRs = $link->query($prodSql);
    $prodData = $prodRs ? $prodRs->fetch() : null;

    if ($prodData) {
        $classid = (int)$prodData['classid'];
        
        // 查詢該商品綁定的分類
        $classSql = sprintf("SELECT * FROM pyclass WHERE classid = %d", $classid);
        $classRs = $link->query($classSql);
        $classData = $classRs ? $classRs->fetch() : null;

        if ($classData) {
            if ((int)$classData['level'] == 2 || ((int)$classData['uplink'] > 0)) {
                // 情境 A：商品綁定的是第二層子分類 (例如: classid = 21 短褲)
                $upSql = sprintf("SELECT * FROM pyclass WHERE classid = %d", (int)$classData['uplink']);
                $upRs = $link->query($upSql);
                $upData = $upRs ? $upRs->fetch() : null;

                if ($upData) {
                    $level1Open = '<li class="breadcrumb-item"><a href="index.php?classid=' . $upData['classid'] . '&level=' . $upData['level'] . '">' . htmlspecialchars($upData['cname']) . '</a></li>';
                }
                $level2Open = '<li class="breadcrumb-item"><a href="index.php?classid=' . $classData['classid'] . '">' . htmlspecialchars($classData['cname']) . '</a></li>';
            } else {
                // 情境 B：商品直接綁定第一層大分類 (例如: classid = 2 下身)
                $level1Open = '<li class="breadcrumb-item"><a href="index.php?classid=' . $classData['classid'] . '&level=' . $classData['level'] . '">' . htmlspecialchars($classData['cname']) . '</a></li>';
            }
        }

        // 當前商品名稱
        $level3Open = '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($prodData['p_name']) . '</li>';
    }

} elseif (isset($_GET['search']) && trim($_GET['search']) != "") {
    // 2. 關鍵字搜尋
    $level1Open = '<li class="breadcrumb-item active" aria-current="page">搜尋：「' . htmlspecialchars($_GET['search']) . '」</li>';

} elseif (isset($_GET['level']) && isset($_GET['classid'])) {
    // 3. 第一層大分類頁面
    $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=%d AND classid=%d", (int)$_GET['level'], (int)$_GET['classid']);
    $classid_rs = $link->query($SQLstring);
    $data = $classid_rs ? $classid_rs->fetch() : null;
    if ($data) {
        $level1Open = '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($data['cname']) . '</li>';
    }

} elseif (isset($_GET['classid'])) {
    // 4. 分類頁面
    $SQLstring = sprintf("SELECT * FROM pyclass WHERE classid=%d", (int)$_GET['classid']);
    $classid_rs = $link->query($SQLstring);
    $data = $classid_rs ? $classid_rs->fetch() : null;
    
    if ($data) {
        if ((int)$data['uplink'] > 0) {
            $upSql = sprintf("SELECT * FROM pyclass WHERE classid=%d", (int)$data['uplink']);
            $upRs = $link->query($upSql);
            $upData = $upRs ? $upRs->fetch() : null;
            if ($upData) {
                $level1Open = '<li class="breadcrumb-item"><a href="index.php?classid=' . $upData['classid'] . '&level=' . $upData['level'] . '">' . htmlspecialchars($upData['cname']) . '</a></li>';
            }
            $level2Open = '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($data['cname']) . '</li>';
        } else {
            $level1Open = '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($data['cname']) . '</li>';
        }
    }

} elseif ($currentPage == 'cart.php') {
    // 5. 購物車頁面
    $level1Open = '<li class="breadcrumb-item active" aria-current="page">您的購物車</li>';
}
?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">首頁</a></li>
        <?php echo $level1Open . $level2Open . $level3Open; ?>
    </ol>
</nav>