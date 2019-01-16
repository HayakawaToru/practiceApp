<?php
// 共通変数・関数読み込み
require "functions.php";


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード変更ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require "auth.php";
?>
<?php
  $siteTitle = 'パスワード変更';
  require "head.php";
?>

<body class="page-passForm page-1colum page-logined">

<?php
  require "header.php";
?>

<!-- メインコンテンツ -->
<div id="contents" class="site-width">
  <h1 class="page-title">パスワード変更</h1>
  <!-- Main -->
  <section id="main">
    <div class="form-container">
      <form action="passEdit.php" method="post" class="form">
        <div class="area-msg">
          <?php echo getErrMsg('common');?>
        </div>
        <label class="<?php if(!empty($err_msg['pass_old'])) echo 'err';?>">
          古いパスワード
          <input type="password" name="pass_old" value="<?php echo getFormData('pass_old');?>">
        </label>
        <div class="area-msg">
          <?php echo getErrMsg('pass_old');?>
        </div>
        <label class="<?php if(!empty($err_msg['pass_new'])) echo 'err';?>">
          新しいパスワード
          <input type="password" name="pass_new" value="<?php echo getFormData('pass_new');?>">
        </label>
        <div class="area-msg">
          <?php
            echo getErrMsg('pass_new');
          ?>
        </div>
        <label class="<?php if(!empty($err_msg['pass_new_re'])) echo 'err';?>">
          新しいパスワード(再入力)
          <input type="password" name="pass_new_re" value="<?php echo getFormData('pass_new_re');?>">
        </label>
        <div class="area-msg">
          <?php
            echo getErrMsg('pass_new_re');
          ?>
        </div>
        <div class="btn-container">
          <input type="submit" class="btn btn-mid" value="変更する">
        </div>
      </form>
    </div>
  </section>

</div>

<!-- footer -->
<?php
  require "footer.php";
?>
