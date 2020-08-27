<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginShop();

  require_once(D_ROOT.'database/MemberDao.php');
  
  if (isset($_SESSION['del_user'])) {
    $del_user = unserialize($_SESSION['del_user']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('shop');
  }

  try {
    $dao = new MemberDao();
    $dao->delete($del_user);

    $_SESSION['msg'] = '登録解除しました';
    
    unset($_SESSION['del_user']);
    
    header('Location: done.php');
    exit();
    
  } catch (PDOException $e) {
    dbError('shop');
  }
?>