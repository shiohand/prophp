<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();
  
  $title = '完了';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqSession('msg');
  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);
?>

<h1>完了</h1>

<p><?php echo $msg ?></p>
<a href="top.php">スタッフ一覧へ</a>

<?php include(D_ROOT.'component/footer_admin.php') ?>