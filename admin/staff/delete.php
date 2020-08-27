<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/StaffDao.php');

  $title = 'スタッフ削除';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  $get = sanitize($_GET);
  $id = $get['id'];

  try {
    $dao = new StaffDao();
    $staff = $dao->findById($id);
    $_SESSION['del_staff'] = serialize($staff);
?>

<h1>スタッフ削除</h1>

<form method="post" action="delete_do.php">
  <table>
    <tr>
      <td>スタッフID</td>
      <td><?php echo $staff->getId() ?></td>
    </tr>
    <tr>
      <td>スタッフ名</td>
      <td>
        <?php echo $staff->getName() ?>
      </td>
    </tr>
  </table>

  <p>削除しますか？</p>
  <button type="button" onclick="history.back()">戻る</button>
  <input type="submit" value="削除">
</form>

<?php
  } catch (PDOException $e) {
    dbError('admin');
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>