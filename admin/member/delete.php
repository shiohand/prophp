<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();
  
  require_once(D_ROOT.'database/MemberDao.php');

  $title = '会員削除';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqGet('id');
  $id = inputGet('id');

  try {
    $dao = new MemberDao();
    $member = $dao->findById($id);
    blockModelEmpty($member);
    $_SESSION['del_member'] = serialize($member);
?>

<h1>会員削除</h1>

<form method="post" action="delete_do.php">
  <table>
    <tr>
      <td>会員ID</td>
      <td><?php echo $member->getId() ?></td>
    </tr>
    <tr>
      <td>メールアドレス</td>
      <td><?php echo $member->getEmail() ?></td>
    </tr>
    <tr>
      <td>お名前</td>
      <td><?php echo $member->getName() ?></td>
    </tr>
    <tr>
      <td>郵便番号</td>
      <td><?php echo $member->getPostal1() ?>-<?php echo $member->getPostal2() ?></td>
    </tr>
    <tr>
      <td>住所</td>
      <td><?php echo $member->getAddress() ?></td>
    </tr>
    <tr>
      <td>電話番号</td>
      <td><?php echo $member->getTel() ?></td>
    </tr>
    <tr>
      <td>性別</td>
      <td>
        <?php if ($member->getGender() === '1'): ?>
          男性
        <?php elseif ($member->getGender() === '2'): ?>
          女性
        <?php endif ?>
      </td>
    </tr>
    <tr>
      <td>年代</td>
      <td><?php echo $member->getBirth() ?>年代</td>
    </tr>
  </table>
  
  <p>削除しますか？</p>
  <button type="button" onclick="history.back()">戻る</button>
  <input type="submit" value="削除">
</form>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>