<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();

  $title = '完了';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }
  unset($_SESSION['msg']);
?>
  
<h1>完了</h1>

<p><?php echo $msg ?></p>
<a href="top.php">商品一覧へ</a>

<?php include(D_ROOT.'component/footer_admin.php') ?>