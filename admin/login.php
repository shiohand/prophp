<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  blockLogin();
  
  $title = 'ログイン';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>スタッフログイン</h1>

<form method="post" action="login_check.php">
  <table>
    <tr>
      <td><label for="id">スタッフID</label></td>
      <td><input id="id" type="text" name="id"></td>
    </tr>
    <tr>
      <td><label for="password">パスワード</label></td>
      <td><input id="password" type="password" name="password"></td>
    </tr>
  </table>

  <input type="submit" value="ログイン">
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>