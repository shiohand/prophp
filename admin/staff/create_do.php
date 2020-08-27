<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/StaffDao.php');
?>
<?php
  if (isset($_SESSION['new_staff'])) {
    $new_staff = unserialize($_SESSION['new_staff']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }
  
  try {
    $dao = new StaffDao();
    $dao->create($new_staff);
    
    $_SESSION['msg'] = 'スタッフ名:'.$new_staff->getName().' を追加しました';
    
    unset($_SESSION['new_staff']);
    
    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError('admin');
  }
?>