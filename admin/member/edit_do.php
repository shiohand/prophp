<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/MemberDao.php');
?>
<?php
  if (isset($_SESSION['up_member'])) {
    $up_member = unserialize($_SESSION['up_member']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }

  try {
    $dao = new MemberDao();
    $dao->update($up_member);

    $_SESSION['msg'] = '会員メールアドレス:'.$up_member->getEmail().' (ID:'.$up_member->getId().') のデータを修正しました';
    
    unset($_SESSION['up_member']);
    
    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError('admin');
  }
?>