<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
require_once(D_ROOT.'database/Product.php');

class CartItem {
  private Product $product;
  private $quantity;

  public function __construct(Product $product, $quantity) {
    $this->product = $product;
    $this->quantity = $quantity;
  }

  public function getProduct(): Product {
    return $this->product;
  }
  public function setProduct($product) {
    $this->product = $product;
  }
  public function getQuantity() {
    return $this->quantity;
  }
  public function setQuantity($quantity) {
    $this->quantity = $quantity;
  }
  // 重複検索
  public static function findById($items, $product_id) {
    foreach ($items as $item) {
      if ($product_id === $item->product->getId()) {
        return $item;
      }
    }
    return false;
  }
}
?>