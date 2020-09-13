<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  blockLogin();

  require_once(D_ROOT.'database/MemberDao.php');
  
  $title = '会員登録確認';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  reqPost();
  $post_email = inputPost('email');
  $post_password = inputPost('password');
  $post_password2 = inputPost('password2');
  $post_name = inputPost('name');
  $post_postal1 = inputPost('postal1');
  $post_postal2 = inputPost('postal2');
  $post_address = inputPost('address');
  $post_tel = inputPost('tel');
  $post_gender = inputPost('gender');
  $post_birth = inputPost('birth');

  // エラーチェック
  $error = array();
  $submit_check = true;

  // メールアドレス
  if ($post_email === '') {
    $error['post_email'] = '<span class="danger">メールアドレスが入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen($post_email) > 50) {
    $error['post_email'] = '<span class="danger">メールアドレスが長すぎます</span>';
    $submit_check = false;
  } elseif (preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/', $post_email) === 0) {
    $error['post_email'] = '<br><span class="danger">メールアドレスを正確に入力してください</span>';
    $submit_check = false;
  } else {
    try {
      $dao = new MemberDao();
      if ($dao->isAllreadyExists($post_email)) {
        $error['post_email'] = '<br><span class="danger">このメールアドレスは既に登録されています</span>';
        $submit_check = false;
      }
    } catch (PDOException $e) {
      dbError();
    }
  }
  // パスワード
  if ($post_password === '') {
    $error['post_password'] = '<span class="danger">パスワードが入力されていません</span>';
    $submit_check = false;
  } elseif ($post_password != $post_password2) {
    $error['post_password'] = '<span class="danger">パスワードが一致しません</span>';
    $submit_check = false;
  }
  // お名前
  if ($post_name === '') {
    $error['post_name'] = '<span class="danger">お名前が入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen($post_name) > 15) {
    $error['post_name'] = '<br><span class="danger">お名前が長すぎます</span>';
    $submit_check = false;
  }
  // 郵便番号
  if ($post_postal1 === '') {
    $error['post_postal'] = '<span class="danger">郵便番号が入力されていません</span>';
    $submit_check = false;
  } elseif (preg_match('/\A[0-9]{3}\z/', $post_postal1) === 0 || preg_match('/\A[0-9]{4}\z/', $post_postal2) === 0) {
    $error['post_postal'] = '<br><span class="danger">郵便番号を正しく入力してください</span>';
    $submit_check = false;
  }
  // 住所
  if ($post_address === '') {
    $error['post_address'] = '<span class="danger">住所が入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen($post_address) > 50) {
    $error['post_address'] = '<br><span class="danger">住所が長すぎます</span>';
    $submit_check = false;
  }
  // 電話番号
  if ($post_tel === '') {
    $error['post_tel'] = '<span class="danger">電話番号が入力されていません</span>';
    $submit_check = false;
  } elseif (preg_match('/\A0[0-9]{1,4}-[0-9]{1,6}(-[0-9]{0,5})?\z/', $post_tel) === 0) {
    $error['post_tel'] = '<br><span class="danger">電話番号をハイフン区切りで正しく入力してください</span>';
    $submit_check = false;
  } elseif (strlen(str_replace('-', '', $post_tel)) !== 10 &&
  strlen(str_replace('-', '', $post_tel)) !== 11) {
    $error['post_tel'] = '<br><span class="danger">電話番号を正しく入力してください</span>';
    $submit_check = false;
  }
  // 性別
  if ($post_gender !== '1' && $post_gender !== '0') {
    commonError();
  }
  // 年代
  if ($post_birth % 10 !== 0 || $post_birth < 1910 || round(intval(date('Y')), -1) < $post_birth) {
    commonError();
  }
?>

<h1>会員登録確認</h1>

<form method="post" action="signup_do.php">
  <table>
    <tr>
      <td>メールアドレス</td>
      <td>
        <?php echo $post_email ?>
        <?php if (isset($error['post_email'])) echo $error['post_email'] ?>
      </td>
    </tr>
    <tr>
      <td>パスワード</td>
      <td>
        <?php if (!isset($error['post_password'])) echo '**********'; ?>
        <?php if (isset($error['post_password'])) echo $error['post_password'] ?>
      </td>
    </tr>
    <tr>
      <td>お名前</td>
      <td>
        <?php echo $post_name ?>
        <?php if (isset($error['post_name'])) echo $error['post_name'] ?>
      </td>
    </tr>
    <tr>
      <td>郵便番号</td>
      <td>
        <?php echo $post_postal1 ?><?php if ($post_postal1 !== '' && $post_postal2 !== '') echo '-' ?><?php echo $post_postal2 ?>
        <?php if (isset($error['post_postal'])) echo $error['post_postal'] ?>
      </td>
    </tr>
    <tr>
      <td>住所</td>
      <td>
        <?php echo $post_address ?>
        <?php if (isset($error['post_address'])) echo $error['post_address'] ?>
      </td>
    </tr>
    <tr>
      <td>電話番号</td>
      <td>
        <?php echo $post_tel ?>
        <?php if (isset($error['post_tel'])) echo $error['post_tel'] ?>
      </td>
    </tr>
    <tr>
      <td>性別</td>
      <td>
        <?php if ($post_gender === '1'): ?>
          男性
        <?php elseif ($post_gender === '2'): ?>
          女性
        <?php endif ?>
      </td>
    </tr>
    <tr>
      <td>年代</td>
      <td>
        <?php echo $post_birth ?>年代
      </td>
    </tr>
  </table>

  <p>この内容で登録しますか？</p>
  <button type="button" onclick="history.back()">戻る</button>
  <!-- submit可能時 -->
  <?php if($submit_check): ?>
    <input type="submit" value="登録">
    <?php
      $post_password = md5($post_password);

      $new_user = new Member(null, $post_email, $post_password, $post_name, $post_postal1, $post_postal2, $post_address, $post_tel, $post_gender, $post_birth, null);
      $_SESSION['new_user'] = serialize($new_user);
    ?>
  <?php endif ?>
</form>

<?php include(D_ROOT.'component/footer_shop.php') ?>