<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once("Connections/conn_db.php");

if (isset($_POST['p_id']) && (int)$_POST['p_id'] > 0) {
    $p_id = (int)$_POST['p_id'];
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    $ip = $_SERVER['REMOTE_ADDR'];
    $emailid = isset($_SESSION['emailid']) ? $_SESSION['emailid'] : NULL;

    // 檢查購物車中是否已有該商品
    $checkSql = sprintf("SELECT cartid, qty FROM cart WHERE p_id = %d AND ip = '%s' AND status = 1", $p_id, $ip);
    $rs = $link->query($checkSql);
    
    if ($rs && $row = $rs->fetch()) {
        // 更新數量
        $newQty = $row['qty'] + $qty;
        $updateSql = sprintf("UPDATE cart SET qty = %d WHERE cartid = %d", $newQty, $row['cartid']);
        $link->query($updateSql);
    } else {
        // 新增商品到購物車
        $insertSql = sprintf(
            "INSERT INTO cart (emailid, p_id, qty, status, ip) VALUES (%s, %d, %d, 1, '%s')",
            $emailid ? $emailid : "NULL", $p_id, $qty, $ip
        );
        $link->query($insertSql);
    }

    // 重新計算當前 IP 的購物車商品總件數
    $countSql = sprintf("SELECT SUM(qty) as total_qty FROM cart WHERE ip = '%s' AND status = 1", $ip);
    $countRs = $link->query($countSql);
    $countRow = $countRs ? $countRs->fetch() : null;
    $cartCount = $countRow && $countRow['total_qty'] ? (int)$countRow['total_qty'] : 0;

    // 回傳結果多帶了 "cart_count" => $cartCount
    echo json_encode([
        "status" => "success", 
        "message" => "已成功加入購物車！",
        "cart_count" => $cartCount
    ]);


    exit();
}

echo json_encode(["status" => "error", "message" => "加入購物車失敗！"]);