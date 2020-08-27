<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/ProductDao.php');
?>
<?php
  if (isset($_SESSION['up_product'])) {
    $up_product = unserialize($_SESSION['up_product']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }
  if (isset($_SESSION['before_pict'])) {
    $before_pict = unserialize($_SESSION['before_pict']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('admin');
  }
  $new_pict = $up_product->getPict();
  $before_pict = $_SESSION['before_pict'];

  try {
    $dao = new ProductDao();
    $product = $dao->update($up_product);

    // 画像の変更ある場合
    if ($before_pict !== $new_pict) {
      // 画像保存を確定 変更前の画像を削除 noimage.pngの場合を除く
      if ($new_pict !== 'noimage.png' && is_file('./pict/tmp/'.$new_pict)) {
        rename('./pict/tmp/'.$new_pict, './pict/'.$new_pict);
      }
      if ($before_pict !== 'noimage.png' && is_file('./pict/'.$before_pict)) {
        unlink('./pict/'.$before_pict);
      }
    }
    
    $_SESSION['msg'] = '商品:'.$up_product->getName().' (ID:'.$up_product->getId().') を修正しました';
    
    unset($_SESSION['before_pict']);
    unset($_SESSION['up_product']);
    
    header('Location: done.php');
    exit();
  
  } catch (PDOException $e) {
    dbError('admin');
  }
?>