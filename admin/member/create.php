<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();

  $title = '会員追加';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>会員追加</h1>

<form method="post" action="create_check.php">
  <table>
    <tr>
      <td><label for="email">メールアドレス</label></td>
      <td><input id="email" type="email" name="email" maxlength="50"><span class="announce"><br>※最大50文字</span></td>
    </tr>
    <tr>
      <td><label for="password">パスワード</label></td>
      <td><input id="password" type="password" name="password"></td>
    </tr>
    <tr>
      <td><label for="password2">パスワード再入力</label></td>
      <td><input id="password2" type="password" name="password2"></td>
    </tr>
    <tr>
      <td><label for="name">お名前</label></td>
      <td><input id="name" type="text" name="name" maxlength="15"><span class="announce"><br>※最大15文字</span></td>
    </tr>
    <tr>
      <td><label for="postal1">郵便番号</label></td>
      <td><input id="postal1" type="text" name="postal1" maxlength="3" size="3">-<input id="postal2" type="text" name="postal2" maxlength="4" size="4"></td>
    </tr>
    <tr>
      <td><label for="address">住所</label></td>
      <td><input id="address" type="text" name="address" maxlength="50"><span class="announce"><br>※最大50文字</span></td>
    </tr>
    <tr>
      <td><label for="tel">電話番号</label></td>
      <td><input id="tel" type="text" name="tel" maxlength="15"><span class="announce"><br>※半角数字・ハイフン区切り</span></td>
    </tr>
    <tr>
      <td><label for="male">性別</label></td>
      <td>
        <label><input type="radio" name="gender" value="1" checked>男性</label><br>
        <label><input type="radio" name="gender" value="2">女性</label>
      </td>
    </tr>
    <tr>
      <td>年代</td>
      <td>
        <select name="birth">
          <?php outputBirthOptions() ?>
        </select>
      </td>
    </tr>
  </table>

  <button type="button" onclick="history.back()">戻る</button>
  <input type="submit" value="確認">
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>