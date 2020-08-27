<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginShop();

  require_once(D_ROOT.'database/MemberDao.php');
  
  if (isset($_SESSION['up_user'])) {
    $up_user = unserialize($_SESSION['up_user']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('shop');
  }

  try {
    $dao = new MemberDao();
    $dao->update($up_user);

    $_SESSION['msg'] = '登録情報を修正しました';
    
    unset($_SESSION['up_user']);
    
    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError('shop');
  }
?>