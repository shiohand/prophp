<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo S_NAME ?>css/common.css">
<title><?php echo $title ?> - shop</title>
</head>
<body>
<header>

<h1><a href="<?php echo S_NAME ?>shop/top.php">shop</a></h1>

<nav class="global-nav">
  <?php if(isset($_SESSION['member_login'])): ?>
    <ul>
      <li>
        <a href="<?php echo S_NAME ?>shop/user_menu/view.php"><?php echo $_SESSION['member_name'] ?>様</a>ログイン中
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/product.php">商品一覧</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/ranking.php">ランキング</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/user_menu/ordered.php">注文履歴</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/user_menu/reviewed.php">レビュー履歴</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/cart.php">カート</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/logout.php">ログアウト</a>
      </li>
    </ul>
  <?php else: ?>
    <ul>
      <li>
        ゲスト様
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/product.php">商品一覧</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/ranking.php">ランキング</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/user_menu/signup.php">会員登録</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/cart.php">カート</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>shop/login.php">ログイン</a>
      </li>
    </ul>
  <?php endif ?>
</nav>

<!-- テスト用 -->
<a href="<?php echo S_NAME ?>admin/top.php">adminトップへ</a>
<a href="<?php echo S_NAME ?>shop/top.php">shopトップへ</a>
<a href="<?php echo S_NAME ?>shop/cartin_f5.php">cheat注文</a>
<!-- endテスト用 -->

</header>
<hr>