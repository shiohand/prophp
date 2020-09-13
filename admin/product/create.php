<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  $title = '商品追加';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>商品追加</h1>

<form method="post" action="create_check.php" enctype="multipart/form-data">
  <table>
    <tr>
      <td><label for="name">商品名</label></td>
      <td><input id="name" type="text" name="name" maxlength="30"><span class="announce"><br>※最大30文字</span></td>
    </tr>
    <tr>
      <td><label for="price">価格</label></td>
      <td><input id="price" type="text" name="price"></td>
    </tr>
    <tr>
      <td><label for="release_date">発売日</label></td>
      <td><input id="release_date" type="text" name="release_date" placeholder="入力形式:<?php echo date('Y') ?>-01-01"></td>
    </tr>
    <tr>
      <td><label for="content">紹介文</label></td>
      <td><textarea id="content" name="content" maxlength="1000" cols="30" rows="10"></textarea><span class="announce"><br>※最大1000文字</span></td>
    </tr>
    <tr>
      <td>画像</td>
      <td>
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
        <input type="file" name="pict" accept="image/*">
      </td>
    </tr>
  </table>

  <button type="button" onclick="history.back()">戻る</button>
  <input type="submit" value="確認">
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>