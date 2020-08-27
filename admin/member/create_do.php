<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/MemberDao.php');
?>
<?php
  if (isset($_SESSION['new_member'])) {
    $new_member = unserialize($_SESSION['new_member']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }

  try {
    $dao = new MemberDao();
    $member = $dao->create($new_member);
    
    $_SESSION['msg'] = '会員メールアドレス:'.$new_member->getEmail().' を追加しました';
    
    unset($_SESSION['new_member']);
    
    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError('admin');
  }
?>