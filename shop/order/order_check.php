<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  sessionStart();

  require_once(D_ROOT.'database/MemberDao.php');
  require_once(D_ROOT.'database/CartItem.php');

  $title = '注文内容確認';
  include(D_ROOT.'component/header_shop.php');

  blockCartEmpty();
?>
<?php
  // sanitize
  $post = sanitize($_POST);
  $post_email = $post['email'];
  $post_name = $post['name'];
  $post_postal1 = $post['postal1'];
  $post_postal2 = $post['postal2'];
  $post_address = $post['address'];
  $post_tel = $post['tel'];
  $post_with_signup = $post['with_signup'];
  $post_password = $post['password'] ?? '';
  $post_password2 = $post['password2'] ?? '';
  $post_gender = $post['gender'];
  $post_birth = $post['birth'];

  // with_signup
  if ($post_with_signup === 'signup') {
    $signup = true;
    $signup_radio = ['disabled', 'checked'];
  } else {
    $signup = false;
    $signup_radio = ['checked', 'disabled'];
    // 一応削除
    $post_password = '';
    $post_password2 = '';
    $post_gender = '';
    $post_birth = '';
  }

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
    // 会員登録しないならかぶってもよい多分
    if ($signup) {
      try {
        $dao = new MemberDao();
        if ($dao->isAllreadyExists($post_email)) {
          $error['post_email'] = '<br><span class="danger">このメールアドレスは既に登録されています</span>';
          $submit_check = false;
        }
      } catch (PDOException $e) {
        dbError('shop');
      }
    }
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
  if ($signup) {
    // パスワード
    if ($post_password === '') {
      $error['post_password'] = '<span class="danger">パスワードが入力されていません</span>';
      $submit_check = false;
    } elseif ($post_password != $post_password2) {
      $error['post_password'] = '<span class="danger">パスワードが一致しません</span>';
      $submit_check = false;
    }
    // 性別
    // 年代
  }
?>

<h1>注文内容確認</h1>

<h2>注文者情報</h2>

<form method="post" action="order_do.php">
  
  <input type="radio" name="with_signup" value="guest" <?php echo $signup_radio[0] ?>>今回だけの注文
  <input type="radio" name="with_signup" value="signup" <?php echo $signup_radio[1] ?>>注文と同時に会員登録
  <table>
    <tr>
      <td>メールアドレス</td>
      <td>
        <?php echo $post_email ?>
        <?php if (isset($error['post_email'])) echo $error['post_email'] ?>
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
    <!-- signup -->
    <?php if ($signup): ?>
      <tr>
        <td>パスワード</td>
        <td>
          <?php if (!isset($error['post_password'])) echo '**********'; ?>
          <?php if (isset($error['post_password'])) echo $error['post_password'] ?>
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
    <?php endif ?>
  </table>

  <p>こちらの内容でお間違いありませんか？</p>
  <button type="button" onclick="history.back()">戻る</button>
  <!-- submit可能時 -->
  <?php if($submit_check): ?>
    <input type="submit" value="注文を確定する">
    <?php
      // パスワード変換は登録するときだけ
      if ($signup) {
        $post_password = md5($post_password);
      }
      $orderer = new Member(0, $post_email, $post_password, $post_name, $post_postal1, $post_postal2, $post_address, $post_tel, $post_gender, $post_birth, null);
      $_SESSION['orderer'] = serialize($orderer);
    ?>
  <?php endif ?>
</form>

<?php
  $cart = unserialize($_SESSION['cart']);
  $total = ['price' => 0, 'quantity' => 0];
?>

<h2>カートの中の商品</h2>

<table>
  <?php foreach ($cart as $item): ?>
    <?php
      $product = $item->getProduct();
      $index = array_search($item, $cart);
      $price = $product->getPrice();
      $quantity = $item->getQuantity();

      $total['quantity'] += $quantity;
      $total['price'] += $price * $quantity;
    ?>
    <tr>
      <!-- index -->
      <td>
        <?php echo $index + 1 ?>
      </td>
      <!-- 商品名 -->
      <td>
        <?php echo $product->getName() ?>
      </td>
      <!-- 価格 -->
      <td>
        <?php echo number_format($price) ?>円
      </td>
      <!-- 数量 -->
      <td>
        数量: <?php echo $quantity ?>個
      </td>
      <!-- 小計 -->
      <td>
        小計: <?php echo number_format($price * $quantity) ?>円
      </td>
    </tr>
  <?php endforeach ?>
</table>
<table>
  <tr>
    <td>
      合計点数: <?php echo $total['quantity'] ?>点
    </td>
    <td>
      合計金額: <?php echo number_format($total['price']) ?>円
    </td>
  </tr>
</table>

<?php include(D_ROOT.'component/footer_shop.php') ?>