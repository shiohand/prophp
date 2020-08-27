<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginShop();

  require_once(D_ROOT.'database/ReviewDao.php');
  
  if (isset($_SESSION['new_review'])) {
    $new_review = unserialize($_SESSION['new_review']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('shop');
  }
  if (isset($_SESSION['product_data'])) {
    $product_data = unserialize($_SESSION['product_data']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('shop');
  }
  
  $product_param = $product_data['param'];
  $product_name = $product_data['name'];

  try {
    $dao = new ReviewDao();
    $dao->create($new_review);

    $_SESSION['msg'] = '<a href="'.S_NAME.'shop/view.php?'.$product_param.'">'.$product_name.'</a>のレビューを投稿しました';
    
    unset($_SESSION['new_review']);
    
    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError('shop');
  }
?>