<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  sessionStart();
  
  require_once(D_ROOT.'database/CartItem.php');

  $title = '注文フォーム';
  include(D_ROOT.'component/header_shop.php');

  blockCartEmpty();
?>

<h1>注文フォーム</h1>

<h2>注文者情報</h2>

<form method="post" action="order_check.php">
    注文と同時に会員登録されますと、次回から入力を省略できます。<br>
    <label><input type="radio" name="with_signup" value="guest" checked>今回だけの注文</label>
    <label><input type="radio" name="with_signup" value="signup">注文と同時に会員登録</label>
  <table>
    <tr>
      <td><label for="email">メールアドレス</label></td>
      <td><input id="email" type="email" name="email" maxlength="50"><span class="announce"><br>※最大50文字</span></td>
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
      <td colspan="2">
        以下、会員登録する場合のみ入力
      </td>
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

  <button type="button" onclick="location.href='<?php echo S_NAME ?>shop/cart.php'">カートへ戻る</button>
  <input type="submit" value="確認">
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

<button type="button" onclick="location.href='<?php echo S_NAME ?>shop/cart.php'">カートへ戻る</button>

<?php include(D_ROOT.'component/footer_shop.php') ?>