<div class="post-wrap">
  <?php
    // ユーザー情報の取得
    foreach($posts as $post){
      // postテーブルの投稿者idを取り出し、getUserでuser情報を取得
      $postUserInfo = getUser($post['id']);
      $postUserName = $postUserInfo['name'];
      $media_path_01 = $post['media_path_01'];
      // 取得したパスをsrcで表示できるように画像パスの一部を取り除く処理
      $profile_path = validImagePath($postUserInfo['profile_path']);
      $path = validImagePath($media_path_01);
  ?>
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
<?php }?>
</div>
