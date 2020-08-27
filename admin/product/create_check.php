<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/ProductDao.php');

  $title = '商品追加確認';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  // sanitize
  $post = sanitize($_POST);
  $post_name = $post['name'];
  $post_price = $post['price'];
  $post_release_date = $post['release_date'];
  $post_content = $post['content'];
  $post_pict = $_FILES['pict'];

  // エラーチェック
  $error = array();
  $submit_check = true;

  // 商品名
  if ($post_name === '') {
    $error['post_name'] = '<span class="danger">商品名が入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen($post_name) > 30) {
    $error['post_name'] = '<br><span class="danger">商品名が長すぎます</span>';
    $submit_check = false;
  }
  // 価格
  if ($post_price === '') {
    $error['post_price'] = '<span class="danger">価格が入力されていません</span>';
    $submit_check = false;
  } elseif (preg_match('/\A[0-9]+\z/', $post_price) === 0) {
    $error['post_price'] = '<br><span class="danger">価格を正しく入力してください</span>';
    $submit_check = false;
  }
  // 発売日
  if ($post_release_date === '') {
    $error['post_release_date'] = '<span class="danger">発売日が入力されていません</span>';
    $submit_check = false;
  } elseif (preg_match('/\A[0-9]{4}(-[0-9]{2}){2}\z/', $post_release_date) === 0) {
    $error['post_release_date'] = '<br><span class="danger">発売日を正しく入力してください</span>';
    $submit_check = false;
  } else {
    list($y, $m, $d) = explode('-', $post_release_date);
    if (!checkdate($m, $d, $y)) {
      $error['post_release_date'] = '<br><span class="danger">発売日を正しく入力してください</span>';
      $submit_check = false;
    }
  }
  // 紹介文
  if ($post_content === '') {
    $error['post_content'] = '<span class="danger">紹介文が入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen($post_content) > 1000) {
    $error['post_content'] = '<br><span class="danger">紹介文は1000文字以内で入力してください</span>';
    $submit_check = false;
  }
  // 画像 なければnoimage.png
  $pict_name = 'noimage.png';
  if ($post_pict['error'] === 2 || $post_pict['size'] > 1048576) {
    $error['post_pict'] = '<span class="danger">画像が大き過ぎます（上限:1MB）</span>';
    $submit_check = false;
  } elseif ($post_pict['size'] > 0) {
    // 画像ファイル名決定
    try {
      $dao = new ProductDao();
      // 設定しようとしたファイル名が既存の場合再設定
      do {
        // ランダム2桁+元ファイル名(30字)
        $pict_name = rand(10, 99).mb_substr($post_pict['name'], -28);
      } while ($dao->isAllreadyExists($pict_name));
      // !$submit_check なら長期保存しないためのファイル名に
      if (!$submit_check) {
        // 拡張子だけ取り出し
        $type = '.'.substr($post_pict['name'], strrpos($post_pict['name'], '.') + 1);
        // tmp0~20.拡張子
        $pict_name = 'tmp'.rand(0, 20).$type;
      }
    } catch (PDOException $e) {
      dbError('admin');
    }
    // /pict/tmp/ファイル名 で一時保存
    move_uploaded_file($post_pict['tmp_name'],'./pict/tmp/'.$pict_name);
  }
?>

<h1>商品追加確認</h1>

<form method="post" action="create_do.php">
  <table>
    <tr>
      <td>商品名</td>
      <td>
        <?php echo $post_name ?>
        <?php if(isset($error['post_name'])) echo $error['post_name']; ?>
      </td>
    </tr>
    <tr>
      <td>価格</td>
      <td>
        <?php if(!isset($error['post_price'])): ?>
          <?php echo number_format($post_price) ?>円
        <?php else: ?>
          <?php echo $error['post_price'] ?>
        <?php endif ?>
      </td>
    </tr>
    <tr>
      <td>発売日</td>
      <td>
        <?php echo $post_release_date ?>
        <?php if(isset($error['post_release_date'])) echo $error['post_release_date']; ?>
      </td>
    </tr>
    <tr>
      <td>紹介文</td>
      <td>
        <?php echo nl2br($post_content) ?>
        <?php if(isset($error['post_content'])) echo $error['post_content']; ?>
      </td>
    </tr>
    <tr>
      <td>画像</td>
      <td class="view-img">
        <?php if(!(isset($error['post_pict']))): ?>
          <img src="<?php echo S_NAME ?>admin/product/pict/tmp/<?php echo $pict_name ?>">
        <?php else: ?>
          <?php echo $error['post_pict']; ?>
        <?php endif ?>
      </td>
    </tr>
  </table>

  <p>追加しますか？</p>
  <button type="button" onclick="history.back()">戻る</button>
  <!-- submit可能時に出力 -->
  <?php if($submit_check): ?>
    <input type="submit" value="追加">
    <?php
      $new_product = new Product(null, $post_name, $post_price, $post_content, $pict_name, $post_release_date, null);
      $_SESSION['new_product'] = serialize($new_product);
    ?>
  <?php endif ?>
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>