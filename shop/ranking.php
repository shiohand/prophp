<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT.'database/ProductDao.php');
  require_once(D_ROOT.'common/outputOrders.php');

  $title = 'ランキング';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  // BETWEEN
  $get_term = inputGet('term');
  list($termAs, $betweenAnd) = getTerms($get_term, 'dat_sales_product.created_at');

  try {
    $dao = new ProductDao();
    $rnk_products = $dao->getForRank($betweenAnd, 10);

    $rnk_count = 1;
?>

<h1>ランキング</h1>

<h2>ランキング(売上額)</h2>

<p>
  <?php echo $termAs['all'] ?>
  <?php echo $termAs['day'] ?>
  <?php echo $termAs['week'] ?>
  <?php echo $termAs['month'] ?>
  <?php echo $termAs['year'] ?>
</p>

<table>
  <?php if ($rnk_products[0]->getId()): ?>
    <?php foreach($rnk_products as $product): ?>
      <?php $param = http_build_query(['id' => $product->getId()]) ?>
      <tr>
        <!-- ランク -->
        <td>
          <?php echo $rnk_count++ ?>位
        </td>
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