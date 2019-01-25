<?php
// 共通変数・関数ファイル読み込み
require "functions.php";
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// ログイン認証
require "auth.php";
debug('認証完了');
?>
<?php
// 画面表示用データ取得
//================================
// カレントページのGETパラメータを取得
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;
// パラメータに不正な値が入っているかチェック
if(!is_int((int)$currentPageNum)){
  error_log('エラー発生：指定ページに不正な値が入りました');
  header("Location:mypage.php");
}

// 投稿表示数
$listSpan = 20;
// 現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan);
// DBから商品データを取得
$dbPostData = getPostList($_SESSION['user_id'],$currentMinNum);
debug('ポスト情報の取得');
$siteTitle = "トップ";
require "head.php";
?>

<body class="page-3colum page-logined">
<?php
  require "header.php";
?>

<!-- メインコンテンツ -->

<?php
// ユーザー情報取得
$dbFormData = getUser($_SESSION['user_id']);
$username = $dbFormData['name'];
debug('ユーザー情報をgetUserで取得');
?>

<p id="js-show-msg" class="msg-slide">
  <?php
  if(!empty($_SESSION['err_msg'])){
    echo '<p id="js-show-err-msg" class="msg-slide">';
    echo getSessionFlash('err_msg');
    echo '</p>';
  }else if(!empty($_SESSION['msg_success'])){
    echo '<p id="js-show-msg" class="msg-slide">';
    echo getSessionFlash('msg_success');
    echo '</p>';
  }
  ?>
</p>
<div id="contents" class="site-width">
<section id="profile-bar">
  <div class="prof-head">
    <div class="prof-img-head">
      <img src="<?php if(!empty($dbFormData['header_path'])) echo validImagePath($dbFormData['header_path']);?>">
    </div>
  </div>
  <div class="prof-body">
    <div class="avater-wrap">
      <div class="prof-img-avater">
        <img src="<?php if(!empty($dbFormData['profile_path'])) echo validImagePath($dbFormData['profile_path']);?>">
      </div>
    </div>
    <div class="prof-name-wrap">
      <span class="prof-name"><?php if(!empty($username)) echo $username;?></span>
    </div>
    <div class="prof-statusList">
      <li><span>ツイート</span><span class="num">100</span></li>
      <li><span>フォロー</span><span class="num">100</span></li>
      <li><span>フォロワー</span><span class="num">100</span></li>
    </div>
  </div>
</section>

<section id="main">
  <!-- 投稿エリア -->
  <?php
    require "postPart.php";
    debug('postPartを使って投稿フォームの形成');
  ?>
  <!-- 投稿取得エリア -->
  <?php
    $posts = $dbPostData['data'];
    require "postList.php";
    debug('postListによって投稿の一覧を取得');
  ?>

  <?php
    require "pagination.php";
  ?>
</section>

<section id="side-bar">
</section>

<?php
require "footer.php";
?>
