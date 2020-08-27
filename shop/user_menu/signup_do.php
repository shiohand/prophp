<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  blockLoginShop();

  require_once(D_ROOT.'database/MemberDao.php');
  
  if (isset($_SESSION['new_user'])) {
    $new_user = unserialize($_SESSION['new_user']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('shop');
  }

  try {
    $dao = new MemberDao();
    $user = $dao->create($new_user);
    
    $_SESSION['msg'] = '登録しました';
    
    unset($_SESSION['new_user']);
    
    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError('shop');
  }
?>