<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT.'database/CartItem.php');
  
  $title = 'ショッピングカート';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  // カート内容取得
  $cart = array();
  if (isset($_SESSION['cart'])) {
    $cart = unserialize($_SESSION['cart']);
  }
  // 数量変更・削除
  if (!empty($_POST)) {
    // 削除後の添字ずれを避けるために末尾から処理
    $len = count($cart);
    for ($i = $len - 1; $i >= 0; $i--) { 
      // 数量変更 postされた値
      $q = inputPost('quantity'.$i);
      if ((preg_match("/\A[0-9]+\z/", $q) === 0 || $q < 1)) {
        $cart[$i]->setQuantityError('<br><span class="danger">変更に失敗しました</span>');
      } else {
        // quantityに代入
        $cart[$i]->setQuantity($q);
        $cart[$i]->setQuantityError('');
      }
      // 削除
      if (isset($_POST['delete'.$i])) {
        array_splice($cart, $i, 1);
      }
    }
    $_SESSION['cart'] = serialize($cart);
  }
  // ただの集計用
  $total = ['price' => 0, 'quantity' => 0];
?>

<h1>ショッピングカート</h1>

<?php if (!empty($cart)): ?>

  <form method="post" action="cart.php">
    <input type="submit" value="数量変更・チェックした商品の削除を実行">
    
    <table>
      <?php foreach ($cart as $item): ?>
        <?php
          $product = $item->getProduct();
          $index = array_search($item, $cart);
          $price = $product->getPrice();
          $quantity = $item->getQuantity();
          $quantity_error = $item->getQuantityError();
          
          $total['quantity'] += $quantity;
          $total['price'] += $price * $quantity;

          $param = http_build_query(['id' => $product->getId()]);
        ?>
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
            <?php echo number_format($price) ?>円
          </td>
          <!-- 数量 -->
          <td>
            数量: <input id="quantity" type="number" name="quantity<?php echo $index ?>" value="<?php echo $quantity ?>" min="1">個
            <?php if ($quantity_error) echo $quantity_error ?>
          </td>
          <!-- 小計 -->
          <td>
            小計: <?php echo number_format($price * $quantity) ?>円
          </td>
          <td>
            <label><input id="delete" type="checkbox" name="delete<?php echo $index ?>"></label>
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

    <br>
    <input type="submit" value="数量変更・チェックした商品の削除を実行">
  </form>

  <a href="clear_cart.php">カートを空にする</a>

  <br>
  <a href="order/order.php">購入手続きへ進む</a>
  <a href="order/order_member.php">会員かんたん注文へ進む</a>

<?php else: ?>
  <table>
      <tr>
        <td>現在カートに商品はありません</td>
      </tr>
  </table>
<?php endif ?>

<?php include(D_ROOT.'component/footer_shop.php') ?>