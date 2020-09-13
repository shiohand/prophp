<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();
  
  $title = '完了';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  reqSession('msg');
  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);
?>

<h1>完了</h1>

<p><?php echo $msg ?></p>

<a href="<?php echo S_NAME ?>shop/product.php">商品一覧へ</a><br>
<a href="<?php echo S_NAME ?>shop/top.php">トップページへ</a>

<?php include(D_ROOT.'component/footer_shop.php') ?>