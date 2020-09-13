<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT.'database/ProductDao.php');
  require_once(D_ROOT.'database/ReviewDao.php');

  $title = '商品詳細';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  if (isset($_SESSION['member_login'])) {
    $member_id = $_SESSION['member_id'];
  }

  reqGet('id');
  $product_id = inputGet('id');

  try {
    $p_dao = new ProductDao();
    $product = $p_dao->findById($product_id);
    blockModelEmpty($product);
    $r_dao = new ReviewDao();
    $reviews = $r_dao->findByProductId($product->getId());
?>

<h1>商品詳細</h1>

<a href="product.php">商品一覧へ</a>

<form method="post" action="cartin.php">
  <table>
    <tr>
      <td rowspan="4">
        <div class="view-img">
          <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
        </div>
      </td>
      <tr>
        <td>
          商品名<br>
          <?php echo $product->getName() ?>
        </td>
      </tr>
      <tr>
        <td>
          価格<br>
          <?php echo number_format($product->getPrice()) ?>円
        </td>
      </tr>
      <tr>
        <td>
          発売日<br>
          <?php echo date('Y年m月d日', strtotime($product->getReleaseDate())) ?>
        </td>
      </tr>
    </tr>
    <tr>
      <td colspan="2">
        紹介文<br>
        <?php echo nl2br($product->getContent()) ?>
      </td>
    </tr>
  </table>
  <?php if (strtotime($product->getReleaseDate()) <= time()): ?>
    数量選択: <input type="number" name="quantity" value="1" min="1">個
    <input type="hidden" name="target" value="<?php echo $product_id ?>">
    <input type="submit" value="カートに入れる">
  <?php else: ?>
    <?php echo date('Y年m月d日', strtotime($product->getReleaseDate())) ?> 発売予定
  <?php endif ?>
</form>

<div class="review-input">
  <p>レビューを書く</p>
  <?php if (isset($member_id)): ?>
    <?php if (!$r_dao->isAlreadyExists($product_id, $member_id)): ?>
      <form method="post" action="user_menu/review_check.php">
        <span class="announce">※1000文字以内でご入力ください</span>
        <br>
        <textarea name="review_post"></textarea>
        <br>
        <div class="rating-input">
          評価(★1～5)
          <label for="rat1"><input id="rat1" type="radio" name="rating" value="1">1</label>
          <label for="rat2"><input id="rat2" type="radio" name="rating" value="2">2</label>
          <label for="rat3"><input id="rat3" type="radio" name="rating" value="3" checked>3</label>
          <label for="rat4"><input id="rat4" type="radio" name="rating" value="4">4</label>
          <label for="rat5"><input id="rat5" type="radio" name="rating" value="5">5</label>
        </div>
        <input type="hidden" name="target" value="<?php echo $product_id ?>">
        <input type="submit" value="確認">
      </form>
    <?php else: ?>
      <p>
        レビュー済みです
      </p>
    <?php endif ?>
  <?php else: ?>
    <p>
      ログインが必要です<br>
      <a href="login.php">ログイン</a>
      <a href="user_menu/signup.php">会員登録する</a><br>
    </p>
  <?php endif ?>
</div>

<div class="reviews">
  <p>レビュー一覧</p>
  <table>
    <?php if (!empty($reviews)): ?>
      <?php foreach ($reviews as $review): ?>
        <tr>
          <td>
            会員名: <?php echo $review['member_name'] ?><br>
            評価: <?php outputRating($review['rating']) ?><br>
            本文:<br>
            <div class="review-body">
              <?php echo nl2br($review['body']) ?>
            </div>
            投稿日: <?php echo date('Y/m/d H:i:s', strtotime($review['created_at'])) ?>
          </td>
        </tr>
      <?php endforeach ?>
    <?php else: ?>
      <tr>
        <td>まだレビューがありません</td>
      </tr>
    <?php endif ?>
  </table>
</div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>