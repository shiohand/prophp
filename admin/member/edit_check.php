<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/MemberDAO.php');
  
  $title = '会員修正確認';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  if (isset($_SESSION['member'])) {
    $member = unserialize($_SESSION['member']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }

  // sanitize
  $post = sanitize($_POST);
  $post_email = $post['email'];
  $post_password = $post['password'];
  $post_newpassword = $post['new_password'];
  $post_newpassword2 = $post['new_password2'];
  $post_name = $post['name'];
  $post_postal1 = $post['postal1'];
  $post_postal2 = $post['postal2'];
  $post_address = $post['address'];
  $post_tel = $post['tel'];
  $post_gender = $post['gender'];
  $post_birth = $post['birth'];

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
      if ($post_email !== $member->getEmail() && $dao->isAllreadyExists($post_email)) {
        $error['post_email'] = '<br><span class="danger">このメールアドレスは既に登録されています</span>';
        $submit_check = false;
      }
    } catch (PDOException $e) {
      dbError('admin');
    }
  }
  // パスワード
  if (md5($post_password) != $member->getPassword()) {
    $error['post_password'] = '<span class="danger">パスワードが違います</span>';
    $submit_check = false;
  }
  // 新しいパスワード
  if ($post_newpassword != $post_newpassword2) {
    $error['post_newpassword'] = '<span class="danger">パスワードが一致しません</span>';
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
  } elseif (strlen(str_replace('-', '', $post_tel)) !== 10 && strlen(str_replace('-', '', $post_tel)) !== 11) {
    $error['post_tel'] = '<br><span class="danger">電話番号を正しく入力してください</span>';
    $submit_check = false;
  }
  // 性別
  // 年代
?>

<h1>会員修正確認</h1>

<form method="post" action="edit_do.php">
  <table>
    <tr>
      <td>会員ID</td>
      <td><?php echo $member->getId() ?></td>
    </tr>
    <tr>
      <td>メールアドレス</td>
      <td>
        <?php echo $post_email ?>
        <?php if (isset($error['post_email'])) echo $error['post_email'] ?>
      </td>
    </tr>
    <tr>
      <td>現在のパスワード</td>
      <td>
        <?php if(!isset($error['post_password'])) echo '**********'; ?>
        <?php if(isset($error['post_password'])) echo $error['post_password']; ?>
      </td>
    </tr>
    <tr>
      <td>新しいパスワード</td>
      <td>
        <?php if(!isset($error['post_newpassword']) && $post_newpassword !== '') echo '**********'; ?>
        <?php if(isset($error['post_newpassword'])) echo $error['post_newpassword']; ?>
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

  <p>修正しますか？</p>
  <button type="button" onclick="history.back()">戻る</button>
  <!-- submit可能時 -->
  <?php if($submit_check): ?>
    <input type="submit" value="確定">
    <?php
      // 新しいパスワードが入力されている場合は変更
      if ($post_newpassword !== '') {
        $post_password = md5($post_newpassword);
      }

      $up_member = new Member($member->getId(), $post_email, $post_password, $post_name, $post_postal1, $post_postal2, $post_address, $post_tel, $post_gender, $post_birth, $member->getCreatedAt());
      $_SESSION['up_member'] = serialize($up_member);
      
      unset($_SESSION['member'])
    ?>
  <?php endif ?>
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>