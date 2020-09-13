<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  $title = 'スタッフ追加';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>スタッフ追加</h1>

<form method="post" action="create_check.php">
  <table>
    <tr>
      <td><label for="name">スタッフ名</label></td>
      <td><input id="name" type="text" name="name" maxlength="15"><span class="announce"><br>※最大15文字</span></td>
    </tr>
    <tr>
      <td><label for="password">パスワード</label></td>
      <td><input id="password" type="password" name="password"></td>
    </tr>
    <tr>
      <td><label for="password2">パスワード再入力</label></td>
      <td><input id="password2" type="password" name="password2"></td>
    </tr>
  </table>

  <button type="button" onclick="history.back()">戻る</button>
  <input type="submit" value="確認">
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>