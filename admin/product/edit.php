<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/ProductDao.php');

  $title = '商品修正';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  $get = sanitize($_GET);
  $id = $get['id'];

  try {
    $dao = new ProductDao();
    $product = $dao->findById($id);
    $_SESSION['product'] = serialize($product);
?>

<h1>商品修正</h1>

<form method="post" action="edit_check.php" enctype="multipart/form-data">
  <table>
    <tr>
      <td>商品ID</td>
      <td><?php echo $product->getId() ?></td>
    </tr>
    <tr>
      <td><label for="name">商品名</label></td>
      <td><input id="name" type="text" name="name" maxlength="30" value="<?php echo $product->getName() ?>"><span class="announce"><br>※最大30文字</span></td>
    </tr>
    <tr>
      <td><label for="price">価格</label></td>
      <td><input id="price" type="text" name="price" value="<?php echo $product->getPrice() ?>"></td>
    </tr>
    <tr>
      <td><label for="release_date">発売日</label></td>
      <td><input id="release_date" type="text" name="release_date" value="<?php echo $product->getReleaseDate() ?>"></td>
    </tr>
    <tr>
      <td><label for="content">紹介文</label></td>
      <td><textarea id="content" name="content" maxlength="1000" cols="30" rows="10"><?php echo $product->getContent() ?></textarea><span class="announce"><br>※最大1000文字</span></td>
    </tr>
    <tr>
      <td rowspan="2">画像</td>
      <td class="view-img">
          <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
          <br>
      </td>
    </tr>
    <tr>
      <td>
        変更する:
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
        <input type="file" name="pict" accept="image/*">
      </td>
    </tr>
  </table>

  <button type="button" onclick="history.back()">戻る</button>
  <input type="submit" value="確認">
</form>

<?php
  } catch (PDOException $e) {
    dbError('admin');
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>