<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginShop();
  
  require_once(D_ROOT.'database/ReviewDao.php');

  $title = 'レビュー履歴';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  $member_id = $_SESSION['member_id'];
  
  try {
    $dao = new ReviewDao();
    $reviews = $dao->findByMemberId($member_id);
?>

<h1>レビュー履歴</h1>

<div class="reviews">
  <table>
    <?php if (!empty($reviews)): ?>
      <?php foreach ($reviews as $review): ?>
        <tr>
          <td>
            商品名: <?php echo $review['product_name'] ?><br>
            評価: <?php outputRating($review['rating']) ?><br>
            本文:<br>
            <div class="review-body">
              <?php echo nl2br($review['body']) ?>
            </div>
            投稿日: <?php echo date('Y/m/d H:i:s', strtotime($review['created_at'])) ?>
          </td>
        </tr>
      <?php endforeach ?>
    <?php else: ?>
      <tr>
        <td>まだレビューがありません</td>
      </tr>
    <?php endif ?>
  </table>
</div>

<?php
  } catch (PDOException $e) {
    dbError('shop');
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>