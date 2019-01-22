<?php
// 共通変数・関数ファイルを読込み
require "functions.php";

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　プロフィール　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require "auth.php";
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
$dbPostData = getPostList($currentMinNum);

$siteTitle = 'プロフィール';
require "head.php";
?>

  <body class="page-3colum page-logined">
    <?php
    require "header.php";
    ?>

    <?php
    // フォーム初期入力値を取得＋変数格納
    $dbFormData = getUser($_SESSION['user_id']);
    debug('取得したユーザー情報：'.print_r($dbFormData,true));

    $user_id = $dbFormData['id'];
    $create_date = $dbFormData['create_date'];
    $username = $dbFormData['name'];
    $bio = $dbFormData['bio'];
    ?>
    <!-- メッセージエリア -->

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

    <!-- プロフィールページヘッダー -->
    <div class="profPage-headwrap">

      <div class="profPage-head-img">
        <img src="<?php if(!empty($dbFormData['header_path'])) echo validImagePath($dbFormData['header_path']);?>">
      </div>

      <div class="profPage-head-img-wrap">
        <div class="profPage-main-img">
          <img src="<?php if(!empty($dbFormData['profile_path'])) echo validImagePath($dbFormData['profile_path']);?>">
        </div>
      </div>

      <div class="profPage-statusList">
        <ul>
          <li><span>ツイート</span><span class="num">100</span></li>
          <li><span>フォロー</span><span class="num">100</span></li>
          <li><span>フォロワー</span><span class="num">100</span></li>
        </ul>
        <div class="profEdit-btn-wrap">
          <button id="profEdit-btn" class="profEdit-btn">プロフィールを編集</button>
        </div>
      </div>
    </div>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">

      <section id="profile-bar">
        <div class="userInfo">
          <div class="username-wrap">
            <span class="username"><?php echo $dbFormData['name'];?></span>
          </div>
          <div class="bio-wrap">
            <span class="prof-bio"><?php echo $dbFormData['bio'];?></span>
          </div>
        </div>
      </section>

      <section id="main">
        <?php
          //投稿機能部分を読み込み
          require "postPart.php";
          // ログイン者投稿を取得＋表示している
          $posts = getUserPost($user_id);
          require "postList.php";

          require "pagination.php";
        ?>

      </section>

      <section id="side-bar">
        <a href="passForm.php">パスワードを変更する</a>
        <a href="withdraw.php">退会する</a>
      </section>

      <!-- プロフィール編集フォーム -->
      <div id="whiteLayer"></div>
      <div id="overLayer">
        <form action="profEdit.php" method="post" enctype="multipart/form-data">
          <input type="text" name="username" placeholder="名前" value="<?php if(!empty($username)) echo $username;?>">
          <textarea name="bio" placeholder="自己紹介" cols="9" rows="16" maxlength="146"><?php if(!empty($bio)) echo $bio;?></textarea>
          <input type="file" name="header-img" value="<?php echo getFormData('header_path');?>">
          <input type="file" name="prof-img" valuej="<?php echo getFormData('profile_path');?>">
          <button class="btn cancel-btn">キャンセルする</button>
          <input type="submit" value="編集を保存する" class="send-save-btn">
        </form>
      </div>

    </div>

<?php
  require "footer.php";
?>
