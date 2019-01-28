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
          <form class="search-form" action="search.php" method="post">
            <div class="search-column">
              <input type="text" placeholder="キーワード検索" name="search-word" class="search-word">
              <input type="submit" value="検索する" class="search">
            </div>
          </form>
          <li><a href="logout.php">ログアウト</a></li>
          <li><a href="msg.php?u_id=<?php echo $_SESSION['user_id'];?>">メッセージ</a></li>
          <li><a href="profPage.php?u_id=<?php echo $_SESSION['user_id'];?>">プロフィール</a></li>
        <?php } ?>
      </ul>
    </nav>
  </div>
</header>
