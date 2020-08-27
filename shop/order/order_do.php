<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  sessionStart();
  
  require_once(D_ROOT.'database/Member.php');
  require_once(D_ROOT.'database/OrderDao.php');
  require_once(D_ROOT.'database/CartItem.php');
?>
<?php
  if (isset($_SESSION['orderer'])) {
    $orderer = unserialize($_SESSION['orderer']);
  } else {
    print '<p>エラーが発生しました</p>';
    commonError('shop');
  }
  if (!isset($_SESSION['cart'])) {
    unset($_SESSION['orderer']);
    print '<p>エラーが発生しました</p>';
    commonError('shop');
  }

  $cart = unserialize($_SESSION['cart']);
  $total = ['price' => 0, 'quantity' => 0];

  $post = sanitize($_POST);
  $post_with_signup = $post['with_signup'];
  
  // with_signup 動くかわからん
  if ($post_with_signup === 'signup') {
    $signup = true;
    // guest用の入力がsignupとして送られていないか
    if (!$orderer->getPassword()) {
      unset($_SESSION['orderer']);
      print '<p>エラーが発生しました</p>';
      commonError('shop');
    }
  } else {
    $signup = false;
  }
  // IDが0以外、つまり会員のかんたん注文の場合
  if (false) { // チート用にfalse 本来は$orderer->getId()
    // ログイン中のIDと一致しない注文はエラー
    if ($orderer->getID() !== $_SESSION['member_id']) {
      unset($_SESSION['orderer']);
      print '<p>エラーが発生しました</p>';
      commonError('shop');
    }
  }

  try {
    $dao = new OrderDao();
    $dao->orderResistation($orderer, $cart, $signup);
    unset($_SESSION['cart']);
  } catch (PDOException $e) {
    dbError('shop');
  }
?>
<?php
  // 表示用メッセージ
  $msg = "";

  $msg .= "{$orderer->getName()} 様\n";
  $msg .= "ご注文ありがとうございました\n";
  $msg .= "{$orderer->getEmail()} にメールを送りましたのでご確認ください\n";
  $msg .= "商品は以下の住所に発送させていただきます\n";
  $msg .= "{$orderer->getPostal1()}-{$orderer->getPostal2()}\n";
  $msg .= "{$orderer->getAddress()}\n";
  $msg .= "お客様の電話番号: {$orderer->getTel()}\n";

  if ($signup) {
    $msg .= "\n";
    $msg .= "会員登録が完了いたしました\n";
    $msg .= "次回からメールアドレスとパスワードでログインしてください\n";
    $msg .= "ご注文が簡単にできるようになります\n";
  }

  // メール用メッセージ
  $body = "";

  $body .= "（メール用メッセージ）\n";
  $body .= "\n";
  $body .= "{$orderer->getName()} 様\n";
  $body .= "このたびはご注文ありがとうございました\n";
  $body .= "\n";
  $body .= "ご注文商品\n";
  $body .= "------------\n";
  $body .= "\n";

  foreach ($cart as $item) {
    $product = $item->getProduct();
    $index = array_search($item, $cart);
    $price = $product->getPrice();
    $quantity = $item->getQuantity();

    $total['quantity'] += $quantity;
    $total['price'] += $price * $quantity;

    $body .= $product->getName()."\n";
    $body .= " --- ";
    $body .= number_format($price).'円 x ';
    $body .= $quantity.'個 = ';
    $body .= number_format($price * $quantity)."円 \n";
  }

  $body .= "\n";
  $body .= "送料は無料です\n";
  $body .= "\n";
  $body .= "------------\n";
  $body .= "\n";
  $body .="代金は以下の口座にお振込ください\n";
  $body .="〇〇銀行　〇〇支店　普通口座　１２３４５６７\n";
  $body .="入金確認が取れ次第、梱包、発送させていただきます\n";

  if ($signup) {
    $body .= "\n";
    $body .="会員登録が完了いたしました\n";
    $body .="次回からメールアドレスとパスワードでログインしてください\n";
    $body .="ご注文が簡単にできるようになります\n";
  }

  $body .= "\n";
  $body .= "------------\n";
  $body .="何を扱っているか分からない店\n";
  $body .= "\n";
  $body .= "\n";
  $body .="福岡県福岡市早良区荒江\n";
  $body .="電話 090-9009-9090\n";
  $body .="メール: info@mail.com\n";
  $body .= "------------\n";

?>
<?php

//   // メール ローカルなのでWarningが出る

//   // 顧客側 注文完了メール
//   $title = 'ご注文ありがとうございます。';
//   $header = 'From: info@mail.com';
//   $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');
//   mb_language('Japanese');
//   mb_internal_encoding('UTF-8');
//   mb_send_mail($email, $title, $body, $header);

//   // 店舗側 受注メール
//   $title = 'お客様からご注文がありました。';
//   $header = 'From:'.$email;
//   $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');
//   mb_language('Japanese');
//   mb_internal_encoding('UTF-8');
//   mb_send_mail('info@rokumarunouen.co.jp', $title, $body, $header);

?>
<?php
  $_SESSION['msg'] = [$msg, $body];
  unset($_SESSION['orderer']);
  header('Location: order_done.php');
  exit();
?>