<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  sessionStart();

  require_once(D_ROOT.'database/ProductDao.php');
  require_once(D_ROOT.'common/outputOrders.php');
  
  $title = '商品一覧';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  $get = sanitize($_GET);
  
  // ORDER BY
  $get_sort = $get['sort'] ?? '';
  list($sortAs, $orderBy) = getOrderByForProduct($get_sort);

  // LIMIT pager
  $page = $get['p'] ?? '1';
  $per_page = 20;
  $offset = ($page - 1) * $per_page;
  try {
    $dao = new ProductDao();
    $count = $dao->getCountAllWithRating();
  } catch (PDOException $e) {
    dbError('shop');
  }
  $pager = createPager($page, $count, $per_page);

  try {
    $products = $dao->findAllWithRating($orderBy, $offset);
?>

<h1>商品一覧</h1>

<p>
  <?php echo $sortAs['rat'] ?>
  <?php echo $sortAs['pri'] ?>
  <?php echo $sortAs['rpri'] ?>
  <?php echo $sortAs['rev'] ?>
</p>

<div class="pager"><?php echo $pager ?></div>

<table>
  <?php foreach($products as $product): ?>
    <?php $param = http_build_query(['id' => $product['product_id']]) ?>
    <tr>
      <!-- 商品画像 -->
      <td class="pict-frame">
        <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product['pict'] ?>">
      </td>
      <!-- 商品名 -->
      <td>
        <a href="view.php?<?php echo $param ?>"><?php echo $product['name'] ?></a>
      </td>
      <!-- 価格 -->
      <td>
        <?php echo number_format($product['price']) ?>円
      </td>
      <!-- 発売日 -->
      <td>
        発売日:<br>
        <?php echo date('Y年m月d日', strtotime($product['release_date'])) ?>
      </td>
      <!-- 評価平均 -->
      <td>
        評価平均:<br>
        <?php echo $product['rating_result'] ?>
      </td>
    </tr>
  <?php endforeach ?>
</table>

<div class="pager"><?php echo $pager ?></div>

<?php
  } catch (PDOException $e) {
    dbError('shop');
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>