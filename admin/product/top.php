<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();
  
  require_once(D_ROOT.'database/ProductDao.php');

  $title = '商品一覧';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  try {
    $dao = new ProductDao();
    $products = $dao->findAll();
?>

<h1>商品一覧</h1>

<a href="create.php">商品追加</a>

<table>
  <tr>
    <td>商品ID</td>
    <td>商品名</td>
    <td>価格</td>
    <td></td>
  </tr>
  <?php foreach($products as $product): ?>
    <?php $param = http_build_query(['id' => $product->getId()]) ?>
    <tr>
      <td><?php echo $product->getId() ?></td>
      <td><a href="view.php?<?php echo $param ?>"><?php echo $product->getName() ?></a></td>
      <td><?php echo number_format($product->getPrice()) ?>円</td>
      <td>
        <a href="edit.php?<?php echo $param ?>">修正</a>
        <a href="delete.php?<?php echo $param ?>">削除</a>
      </td>
    </tr>
  <?php endforeach ?>
</table>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>