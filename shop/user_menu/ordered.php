<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/OrderDao.php');
  require_once(D_ROOT.'common/outputOrders.php');
  
  $title = '注文履歴';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  $member_id = $_SESSION['member_id'];

  // BETWEEN
  $get_term = inputGet('term');
  list($termAs, $betweenAnd) = getTerms($get_term, 'dat_sales.created_at');

  // LIMIT pager
  $page = pageCheck(inputGet('p', '1'));
  $per_page = 5;
  $offset = ($page - 1) * $per_page;
  try {
    $dao = new OrderDao();
    // countはsales_id単位
    $count = $dao->getCountForOrdered($member_id, $betweenAnd);
  } catch (PDOException $e) {
    dbError();
  }
  $pager = createPager($page, $count, $per_page);

  try {
    $dao = new OrderDao();
    $orders = $dao->getForOrdered($member_id, $betweenAnd, $offset);

    $last_sales_id = 0;
?>

<h1>注文履歴</h1>

<p>
  <?php echo $termAs['all'] ?>
  <?php echo $termAs['day'] ?>
  <?php echo $termAs['week'] ?>
  <?php echo $termAs['month'] ?>
  <?php echo $termAs['year'] ?>
</p>

<div class="pager"><?php echo $pager ?></div>

<?php if (!empty($orders)): ?>

  <table>
    <?php foreach ($orders as $order):?>
      <?php if ($last_sales_id !== $order['sales_id']): ?>
        <tr>
          <td colspan="5">
            注文日時: <?php echo date('Y/m/d H:i:s', strtotime($order['created_at'])) ?>
            <br>
            合計金額: <?php echo number_format($order['final_price']) ?>円
          </td>
        </tr>
        <?php $last_sales_id = $order['sales_id'] ?>
      <?php endif ?>

      <?php $param = http_build_query(['id' => $order['product_id']]) ?>
      <tr>
        <!-- 商品画像 -->
        <td class="pict-frame">
          <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $order['pict'] ?>">
        </td>
        <!-- 商品名 -->
        <td>
          <a href="<?php echo S_NAME ?>shop/view.php?<?php echo $param ?>"><?php echo $order['product_name'] ?></a>
        </td>
        <!-- 価格 -->
        <td>
          <?php echo number_format($order['sales_price']) ?>円
        </td>
        <!-- 数量 -->
        <td>
          数量: <?php echo $order['quantity'] ?>個
        </td>
        <!-- 小計 -->
        <td>
          小計: <?php echo number_format($order['total_price']) ?>円
        </td>
      </tr>
    <?php endforeach ?>
  </table>

<?php else: ?>
  <table>
      <tr>
        <td>該当なし</td>
      </tr>
  </table>
<?php endif ?>

<div class="pager"><?php echo $pager ?></div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>