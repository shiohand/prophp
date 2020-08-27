<?php

  $submit_check = true;
  $error = array();

  if ($post === '') {
    // 未入力
  }
  if (mb_strlen($post) > $length) {
    // 文字数
  }
  if ($is_exists) {
    // 重複 ($is_existsはメソッドなどで)
  }
  if (!preg_match('/\A\d+\z/', $post_member_id)) {
    // 整数でない
  }
  if (!preg_match('/\A\d+(,\d+)*\z/', $post_member_id)) {
    // カンマ区切りの整数でない
  }
  if ($post != $post2) {
    // 再入力不一致
  }

  // パスワード
  if (md5($post_password) != $right_password) {
    // 不一致 md5
  }

  // メールアドレス
  if (preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/', $post_email) === 0) {
    // 不正
  }

  // 郵便番号
  if (preg_match('/\A[0-9]{3}\z/', $post_postal1) === 0 || preg_match('/\A[0-9]{4}\z/', $post_postal2) === 0) {
    // 不正 日本
  }

  // 電話番号
  if (preg_match('/\A0[0-9]{1,4}-[0-9]{1,6}(-[0-9]{0,5})?\z/', $post_tel) === 0) {
    // 不正 ハイフン区切り
  }
  if (strlen(str_replace('-', '', $post_tel)) !== 10 && strlen(str_replace('-', '', $post_tel)) !== 11) {
    // 桁数不正 ハイフン除外
  }

  // 日付
  if (preg_match('/\A[0-9]{4}(-[0-9]{2}){2}\z/', $post_date) === 0) {
    // 日付形式でない yyyy-mm-dd
  }
  if (!checkdate($m, $d, $y)) {
    // 有効な日付でない
  }

  // ファイル
  if ($post_file['error'] === 2 || $post_file['size'] > $size) {
    // $_FILE サイズ超過で取得できていない ファイルが指定の$sizeより大きい
  }
?>