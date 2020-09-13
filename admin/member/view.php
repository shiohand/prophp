<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');
  
  $title = '会員参照';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqGet('id');
  $id = inputGet('id');

  try {
    $dao = new MemberDao();
    $member = $dao->findById($id);
    blockModelEmpty($member);
    $param = http_build_query(['id' => $member->getId()]);
?>

<h1>会員参照</h1>

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

<a href="edit.php?<?php echo $param ?>">修正</a><br>
<a href="delete.php?<?php echo $param ?>">削除</a><br>
<button type="button" onclick="history.back()">戻る</button>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>