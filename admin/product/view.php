<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/ProductDao.php');

  $title = '商品参照';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  $get = sanitize($_GET);
  $id = $get['id'];

  try {
    $dao = new ProductDao();
    $product = $dao->findById($id);
    $param = http_build_query(['id' => $product->getId()]);
?>

<h1>商品参照</h1>

<a href="<?php echo S_NAME ?>shop/view.php?<?php echo $param ?>" target="blank">ショップの商品ページを確認</a><br>
<table>
  <tr>
    <td>商品ID</td>
    <td><?php echo $product->getId() ?></td>
  </tr>
  <tr>
    <td>商品名</td>
    <td><?php echo $product->getName() ?></td>
  </tr>
  <tr>
    <td>価格</td>
    <td><?php echo number_format($product->getPrice()) ?>円</td>
  </tr>
  <tr>
    <td>発売日</td>
    <td><?php echo $product->getReleaseDate() ?></td>
  </tr>
  <tr>
    <td>紹介文</td>
    <td><?php echo nl2br($product->getContent()) ?></td>
  </tr>
  <tr>
    <td>画像</td>
    <td class="view-img">
      <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
    </td>
  </tr>
</table>

<a href="edit.php?<?php echo $param ?>">修正</a><br>
<a href="delete.php?<?php echo $param ?>">削除</a><br>
<button type="button" onclick="history.back()">戻る</button>

<?php
  } catch (PDOException $e) {
    dbError('admin');
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>