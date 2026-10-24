<?php
require_once("connections/conn_db.php");

$ip = $_SERVER['REMOTE_ADDR'];

if (isset($_GET['mode']) && $_GET['mode'] != '') {
    $mode = (int)$_GET['mode'];
    switch ($mode) {
        case 1:
            // 使用購物車編號刪除內容
            $cartid = isset($_GET['cartid']) ? (int)$_GET['cartid'] : 0;
            $stmt = $link->prepare("DELETE FROM cart WHERE cartid = ? AND ip = ? AND orderid IS NULL");
            $stmt->execute([$cartid, $ip]);
            break;
        case 2:
            // 使用IP清空購物車全部內容
            $stmt = $link->prepare("DELETE FROM cart WHERE ip = ? AND orderid IS NULL");
            $stmt->execute([$ip]);
            break;
    }
}

header("Location: cart.php");
exit();
?>