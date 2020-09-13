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
  $post_at_start = inputPost('at_start');
  $post_at_end = inputPost('at_end');
  $post_product_ids = inputPost('product_ids');

  if (inputPost('submit_clear') === 'クリア') {
    $post_at_start = '';
    $post_at_end = '';
    $post_product_ids = '';
  }

  // id
  if ($post_product_ids && !preg_match('/\A\d+(,\d+)*\z/', $post_product_ids)) {
    $post_product_ids = 'error';
  };

  // BETWEEN
  $get_term = inputGet('term');
  list($termAs, $betweenAnd) = getTerms($get_term, 'dat_sales_product.created_at');

  // BETWEEN
  $betweenAnd .= makeOptBetween('dat_sales_product.created_at', $post_at_start, $post_at_end);

  // ORDER BY
  $get_sort = inputGet('sort');
  list($sortAs, $orderBy) = getOrderByForPSales($get_sort);

  // LIMIT pager
  $page = pageCheck(inputGet('p', '1'));
  $per_page = 30;
  $offset = ($page - 1) * $per_page;
  try {
    $dao = new OrderDao();
    $count = $dao->getCountForProductSales($post_product_ids, $betweenAnd);
  } catch (PDOException $e) {
    echo $e;
    dbError();
  }
  $pager = createPager($page, $count, $per_page);

  try {
    list($saless, $_SESSION['product_sales_sql']) = $dao->getForProductSales($post_product_ids, $betweenAnd, $orderBy, $offset);
?>

<h1>商品別売上</h1>

<nav class="admin-order-nav">
  <ul>
    <?php
      outputNavLis([
        ['注文管理トップ', 'top.php'],
        ['注文データ', 'order.php'],
        ['総売上', 'sales.php'], 
        ['商品別売上', ''], 
        ['指定商品売上', 'one_product_sales.php'], 
      ]);
    ?>
  </ul>
</nav>

<p><a href="<?php echo URL ?>">リセット</a></p>

<p>
  <?php echo $termAs['all'] ?>
  <?php echo $termAs['day'] ?>
  <?php echo $termAs['week'] ?>
  <?php echo $termAs['month'] ?>
  <?php echo $termAs['year'] ?>
</p>

<form method="post" action="<?php echo URI ?>">
  <p>
    期間指定: <input type="date" name="at_start" value="<?php echo $post_at_start ?>"> から <input type="date" name="at_end" value="<?php echo $post_at_end ?>"> まで
  </p>
  <p>
    <label>商品ID(カンマ区切りで複数選択): <input class="middle-input" type="text" name="product_ids" value="<?php echo $post_product_ids ?>"></label>
  </p>
  <p>
    <input type="submit" value="絞り込み">
    <input type="submit" name="submit_clear" value="クリア">
  </p>
</form>

<div class="pager"><?php echo $pager ?></div>

<table>
  <tr>
    <td>商品ID <?php echo $sortAs['pid'] ?></td>
    <td>商品名</td>
    <td>売上個数(個) <?php echo $sortAs['q'] ?></td>
    <td>売上金額(円) <?php echo $sortAs['pri'] ?></td>
    <td>平均単価(円) <?php echo $sortAs['uni'] ?></td>
  </tr>
  <?php foreach ($saless as $sales):?>
    <tr>
      <!-- 商品ID -->
      <td>
        <?php echo $sales['product_id'] ?>
      </td>
      <!-- 商品名 -->
      <td>
        <?php echo $sales['product_name'] ?>
      </td>
      <!-- 数量 -->
      <td>
        <?php echo $sales['quantity'] ?>
      </td>
      <!-- 小計 -->
      <td>
        <?php echo number_format($sales['total_price']) ?>
      </td>
      <!-- 平均単価 -->
      <td>
        <?php echo number_format($sales['avg_unit_price']) ?>
      </td>
    </tr>
  <?php endforeach ?>
</table>

<div class="pager"><?php echo $pager ?></div>

<form method="post" action="download_csv.php">
  <input type="hidden" name="req" value="product_sales">
  <input type="submit" value="CSVダウンロード">
</form>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>