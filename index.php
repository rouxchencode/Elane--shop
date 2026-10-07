

<!-- 若session未啟動，則先啟動session 功能，此為跨網頁變數存取-->
<?php (!isset($_session)) ? session_start() : ''; ?>


<!-- 將資料庫連線資訊載入 -->
<?php require_once("Connections/conn_db.php"); ?>

<!-- 載入共用PHP函數庫 -->

<?php require_once("php_lib.php"); ?>





<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <!-- 引入網頁的標頭 -->
     <?php require_once('header.php');?>
</head>
<body>

<div class="announcement-bar">
  ✦ 全館滿 $1,500 即享免運｜新會員首購輸入【BABYCHILL】即享 9 折優惠 ✦
</div>

<!-- 引入導覽列 -->
<?php require_once("navbar.php"); ?>  

<main class="container">
  <!-- Hero Banner (套用背景模特圖) -->
  <section class="hero">
    <div class="hero-grid">
      <div class="hero-card hero-dark">
        <span class="badge-tag">Tops</span>
        <h1 class="hero-title">捕捉日常的柔光<br>輕盈上身穿搭提案</h1>
        <p class="hero-desc">揉合細緻與層次抓皺，打造空氣感滿滿的溫柔氣息。</p>
        <a href="#" class="btn btn-pink">探索熱銷上身專區 →</a>
      </div>
      <div class="hero-card hero-light">
        <span class="badge-tag">Bottoms</span>
        <h1 class="hero-title">輕盈下身系列<br>流暢剪裁的日常俐落</h1>
        <p class="hero-desc">俐落剪裁的完美平衡，展現隨性不造作的少女感。</p>
        <a href="#" class="btn btn-dark">探索精選下身專區 →</a>
      </div>
    </div>
  </section>


  <!-- 建立類別分項 -->
   <?php require_once('breadcrumb.php'); ?>

  <!--引入product商品 -->
   <?php require_once("product_list.php"); ?>   
  <!-- Collection A:  -->
  <!-- <section>
    <div class="section-header">
      <h2 class="section-title">COLLECTION A｜純白蕾絲與柔灰</h2>
      <p class="section-subtitle">輕薄透膚，打造仙氣滿滿的溫柔甜辣質感</p>
    </div>
    <div class="product-grid">
      <div class="product-card">
        <div class="product-img-box">
          <span class="product-tag tag-pink">HOT</span>
          <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=500&auto=format&fit=crop" alt="深咖羊毛大衣 Model">
        </div>
        <div class="product-info">
          <h3>輕柔蕾絲透膚罩衫套裝</h3>
          <p class="product-price">NT$ 2,480</p>
          <button class="add-to-cart">+ 加入購物車</button>
        </div>
      </div>

      <div class="product-card">
        <div class="product-img-box">
          <span class="product-tag tag-pink">MUST BUY</span>
          <img src="https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500&auto=format&fit=crop" alt="粉色針織衫 Model">
        </div>
        <div class="product-info">
          <h3>溫柔抓褶領結綁帶上衣</h3>
          <p class="product-price">NT$ 1,280</p>
          <button class="add-to-cart">+ 加入購物車</button>
        </div>
      </div>

      <div class="product-card">
        <div class="product-img-box">
          <span class="product-tag tag-pink">CLASSIC</span>
          <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?w=500&auto=format&fit=crop" alt="深咖傘裙 Model">
        </div>
        <div class="product-info">
          <h3>少女感抓褶層次蛋糕長裙</h3>
          <p class="product-price">NT$ 1,450</p>
          <button class="add-to-cart">+ 加入購物車</button>
        </div>
      </div>
    </div>
  </section> -->

  <!-- Collection B:  -->
  <!-- <section>
    <div class="section-header">
      <h2 class="section-title">COLLECTION B｜俐落斜領與層次剪裁</h2>
      <p class="section-subtitle">斜領剪裁與美背交叉，隨性修飾身形的日常微辣穿搭</p>
    </div>
    <div class="product-grid">
      <div class="product-card">
        <div class="product-img-box">
          <span class="product-tag tag-yellow">NEW</span>
          <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500&auto=format&fit=crop" alt="寶寶藍襯衫 Model">
        </div>
        <div class="product-info">
          <h3>法式一字領氣質綁帶襯衫</h3>
          <p class="product-price">NT$ 1,180</p>
          <button class="add-to-cart">+ 加入購物車</button>
        </div>
      </div>

      <div class="product-card">
        <div class="product-img-box">
          <span class="product-tag tag-yellow">POPULAR</span>
          <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=500&auto=format&fit=crop" alt="黃色包包 Model">
        </div>
        <div class="product-info">
          <h3>修身交叉線條短袖上衣</h3>
          <p class="product-price">NT$ 1,580</p>
          <button class="add-to-cart">+ 加入購物車</button>
        </div>
      </div>

      <div class="product-card">
        <div class="product-img-box">
          <span class="product-tag tag-yellow">SET</span>
          <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=500&auto=format&fit=crop" alt="淺藍牛仔褲 Model">
        </div>
        <div class="product-info">
          <h3>不對稱斜領頸圈設計短袖</h3>
          <p class="product-price">NT$ 1,380</p>
          <button class="add-to-cart">+ 加入購物車</button>
        </div>
      </div>
    </div>
  </section> -->
</main>

<footer class="footer">
   <?php require_once("footer.php"); ?> 
</footer>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>
</html>