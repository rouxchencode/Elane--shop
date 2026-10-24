<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once("connections/conn_db.php");

if (isset($_POST['p_id']) && (int)$_POST['p_id'] > 0) {
    $p_id = (int)$_POST['p_id'];
    $qty = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;
    $ip = $_SERVER['REMOTE_ADDR'];
    $emailid = isset($_SESSION['emailid']) ? $_SESSION['emailid'] : null;

    // 檢查購物車中是否已有該商品
    $stmt = $link->prepare("SELECT cartid, qty FROM cart WHERE p_id = ? AND ip = ? AND status = 1");
    $stmt->execute([$p_id, $ip]);
    $row = $stmt->fetch();

    if ($row) {
        // 更新數量
        $stmt = $link->prepare("UPDATE cart SET qty = ? WHERE cartid = ?");
        $stmt->execute([$row['qty'] + $qty, $row['cartid']]);
    } else {
        // 新增商品到購物車
        $stmt = $link->prepare("INSERT INTO cart (emailid, p_id, qty, status, ip) VALUES (?, ?, ?, 1, ?)");
        $stmt->execute([$emailid, $p_id, $qty, $ip]);
    }

    // 重新計算購物車總件數
    $stmt = $link->prepare("SELECT SUM(qty) AS total_qty FROM cart WHERE ip = ? AND status = 1");
    $stmt->execute([$ip]);
    $countRow = $stmt->fetch();
    $cartCount = ($countRow && $countRow['total_qty']) ? (int)$countRow['total_qty'] : 0;

    echo json_encode([
        "status" => "success",
        "message" => "已成功加入購物車！",
        "cart_count" => $cartCount
    ]);
    exit();
}

echo json_encode(["status" => "error", "message" => "加入購物車失敗！"]);