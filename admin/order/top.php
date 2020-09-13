<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  $title = '注文管理';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>注文管理</h1>

<nav class="admin-order-nav">
  <ul>
    <?php
      outputNavLis([
        ['注文管理トップ', ''],
        ['注文データ', 'order.php'],
        ['総売上', 'sales.php'], 
        ['商品別売上', 'product_sales.php'], 
        ['指定商品売上', 'one_product_sales.php'], 
      ]);
    ?>
  </ul>
</nav>

<form method="post" action="sales_data.php">
  <label for="sales_id">注文ID: <input id="sales_id" class="short-input" type="text" name="sales_id"></label>
  <input type="submit" value="注文詳細表示">
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>