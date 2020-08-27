<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/StaffDao.php');
?>
<?php
  if (isset($_SESSION['up_staff'])) {
    $up_staff = unserialize($_SESSION['up_staff']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }

  try {
    $dao = new StaffDao();
    $dao->update($up_staff);
    
    $_SESSION['msg'] = 'スタッフ名:'.$up_staff->getName().' (ID:'.$up_staff->getId().') のデータを修正しました';

    unset($_SESSION['up_staff']);
    
    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError('admin');
  }
?>