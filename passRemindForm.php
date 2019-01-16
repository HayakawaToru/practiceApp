<?php
// 共通変数・関数ファイル読み込み
require "functions.php";
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード再発行認証キー入力ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// ログイン認証は不要

$siteTitle = 'パスワード再発行メール送信フォーム';
require "head.php"
?>

<body class="page-1colum">

  <!-- メニュー -->
  <?php
  require "header.php";
  ?>
    <?php
    if(!empty($_SESSION['err_msg'])){
      echo '<p id="js-show-err-msg" class="msg-slide">';
      echo getSessionFlash('err_msg');
      echo '</p>';
    }else if(!empty($_SESSION['msg_success'])){
      echo '<p id="js-show-msg" class="msg-slide">';
      echo getSessionFlash('msg_success');
      echo '</p>';
    }else{
      echo '<p id="empty-msg" class="msg-slide"></p>';
    }
    ?>
  <div id="contents" class="site-width">

    <!-- Main -->
    <section id="main">
      <div class="form-container">
        <form action="passRemindSend.php" method="post" class="form">
          <p>入力したメールアドレス宛に再発行用のURLと認証キーを送ります</p>
          <label>
            Email
            <input type="text" name="email" value="<?php echo getFormData('email');?>">
          </label>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="送信する">
          </div>
        </form>
      </div>
    </section>

  </div>
  <?php
  require "footer.php";
  ?>
