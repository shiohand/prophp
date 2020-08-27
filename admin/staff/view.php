<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/StaffDao.php');

  $title = 'スタッフ参照';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  $get = sanitize($_GET);
  $id = $get['id'];

  try {
    $dao = new StaffDao();
    $staff = $dao->findById($id);
    $param = http_build_query(['id' => $staff->getId()]);
?>

<h1>スタッフ参照</h1>

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

<a href="edit.php?<?php echo $param ?>">修正</a><br>
<a href="delete.php?<?php echo $param ?>">削除</a><br>
<button type="button" onclick="history.back()">戻る</button>

<?php
  } catch (PDOException $e) {
    dbError('admin');
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>