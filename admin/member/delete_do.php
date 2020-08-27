<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/MemberDao.php');
?>
<?php
  if (isset($_SESSION['del_member'])) {
    $del_member = unserialize($_SESSION['del_member']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }

  try {
    $dao = new MemberDao();
    $dao->delete($del_member);

    $_SESSION['msg'] = '会員メールアドレス:'.$del_member->getEmail().' (ID:'.$del_member->getId().') のデータを削除しました';
    
    unset($_SESSION['del_member']);
    
    header('Location: done.php');
    exit();
    
  } catch (PDOException $e) {
    dbError('admin');
  }
?>