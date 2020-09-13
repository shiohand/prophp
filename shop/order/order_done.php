<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();
  
  $title = '注文完了';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  reqSession('msg');
  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);
?>

<h1>注文完了</h1>

<p><?php echo nl2br($msg) ?></p>

<!-- テスト用 -->
<h2>確認 顧客側 注文完了メール</h2>
<p><?php echo nl2br($body) ?></p>
<!-- endテスト用 -->

<br>
<a href="<?php echo S_NAME ?>shop/product.php">商品一覧へ</a>
<a href="<?php echo S_NAME ?>shop/top.php">トップページへ</a>

<?php include(D_ROOT.'component/footer_shop.php') ?>