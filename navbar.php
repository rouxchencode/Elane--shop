<nav class="navbar navbar-expand-lg sticky-top header">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php" style="font-family: 'Margarine', sans-serif; font-weight: 400; letter-spacing: 2px;">ELANE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a href="login.php" class="nav-link">會員註冊</a></li>
                <li class="nav-item"><a href="login.php" class="nav-link">會員登入</a></li>
                <li class="nav-item"><?php
                                        // 取得當前購物車總數量
                                        $cartNumSql = sprintf("SELECT SUM(qty) as total_qty FROM cart WHERE ip = '%s' AND status = 1", $_SERVER['REMOTE_ADDR']);
                                        $cartNumRs = $link->query($cartNumSql);
                                        $cartNumRow = $cartNumRs ? $cartNumRs->fetch() : null;
                                        $totalCartQty = ($cartNumRow && $cartNumRow['total_qty']) ? (int)$cartNumRow['total_qty'] : 0;
                                        ?>
                    <a class="nav-link" href="cart.php">
                        購物車 <span id="cart-qty-badge" class="badge rounded-pill bg-danger"><?php echo $totalCartQty; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">本月新品</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="checkout.php">訂單查詢</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">全部商品</a>
                </li>
                <?php multiList01(); ?>
                <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">主題選品</a>
            <ul class="dropdown-menu">
                <li class="nav-item dropend">
                    <a href="#" class="dropdown-item dropdown-toggle"  data-bs-toggle="dropdown">
                        上身
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#" class="dropdown-item">背心</a></li>
                        <li><a href="#" class="dropdown-item">休閒</a></li>
                        <li><a href="#" class="dropdown-item">罩衫</a></li>
                    </ul>
                </li>

                <li class="nav-item dropend">
                    <a href="#" class="dropdown-item dropdown-toggle"  data-bs-toggle="dropdown">
                        下身
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#" class="dropdown-item">短褲</a></li>
                        <li><a href="#" class="dropdown-item">休閒</a></li>
                        <li><a href="#" class="dropdown-item">罩衫</a></li>
                    </ul>
                </li>
                <li class="nav-item ">
                  <a class="nav-link ms-2" aria-current="page" href="#" >外套</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link ms-2" href="#">配件</a>
                </li>
            </ul>
        </li>             -->
            </ul>

            <!-- 關鍵加入：搜尋表單 -->
            <form class="d-flex" action="index.php" method="GET">
                <input class="form-control me-1" type="search" name="search" placeholder="搜尋商品名稱/簡介" aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-dark text-nowrap" type="submit">搜尋</button>
            </form>

        </div>
    </div>
</nav>
<?php
function multiList01()
{
    global $link;
    //列出產品類別第一層
    $SQLstring = "SELECT * FROM pyclass WHERE level=1 ORDER BY sort";
    $pyclass01 = $link->query($SQLstring);
?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" aria-expanded="false">產品資訊</a>
        <ul class="dropdown-menu">
            <?php while ($pyclass01_Rows = $pyclass01->fetch()) { ?>
                <li class="nav-item dropend">
                    <a href="index.php?classid=<?php echo $pyclass01_Rows['classid']; ?>" class="dropdown-item dropdown-toggle"><i class="fas <?php echo $pyclass01_Rows['fonticon']; ?> fa-lg fa-fw"></i><?php echo $pyclass01_Rows['cname']; ?></a>
                    <?php
                    //列出產品類別對應的第二層資料
                    $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=2 AND uplink=%d ORDER BY sort", $pyclass01_Rows['classid']);
                    $pyclass02 = $link->query($SQLstring);
                    ?>
                    <ul class="dropdown-menu">
                        <?php while ($pyclass02_Rows = $pyclass02->fetch()) { ?>
                            <li>
                                <a href="index.php?classid=<?php echo $pyclass02_Rows['classid']; ?>" class="dropdown-item">
                                    <em class="fas <?php echo $pyclass02_Rows['fonticon']; ?> fa-fw"></em>
                                    <?php echo $pyclass02_Rows['cname']; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>