<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();

  require_once(D_ROOT.'database/MemberDao.php');

  $title = '会員修正';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  $get = sanitize($_GET);
  $id = $get['id'];

  try {
    $dao = new MemberDao();
    $member = $dao->findById($id);
    $_SESSION['member'] = serialize($member);
?>

<h1>会員修正</h1>

<form method="post" action="edit_check.php">
  <table>
    <tr>
      <td>会員ID</td>
      <td><?php echo $id ?></td>
    </tr>
    <tr>
      <td><label for="email">メールアドレス</label></td>
      <td><input id="email" type="email" name="email" maxlength="50" value="<?php echo $member->getEmail() ?>"><span class="announce"><br>※最大50文字</span></td>
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
    <tr>
      <td><label for="name">名前</label></td>
      <td><input id="name" type="text" name="name" maxlength="15" value="<?php echo $member->getName() ?>"><span class="announce"><br>※最大15文字</span></td>
    </tr>
    <tr>
      <td><label for="postal1">郵便番号</label></td>
      <td>
        <input id="postal1" type="text" name="postal1" maxlength="3" size="3" value="<?php echo $member->getPostal1() ?>">-<input type="text" name="postal2" maxlength="4" size="4" value="<?php echo $member->getPostal2() ?>">
      </td>
    </tr>
    <tr>
      <td><label for="address">住所</label></td>
      <td><input id="address" type="text" name="address" maxlength="50" value="<?php echo $member->getAddress() ?>"><span class="announce"><br>※最大50文字</span></td>
    </tr>
    <tr>
      <td><label for="tel">電話番号</label></td>
      <td><input id="tel" type="text" name="tel" maxlength="15" value="<?php echo $member->getTel() ?>"><span class="announce"><br>※半角数字・ハイフン区切り</span></td>
    </tr>
    <tr>
      <td><label for="male">性別</label></td>
      <td>
        <label><input type="radio" name="gender" value="1"<?php if ($member->getGender() === '1') echo ' checked' ?>>男性</label><br>
        <label><input type="radio" name="gender" value="2"<?php if ($member->getGender() === '2') echo ' checked' ?>>女性</label>
      </td>
    </tr>
    <tr>
      <td>年代</td>
      <td>
        <select name="birth">
          <?php outputBirthOptions($member->getBirth()) ?>
        </select>
      </td>
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