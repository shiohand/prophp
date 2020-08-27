<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginShop();

  require_once(D_ROOT.'database/MemberDao.php');
  
  $title = '登録解除';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  $id = $_SESSION['member_id'];

  try {
    $dao = new MemberDao();
    $user = $dao->findById($id);
    $_SESSION['del_user'] = serialize($user);
?>

<h1>登録解除</h1>

<form method="post" action="delete_do.php">
  <table>
    <tr>
      <td>メールアドレス</td>
      <td><?php echo $user->getEmail() ?></td>
    </tr>
    <tr>
      <td>お名前</td>
      <td><?php echo $user->getName() ?></td>
    </tr>
    <tr>
      <td>郵便番号</td>
      <td><?php echo $user->getPostal1() ?>-<?php echo $user->getPostal2() ?></td>
    </tr>
    <tr>
      <td>住所</td>
      <td><?php echo $user->getAddress() ?></td>
    </tr>
    <tr>
      <td>電話番号</td>
      <td><?php echo $user->getTel() ?></td>
    </tr>
    <tr>
      <td>性別</td>
      <td>
        <?php if ($user->getGender() === '1'): ?>
          男性
        <?php elseif ($user->getGender() === '2'): ?>
          女性
        <?php endif ?>
      </td>
    </tr>
    <tr>
      <td>年代</td>
      <td><?php echo $user->getBirth() ?>年代</td>
    </tr>
  </table>
  
  <p>登録を解除してデータを削除します。よろしいですか？</p>
  <button type="button" onclick="history.back()">戻る</button>
  <input type="submit" value="登録を解除">
</form>

<?php
  } catch (PDOException $e) {
    dbError('shop');
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>