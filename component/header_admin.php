<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo S_NAME ?>css/common.css">
<title><?php echo $title ?> - admin</title>
</head>
<body>
<header>

<h1><a href="<?php echo S_NAME ?>admin/top.php">admin</a></h1>

<nav class="global-nav">
  <?php if(isset($_SESSION['staff_login'])): ?>
    <ul>
      <li>
        <?php echo $_SESSION['staff_name'] ?>さんログイン中
      </li>
      <li>
        <a href="<?php echo S_NAME ?>admin/logout.php">ログアウト</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>admin/staff/top.php">スタッフ管理</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>admin/product/top.php">商品管理</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>admin/member/top.php">会員管理</a>
      </li>
      <li>
        <a href="<?php echo S_NAME ?>admin/order/top.php">注文管理</a>
      </li>
    </ul>
  <?php else: ?>
    <ul>
      <li>
        <a href="login.php">ログイン</a>
      </li>
    </ul>
  <?php endif ?>
</nav>


<a href="<?php echo S_NAME ?>admin/top.php">adminトップへ</a>
<a href="<?php echo S_NAME ?>shop/top.php">shopトップへ</a>

</header>
<hr>