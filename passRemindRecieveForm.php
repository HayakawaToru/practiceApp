<?php
// 共通変数・関数ファイル読み込み
require "functions.php";
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード再発行認証キー入力ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// ログイン認証は不要

$siteTitle = 'Pass再発行認証キー入力画面';
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
        <form action="passRemindRecieve.php" method="post" class="form">
          <p>指定したメールアドレスに送った認証キーを入力してください</p>
          <label>
            認証キー
            <input type="text" name="token" value="<?php echo getFormData('email');?>">
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
