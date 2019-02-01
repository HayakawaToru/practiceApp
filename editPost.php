<?php
  // 共通変数と関数を読み込み
  require "functions.php";
  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debug('「　編集・削除処理開始　');
  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debugLogStart();
  // 認証
  require "auth.php";

  // GETの値によって編集ページと削除ページの切り替えを行う
  if($_GET['page_id'] == 1 || $_GET['page_id'] == 2){
    debug('GET情報の確認'.print_r($_GET,true));

    // GETから編集対象のpost_idを取得し,投稿情報を取得
    $getOnePost = getOnePost($_GET['post_id']);

    // // p_idが１で編集機能、２で削除機能を展開
    // if($_GET['page_id'] == 1){
    //
    //
    // }else if($_GET['page_id'] == 2){
    //
    // }else{
    //   debug('$_GET値が不正に操作されているのでマイページへ遷移');
    //   header("Loaction:mypage.php");
    // }

  }else{
    debug('$_GETの値受け取りに失敗');
    header("Location:mypage.php");
  }

  if($_GET['page_id'] == 1){
    $siteTitle = "投稿編集";
  }else{
    $siteTitle = "投稿削除";
  }

  require "head.php";
?>

<body class="page-1colum">
<!-- ヘッダー -->
  <?php require "header.php";?>

<!-- メイン -->
  <div id="contents" class="site-width">
    <div id="main">
      <div class="form-wrap">
        <div class="each-post-wrap">
          <div class="each-post-content">
            <div class="user-img">
              <img src="<?php echo $profile_path;?>">
            </div>
            <div class="post-user-name">
              <a href="profPage.php?u_id=<?php echo $post['id'];?>">
                <?php echo $postUserName; ?>
              </a>
            </div>
            <div class="js-open-edit-menu">
              <i class="fas fa-angle-down "></i>
              <!-- 編集・削除用モーダルウインドウエリア -->
              <div class="post-edit-wrap">
                <!-- $_GETのpage_idで編集と削除画面の分岐、post_idで編集対象を指定 -->
                <li><a href="editPost.php?page_id=1&post_id=<?php echo $post['id'] ;?>">編集する</a></li>
                <li><a href="editPost.php?page_id=2&post_id=<?php echo $post['id'] ;?>">削除する</a></li>
              </div>
            </div>
          <div class="user-post">
            <?php echo $post['post']; ?>
          </div>
            <?php if(!empty($media_path_01)) {?>
              <div class="post-media_path">
                <img src='<?php echo $path;?>'>
              </div>
            <?php } ?>
            <div class="bottom-status">
              <ul>
                <li class="post-status">
                  <i class="far fa-heart js-click-like <?php if (isLike($post['post_id'],$_SESSION['user_id'])){ echo 'active';}?>" data-postid="<?php echo $post['post_id'];?>"></i>
                  <!-- php echo countFav($post['post_id']); -->
                </li>
              </ul>
            </div>
          </div>

        </div>
        <form>
          <input type="hidden" name="post_id" value="<?php if(isset($getOnePost['post_id'])) echo $getOnePost['post_id']; ?>">
          <input type="">
        </form>
      </div>
    </div>
  </div>

<?php
require "footer.php";
?>
