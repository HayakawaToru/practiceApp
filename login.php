<?php
require "functions.php";

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログインページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// ログイン認証
require "auth.php";

//================================
// ログイン画面処理
//================================
if(!empty($_POST)){
  debug('POST送信があります');

  // 変数にユーザー情報を代入
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_save = (!empty($_POST['pass_save'])) ? true : false;

  // 未入力チェック
  validRequired($email, 'email');
  validRequired($pass, ' pass');

  // email形式チェック
  validEmail($email, 'email');
  // emailの最大文字数チェック
  validMaxLen($email, 'email');

  // 半角英数宇入力チェック
  validHalf($pass, ' pass');
  // パスワードの最大文字数チェック
  validMaxLen($pass, 'pass');
  // パスワード最小文字数チェック
  validMinLen($pass, 'pass');
  if(empty($err_msg)){
    debug('バリデーションOKです');

    // 例外処理
    try{
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      $sql = 'SELECT pass,id FROM users WHERE email = :email AND delete_flg = 0';
      $data = array(':email' => $email);
      // クエ入り実行
      $stmt = queryPost($dbh, $sql, $data);
      // クエリ結果の値を取得する
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      debug('クエリ結果の中身：'.print_r($result,true));

      // パスワード照合
      if(!empty($result) && password_verify($pass, array_shift($result))){
        debug('パスワードがマッチしました');

        // ログイン有効期限
        $sesLimit = 60 * 60;
        // 最終ログイン日時を現在日時に
        $_SESSION['login_date'] = time();

        // ログイン保持にチェックがある場合
        if($pass_save){
          debug('ログイン保持にチェックがあります');
          // ログイン有効期限を30日にしてセット
          $_SESSION['login_limit'] = $sesLimit * 24 * 30;
        }else {
          debug('ログイン保持にチェックはありません');
          // 次回からログイン保持しないので、ログイン有効期限を1時間後にセット
          $_SESSION['login_limit'] = $sesLimit;
        }
        // ユーザーIDを格納
        $_SESSION['user_id'] = $result['id'];

        debug('セッション変数の中身：',print_r($_SESSION,true));
        debug('マイページへ遷移します');
        header("Location:mypage.php?u_id=".$result['id']);
      }else {
        debug('パスワードがマッチしていません');
        $err_msg['common'] = MSG09;
      }
    } catch (Exception $e) {
      error_log('エラー発生：'.$e->getMessage());
      $err_msg['common'] = MSG08;
    }
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
  $siteTitle = 'ログイン';
  require "head.php";
?>

  <body class="page-login page-1colum">

    <!-- メニュー -->
    <?php require "header.php";?>
    <p>
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
    </p>
    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      <!-- Main -->
      <section id="main">

        <div class="form-container">

          <form action="" method="post" class="form">
            <h2 class="title">ログイン</h2>
            <div class="area-msg">
              <?php
                if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>
            <label class="<?php if(!empty($err_msg['email']))echo 'err';?>">
              メールアドレス
              <input type="text" name="email" value="<?php if(!empty($_POST['email']))echo $_POST['email'];?>">
            </label>
            <div class="area-msg">
              <?php
              if(!empty($err_msg['email']))echo $err_msg['email'];
              ?>
            </div>
            <label class="<?php if(!empty($err_msg['pass'])) echo 'err';?>">
              パスワード
              <input type="password" name="pass" value="<?php if(!empty($_POST['pass']))echo $_POST['pass'];?>">
            </label>
            <div class="area-msg">
              <?php
                if(!empty($err_msg['pass']))echo $err_msg['pass'];
               ?>
            </div>
            <label>
              <input type="checkbox"  name="pass_save">次回ログインを省略する
            </label>
            <div class=2btn-container>
              <input type="submit" class="btn btn-mid" value="ログイン">
            </div>
            <a href="passRemindForm.php">パスワードをお忘れの方はこちら</a>
          </form>
        </div>

      </section>

    </div>
  </body>
</html>
