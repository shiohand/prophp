<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  reqLoginAdmin();
  
  require_once(D_ROOT.'database/MemberDao.php');

  $title = '会員一覧';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  try {
    $dao = new MemberDao();
    $members = $dao->findAll();
?>
  
<h1>会員一覧</h1>

<a href="create.php">会員追加</a>

<table>
  <tr>
    <td>会員ID</td>
    <td>メールアドレス</td>
    <td>お名前</td>
    <td>性別</td>
    <td>年代</td>
    <td></td>
  </tr>
  <?php foreach($members as $member): ?>
    <?php $param = http_build_query(['id' => $member->getId()]) ?>
    <tr>
      <td><?php echo $member->getId() ?></td>
      <td><a href="view.php?<?php echo $param ?>"><?php echo $member->getEmail() ?></a></td>
      <td><?php echo $member->getName() ?></td>
      <td>
        <?php if ($member->getGender() === '1'): ?>
          男性
        <?php elseif ($member->getGender() === '2'): ?>
          女性
        <?php endif ?>
      </td>
      <td><?php echo $member->getBirth() ?></td>
      <td>
        <a href="edit.php?<?php echo $param ?>">修正</a>
        <a href="delete.php?<?php echo $param ?>">削除</a>
      </td>
    </tr>
  <?php endforeach ?>
</table>

<?php
  } catch (PDOException $e) {
    dbError('admin');
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>