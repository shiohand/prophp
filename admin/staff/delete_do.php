<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/StaffDao.php');
?>
<?php
  if (isset($_SESSION['del_staff'])) {
    $del_staff = unserialize($_SESSION['del_staff']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }

  try {
    $dao = new StaffDao();
    $dao->delete($del_staff);

    $_SESSION['msg'] = 'スタッフ名:'.$del_staff->getName().' (ID:'.$del_staff->getId().') のデータを削除しました';
    
    unset($_SESSION['del_staff']);
    
    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError('admin');
  }
?>