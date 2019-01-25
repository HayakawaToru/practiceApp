$(function(){
  // プロフィール編集モーダルウィンドウ
  $('.profEdit-btn').click(function(){
    $('#whiteLayer').show();
    $('#overLayer').show();
  });
  $('.whiteLayer').click(function(){
    $('#whiteLayer').hide();
    $('#overLayer').hide();
  });

  // ツイートフォーム表示/非表示
  $('.open-tweet-box').click(function(){
    $('#open-tweet-form').addClass('hidden-wrap');
    $('#tweet-wrap').removeClass('hidden-wrap');
  });

  $('.fa-image-wrap').click(function(){
    $('.fa-image-wrap').addClass('hidden-wrap');
    $('.imgDrop-container').removeClass('hidden-wrap');
  });

  // メッセージ表示
  var $jsShowMsg = $('#js-show-msg');
  var $jsShowErrMsg = $('#js-show-err-msg');

    var err_msg = $jsShowErrMsg.text();
    if(err_msg.replace(/^[\s　]+|[\s　]+$/g,"").length){
      $jsShowErrMsg.removeClass('msg-slide');
      $jsShowErrMsg.addClass('err-msg-slide-change');
      setTimeout(function(){$jsShowErrMsg.slideToggle('slow');}, 5000);
    }

    var msg = $jsShowMsg.text();
    if(msg.replace(/^[\s　]+|[\s　]+$/g,"").length){
      $jsShowMsg.removeClass('msg-slide');
      $jsShowMsg.addClass('msg-slide-change');
      setTimeout(function(){$jsShowMsg.slideToggle('slow');}, 5000);
    }

  // 画像ライブレビュー
  var $dropArea = $('.area-drop');
  var $fileInput = $('.input-file');
  $dropArea.on('click',function(e){
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border', '3px #ccc dashed');
  });
  $dropArea.on('dragleave', function(e){
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border','none');
  });
  $fileInput.on('change', function(e){
    $dropArea.css('border', 'none');
    var file = this.files[0],
        $img = $(this).siblings('.prev-img'),
        fileReader = new FileReader();

        fileReader.onload = function(event) {
          $img.attr('src', event.target.result).show();
        };

        fileReader.readAsDataURL(file);
  });

  // お気に入り登録・削除
  var $like,
      likePostId;
  $like = $('.js-click-like') || null;

  $like.on('click', function(e){
    likePostId = $(e.target).data('postid') || null;

      // 数値の０はfalseと判定される。emptyではなくissetを使う
      if(likePostId !== undefined && likePostId !== null){

        console.log(likePostId);
        var $this = $(this);

        $.ajax({
          type: "POST",
          url: "ajaxPost.php",
          data: { postId : likePostId}
        }).done(function(data){
          console.log('Ajax Success');
          // クラス属性をtoggleでクリックごとに付け外しできるように
          $this.toggleClass('active');
        }).fail(function(msg){
          console.log('Ajax Error');
        });
      }
  });

  // フォローAjax通信
  var $follow,
      followId;
  $follow = $('.js-follow-btn') || null;
  $follow.on('click', function(e){
    followId = $(e.target).data('followid') || null;

      // 数値の０はfalseと判定される。emptyではなくissetを使う
      if(followId !== undefined && followId !== null){

        console.log(followId);
        var $this = $(this);

        $.ajax({
          type: "POST",
          url: "ajaxFollow.php",
          data: { followId : followId}
        }).done(function(data){
          console.log('Ajax Success');
          // クラス属性をtoggleでクリックごとに付け外しできるように
          $this.toggleClass('active');
        }).fail(function(msg){
          console.log('Ajax Error');
        });
      }
  });


});
