<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  blockLoginAdmin();

  require_once(D_ROOT.'database/StaffDao.php');
  
  try {
    $post = sanitize($_POST);
    $post_id = $post['id'];
    $post_password = $post['password'];

    $dao = new StaffDao();
    $staff = $dao->loginCheck($post_id, md5($post_password));

    if ($staff->getId()) {
      $_SESSION['staff_login'] = 1;
      $_SESSION['staff_id'] = $staff->getId();
      $_SESSION['staff_name'] = $staff->getName();
      header('Location: top.php');
      exit();
    }
  } catch (PDOException $e) {
    dbError('admin');
  }
?>
<?php
  $title = 'ログインチェック';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>スタッフIDまたはパスワードが違います</h1>

<ul>
  <li>
    <a href="login.php">ログイン画面へ戻る</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_admin.php') ?>