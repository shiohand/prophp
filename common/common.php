<?php

define('D_ROOT', $_SERVER['DOCUMENT_ROOT'].'/prophp/');
define('S_NAME', 'http://'.$_SERVER['SERVER_NAME'].'/prophp/');
define('URL', htmlspecialchars($_SERVER['PHP_SELF']));
define('URI', htmlspecialchars($_SERVER['REQUEST_URI']));

// session_start()
function sessionStart() {
  session_start();
  session_regenerate_id(true);
}
// 未ログインを弾く
function reqLoginAdmin() {
  session_start();
  session_regenerate_id(true);
  if (!isset($_SESSION['staff_login'])) {
    header('Location: '.S_NAME.'admin/no_login.php');
    exit();
  }
}
function reqLoginShop() {
  session_start();
  session_regenerate_id(true);
  if (!isset($_SESSION['member_login'])) {
    header('Location: '.S_NAME.'shop/no_login.php');
    exit();
  }
}
// ログイン済みを弾く
function blockLoginAdmin() {
  session_start();
  if (isset($_SESSION['staff_login'])) {
    header('Location: '.S_NAME.'admin/already_login.php');
    exit();
  }
}
function blockLoginShop() {
  session_start();
  if (isset($_SESSION['member_login'])) {
    header('Location: '.S_NAME.'shop/already_login.php');
    exit();
  }
}
// カートが空を弾く
function blockCartEmpty() {
  if (!isset($_SESSION['cart'])) {
    print 'カートが空です<br>';
    print '<a href="'.S_NAME.'shop/top.php">トップページへ</a>';
    include(D_ROOT.'component/footer_shop.php');
    exit();
  }
}

// エラーメッセージとフッターとexit()
function commonError($from) {
  print '<br><a href="'.S_NAME.$from.'/top.php">トップページへ戻る</a>';
  include(D_ROOT.'component/footer_'.$from.'.php');
  exit();
}
function dbError($from) {
  print 'ただいま障害により大変ご迷惑をお掛けしております。';
  print '<a href="'.S_NAME.$from.'/top.php">トップページへ戻る</a>';
  // print $e->getMessage();
  include(D_ROOT.'component/footer_'.$from.'.php');
  exit();
}
// 配列にhtmlspecialchars()をかけて戻す
function sanitize(array $before): array {
  $after = array();
  foreach($before as $key => $val) {
    $after[$key] = htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
  }
  return $after;
}

// いろいろつくり

// 単純なリストをつくり機(href設定でaタグ) $item = ['content', 'href'];
function outputNavLis(array $items) {
  foreach ($items as $item) {
    echo '<li>';
    // hrefがあればaタグ、なければcontentのみ
    if ($item[1]) {
      echo '<a href="'.$item[1].'">'.$item[0].'</a>';
    } else {
      echo $item[0];
    }
    echo '</li>';
  }
}
// member 年代のselectを出力
function outputBirthOptions($selected = '1980') {
  $now = round(intval(date('Y')), -1);
  for ($i = 1910; $i <= $now; $i += 10) {
    echo '<option value="'.$i.'"';
    if (strval($i) === $selected) {
      echo ' selected';
    }
    echo '>'.$i.'年代</option>';
  }
}
// ratingを出力
function outputRating($rating = '3') {
  $roop = intval($rating);
  for($i = 0; $i < $roop; $i++) {
    echo '★';
  }
  for($i = 0; $i < 5 - $roop; $i++) {
    echo '☆';
  }
}
// valの数だけhttp_build_query + URLと結合
function buildQuerys(string $key, array $vals, array $inherit = array(), string $url = URL) {
  $urls = array();
  foreach ($vals as $val) {
    $inherit[$key] = $val;
    $urls[$val] = $url.'?'.http_build_query($inherit);
  }
  return $urls;
}
// 現在のリクエストのクエリを分解、buildQuerysなどの材料に
function buildInheritQuerysArr(string $except): array {
  parse_str(($_SERVER['QUERY_STRING']), $querys);
  // 指定したキーがあれば除外
  unset($querys[$except]);
  foreach(array_keys($querys) as $key) {
    // 想定されないキーは除外
    if (!in_array($key, ['interv', 'term', 'sort', 'p'])) {
      unset($querys[$key]);
    }
  }
  return $querys;
}
function createPager($page, $count, $per_page):string {
  $total_page = ceil($count / $per_page);
  if ($total_page == 0) {
    $total_page = 1;
    $page = $total_page;
  }
  if ($total_page < $page) {
    $page = $total_page;
    $is_page_over = true;
  } else {
    $is_page_over = false;
  }
  $prev = max($page - 1, 1);
  $prev2 = max($page - 2, 1);
  $next = min($page + 1, $total_page);
  $next2 = min($page + 2, $total_page);

  // クエリつきURL作成
  $p_array = [1, $prev2, $prev, $next, $next2, $total_page];
  $inherit = buildInheritQuerysArr('p');
  $urls = buildQuerys('p', $p_array, $inherit);

  // 現在のページ位置によって出力変更
  $arr = array();
  // prev側
  switch ($page - 1) {
    case 0:
      break;
    case 1:
      $arr[] = '<a href="'.$urls[$prev].'">'.$prev.'</a>';
      break;
    case 2:
      $arr[] = '<a href="'.$urls[$prev2].'">'.$prev2.'</a>';
      $arr[] = '<a href="'.$urls[$prev].'">'.$prev.'</a>';
      break;
    default:
      $arr[] = '<a href="'.$urls[1].'">1...</a>';
      $arr[] = '<a href="'.$urls[$prev2].'">'.$prev2.'</a>';
      $arr[] = '<a href="'.$urls[$prev].'">'.$prev.'</a>';
      break;
  }
  // 現在のページ(オーバーしていたら最終ページへのリンク)
  if ($is_page_over) {
    $arr[] = '<a href="'.$urls[$total_page].'">'.$total_page.'</a>';
  } else {
    $arr[] = $page;
  }
  // next側
  switch ($total_page - $page) {
    case 0:
      break;
    case 1:
      $arr[] = '<a href="'.$urls[$next].'">'.$next.'</a>';
      break;
    case 2:
      $arr[] = '<a href="'.$urls[$next].'">'.$next.'</a>';
      $arr[] = '<a href="'.$urls[$next2].'">'.$next2.'</a>';
      break;
    default:
      $arr[] = '<a href="'.$urls[$next].'">'.$next.'</a>';
      $arr[] = '<a href="'.$urls[$next2].'">'.$next2.'</a>';
      $arr[] = '<a href="'.$urls[$total_page].'">...'.$total_page.'</a>';
      break;
  }

  // 結合
  $output = 'pager : ';
  foreach ($arr as $item) {
    $output .= $item."\n";
  }
  return $output;
}
?>