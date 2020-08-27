<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  blockLoginShop();

  require_once(D_ROOT.'database/MemberDao.php');
  
  $title = 'ログイン';
  include(D_ROOT.'component/header_shop.php');
?>

<h1>会員ログイン</h1>

<form method="post" action="login_check.php">
  <table>
    <tr>
      <td><label for="email">メールアドレス</label></td>
      <td><input id="email" type="email" name="email"></td>
    </tr>
    <tr>
      <td><label for="password">パスワード</label></td>
      <td><input id="password" type="password" name="password"></td>
    </tr>
  </table>

  <input type="submit" value="ログイン">
</form>

<?php include(D_ROOT.'component/footer_shop.php') ?>
