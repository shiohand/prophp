<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT.'database/ProductDao.php');

  $title = 'トップ';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  $now = date('Y-m-d H:i:s');
?>

<h1>shopトップ</h1>

<!-- ランキング -->
<?php
  try {
    $dao = new ProductDao();

    $betweenAnd = makeOptBetweenByTerm('dat_sales_product.created_at', 'month');
    $rnk_products = $dao->getForRank($betweenAnd, 5);

    $rnk_count = 1;
?>

<h2>ランキング(月間売上額)</h2>

<a href="ranking.php">ランキングの続きへ</a>

<table>
  <?php if ($rnk_products[0]->getId()): ?>
    <?php foreach($rnk_products as $product): ?>
      <?php $param = http_build_query(['id' => $product->getId()]) ?>
      <tr>
        <!-- 順位 -->
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

<a href="ranking.php">ランキングの続きへ</a>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<!-- 新着商品 -->
<?php
  try {
    $dao = new ProductDao();

    $opt = '';
    $opt .= " AND release_date <= '{$now}'";
    $opt .= makeOptOrderBy('release_date', true);
    $products = $dao->getForShop($opt);
?>

<h2>新着商品</h2>

<a href="product.php">商品一覧へ</a>

<table>
  <?php if ($products[0]->getId()): ?>
    <?php foreach($products as $product): ?>
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
      </tr>
    <?php endforeach ?>
  <?php else: ?>
    <tr>
      <td>結果がありません</td>
    </tr>
  <?php endif ?>
</table>

<a href="product.php">商品一覧へ</a>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<!-- 発売予定 -->
<?php
  try {
    $dao = new ProductDao();
    
    $opt = '';
    $opt .= " AND '{$now}' < release_date";
    $opt .= makeOptOrderBy('release_date', false);
    $products = $dao->getForShop($opt);
?>

<h2>発売予定</h2>

<table>
  <?php if ($products[0]->getId()): ?>
    <?php foreach($products as $product): ?>
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
        <!-- 発売日 -->
        <td>
          発売日: <?php echo date('Y年m月d日', strtotime($product->getReleaseDate())) ?>
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