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
          <div class="post-user-img">
            <img src="<?php echo $profile_path;?>">
          </div>
          <div class="post-user-name">
          <?php echo $postUserName; ?>
          </div>
        <div class="user-post">
          <?php echo $post['post']; ?>
        </div>
          <?php if(!empty($media_path_01)) {?>
            <div class="post-media_path">
              <img src='<?php echo $path;?>'>
            </div>
          <?php } ?>
        </div>
      </div>
<?php }?>
</div>
