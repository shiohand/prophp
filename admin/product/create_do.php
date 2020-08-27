<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/ProductDao.php');
?>
<?php
  if (isset($_SESSION['new_product'])) {
    $new_product = unserialize($_SESSION['new_product']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }
  $new_pict = $new_product->getPict();
  
  try {
    $dao = new ProductDao();
    $product = $dao->create($new_product);
    
    // 画像保存を確定 noimage.pngの場合を除く
    if ($new_pict !== 'noimage.png' && is_file('./pict/tmp/'.$new_pict)) {
      rename('./pict/tmp/'.$new_pict, './pict/'.$new_pict);
    }

    $_SESSION['msg'] = '商品:'.$new_product->getName().' を追加しました';
    
    unset($_SESSION['new_product']);
    
    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError('admin');
  }
?>