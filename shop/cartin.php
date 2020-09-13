<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();
  
  require_once(D_ROOT.'database/ProductDao.php');
  require_once(D_ROOT.'database/CartItem.php');
  
  $title = 'カートに入れる';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  try {
    reqPost();
    $post_product_id = inputPost('target');
    $post_quantity = inputPost('quantity');

    $dao = new ProductDao();
    $product = $dao->findById($post_product_id);
    blockModelEmpty($product);
    $recommends = $dao->findRecommend($post_product_id);
    $param = http_build_query(['id' => $post_product_id]);

    // 商品の存在チェック、未発売チェック
    if (time() < strtotime($product->getReleaseDate())) {
      commonError();
    }
    // 数量確認
    if ((preg_match("/\A[0-9]+\z/", $post_quantity) === 0 || $post_quantity < 1)) {
      print '数量が不正です<br>';
      print '<a href="'.S_NAME.'shop/product.php">商品一覧へ戻る</a><br>';
      print '<a href="'.S_NAME.'shop/view.php?'.$param.'">商品ページへ戻る</a>';
      print '<br><a href="'.S_NAME.$from.'/top.php">トップページへ戻る</a>';
      include(D_ROOT.'component/footer_'.$from.'.php');
      exit();
    }

    $cart = array();
    if (isset($_SESSION['cart'])) {
      $cart = unserialize($_SESSION['cart']);
      // カート内のチェック
      if (CartItem::findById($cart, $post_product_id)) {
        print 'その商品はすでにカートに入っています。<br>';
        print '<a href="'.S_NAME.'shop/product.php">商品一覧へ戻る</a><br>';
        print '<a href="'.S_NAME.'shop/view.php?'.$param.'">商品ページへ戻る</a>';
        print '<br><a href="'.S_NAME.$from.'/top.php">トップページへ戻る</a>';
        include(D_ROOT.'component/footer_'.$from.'.php');
        exit();
      }
    }
    // CartItem作成、追加
    $cart[] = new CartItem($product, $post_quantity);
    $_SESSION['cart'] = serialize($cart);
?>

<h1>カートに追加しました</h1>

<p>
  <a href="<?php echo S_NAME ?>shop/view.php?<?php echo $param ?>">
    <?php echo $product->getName() ?>
  </a>をカートに追加しました。
  <br>
  数量: <?php echo $post_quantity ?>
</p>
<div class="pict-frame">
  <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
</div>

<a href="cart.php">カートを見る</a>
<br>
<a href="order/order.php">購入手続きへ進む</a>
<a href="order/order_member.php">会員かんたん注文へ進む</a>

<br>
<h2>一緒に購入されることの多い商品</h2>
<table>
  <?php if ($recommends[0]->getId()): ?>
    <?php foreach($recommends as $product): ?>
      <?php $param = http_build_query(['id' => $product->getId()]) ?>
      <tr>
        <!-- 商品画像 -->
        <td class="pict-frame">
          <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
        </td>
        <!-- 商品名 -->
        <td>
          <a href="view.php?<?php echo $param ?>"><?php echo $product->getName() ?></a>
        </td>
        <!-- 価格 -->
        <td>
          <?php echo number_format($product->getPrice()) ?>円
        </td>
        <!-- 数量選択・カートに入れる -->
        <td>
          <form method="post" action="cartin.php">
            数量選択: <input type="number" name="quantity" value="1" min="1">個
            <input type="hidden" name="target" value="<?php echo $product->getId() ?>">
            <input type="submit" value="カートに入れる">
          </form>
        </td>
      </tr>
    <?php endforeach ?>
  <?php else: ?>
    <tr>
      <td>結果がありません</td>
    </tr>
  <?php endif ?>
</table>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>