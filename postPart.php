<!-- 投稿フォーム部分 -->

<div class="tweet-all-wrap">
  <div id="open-tweet-form" class="open-tweet-form">
    <div class="open-tweet-box">
      <span class="open-tweet-box-msg">今どうしてる？</span>
    </div>
  </div>
  <div id="tweet-wrap" class="tweet-wrap hidden-wrap">

    <form action="post.php" method="post" class="form" enctype="multipart/form-data">
      <input type="text" name="tweet" placeholder="いまどうしてる？">
      <!-- 画像投稿エリア -->
      <div class="fa-image-wrap">
        <span class="far fa-image"></span>
      </div>
      <div class="imgDrop-container hidden-wrap">
        <label class="area-drop">
          <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
          <input type="file" name="pic1" class="input-file">
          <img src="<?php echo getFormData('pic1');?>" class="prev-img" style="<?php if(empty(getFormData('pic1'))) echo 'display:none';?>">
            ドラッグ＆ドロップ
        </label>
      </div>
      <input type="submit" class="btn-mid" value="ツイート">
    </form>

  </div>
</div>
