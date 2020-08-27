<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/StaffDao.php');

  $title = 'スタッフ修正';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  $get = sanitize($_GET);
  $id = $get['id'];

  try {
    $dao = new StaffDao();
    $staff = $dao->findById($id);
    $_SESSION['staff'] = serialize($staff);
?>

<h1>スタッフ修正</h1>

<form method="post" action="edit_check.php">
  <table>
    <tr>
      <td>スタッフID</td>
      <td><?php echo $id ?></td>
    </tr>
    <tr>
      <td><label for="name">スタッフ名</label></td>
      <td><input id="name" type="text" name="name" maxlength="15" value="<?php echo $staff->getName() ?>"><span class="announce"><br>※最大15文字</span></td>
    </tr>
    <tr>
      <td><label for="password">現在のパスワード</label></td>
      <td><input id="password" type="password" name="password"></td>
    </tr>
    <tr>
      <td><label for="new_password">新しいパスワード</label></td>
      <td><input id="new_password" type="password" name="new_password"></td>
    </tr>
    <tr>
      <td><label for="new_password2">新しいパスワード再入力</label></td>
      <td><input id="new_password2" type="password" name="new_password2"></td>
    </tr>
  </table>

  <button type="button" onclick="history.back()">戻る</button>
  <input type="submit" value="確認">
</form>

<?php
  } catch (PDOException $e) {
    dbError('admin');
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>