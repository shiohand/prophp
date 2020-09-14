<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/OrderDao.php');
  require_once(D_ROOT.'common/outputOrders.php');

  $title = '注文管理';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  $post_at_start = dateCheck(inputPost('at_start'));
  $post_at_end = dateCheck(inputPost('at_end'));
  $post_product_id = inputPost('product_id', '1');

  if (inputPost('submit_clear') === 'クリア') {
    $post_at_start = '';
    $post_at_end = '';
    $post_product_id = '1';
  }

  // id
  if ($post_product_id && !preg_match('/\A\d+\z/', $post_product_id)) {
    $post_product_id = 'error';
  };

  // 日別, 月別, ...
  $get_interv = inputGet('interv');
  list($intervAs, $interv) = getIntervs($get_interv);

  // BETWEEN
  $betweenAnd = makeOptBetween('dat_sales_product.created_at', $post_at_start, $post_at_end);

  // ORDER BY
  $get_sort = inputGet('sort');
  list($sortAs, $orderBy) = getOrderByForSalesAndOPSales($get_sort);
  
  // LIMIT pager
  $page = pageCheck(inputGet('p', '1'));
  $per_page = 30;
  $offset = ($page - 1) * $per_page;
  try {
    $dao = new OrderDao();
    $count = $dao->getCountForOneProductSales($post_product_id, $interv, $betweenAnd);
  } catch (PDOException $e) {
    dbError();
  }
  $pager = createPager($page, $count, $per_page);

  try {
    list($saless, $_SESSION['one_product_sales_sql']) = $dao->getForOneProductSales($post_product_id, $interv, $betweenAnd, $orderBy, $offset);
?>

<h1>指定商品売上</h1>

<nav class="admin-order-nav">
  <ul>
    <?php
      outputNavLis([
        ['注文管理トップ', 'top.php'],
        ['注文データ', 'order.php'],
        ['総売上', 'sales.php'], 
        ['商品別売上', 'product_sales.php'], 
        ['指定商品売上', ''], 
      ]);
    ?>
  </ul>
</nav>

<p><a href="<?php echo URL ?>">リセット</a></p>

<p>
  <?php echo $intervAs['day'] ?>
  <?php echo $intervAs['month'] ?>
  <?php echo $intervAs['year'] ?>
</p>

<form method="post" action="<?php echo URI ?>">
  <p>
    期間指定: <input type="date" name="at_start" value="<?php echo $post_at_start ?>"> から <input type="date" name="at_end" value="<?php echo $post_at_end ?>"> まで <input type="submit" value="絞り込み">
  </p>
  <p>
    <label>商品ID: <input type="text" class="short-input" name="product_id" value="<?php echo $post_product_id ?>"></label>
  </p>
  <p>
    <input type="submit" value="絞り込み">
    <input type="submit" name="submit_clear" value="クリア">
  </p>
</form>

<div class="pager"><?php echo $pager ?></div>

<table>
  <tr>
    <td>期間 <?php echo $sortAs['at'] ?></td>
    <td>売上金額(円) <?php echo $sortAs['pri'] ?></td>
  </tr>
  <?php foreach ($saless as $sales):?>
    <tr>
      <!-- 期間 -->
      <td>
        <?php echo $sales['interv'] ?>
      </td>
      <!-- 小計 -->
      <td>
        <?php echo number_format($sales['total_price']) ?>
      </td>
    </tr>
  <?php endforeach ?>
</table>

<div class="pager"><?php echo $pager ?></div>

<form method="post" action="download_csv.php">
  <input type="hidden" name="req" value="one_product_sales">
  <input type="submit" value="CSVダウンロード">
</form>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>