<header>
  <div class="site-width">
    <h1><a href="mypage.php">SampleApp</a></h1>
    <nav id="top-nav">
      <ul>
        <?php
          if(empty($_SESSION['user_id'])){
        ?>
          <li><a href="signup.php">ユーザー登録</a></li>
          <li><a href="login.php">ログイン</a></li>
        <?php
        }else{
        ?>
          <li><a href="profPage.php">プロフィール</a></li>
          <li><a href="logout.php">ログアウト</a></li>
        <?php } ?>
      </ul>
    </nav>
  </div>
</header>
