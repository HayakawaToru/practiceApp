<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body class="page-signup page-1colum">

<?php
  require "header.php";
?>

  <!-- メインコンテンツ -->
  <div id="contents" class="site-width">

    <!-- Main -->
    <section id="main">
      <div class="form-container">
        <form action="registration.php" method="post" class="form">
          <h2 class="title">ユーザー登録</h2>

          <div class="area-msg">
            <?php
            if(!empty($err_msg['common'])) echo $err_msg['common'];
            ?>
          </div>

          <label class="<?php if(!empty($err_msg['email'])) echo 'err';?>">
            <input type="text" name="email" placeholder="Email" value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?>">
          </label>
          <div class="area-msg">
            <?php
            if(!empty($err_msg['email'])) echo $err_msg['email'];
            ?>
          </div>

          <label class="<?php if(!empty($err_msg['pass'])) echo 'err';?>">
            <input type="password" name="pass" placeholder="Password" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass'];?>">
          </label>
          <div class="area-msg">
            <?php
            if(!empty($err_msg['pass'])) echo $err_msg['pass'];
            ?>
          </div>

          <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err';?>">
            <input type="password" name="pass_re" placeholder="Password(再入力)" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re'];?>">
          </label>
          <div class="area-msg">
            <?php
            if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];
            ?>
          </div>

          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="登録する">
          </div>
        </form>
      </div>
    </section>
  </div>


</body>
</html>
