<?php
// 共通変数・関数の読み込み
require "functions.php";
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード再発行認証キー処理開始　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(empty($_SESSION['auth_key'])){
  header("Location:passRemindForm.php");
}

// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります');
  debug('POST情報：'.print_r($_POST,true));
  // 変数に入力フォームの認証キーを代入
  $auth_key = $_POST['token'];

  validRequired($auth_key, 'token');

  if(empty($err_msg)){
    debug('未入力チェックOK');

    // 入力文字数チェック
    validLength($auth_key, 'token');
    // 半角入力チェック
    validHalf($auth_key, 'token');

    if(empty($err_msg)){
      debug('バリデーションOK');

      if($auth_key !== $_SESSION['auth_key']){
        $err_msg['token'] = MSG13;
      }

      if(time() > $_SESSION['auth_key_limit']){
        $err_msg['token'] = MSG14;
      }

      if(empty($err_msg)){
        debug('認証OK');

        $pass = makeRandKey();

        // 例外処理
        try{
          // DBへ接続
          $dbh = dbConnect();
          // SQL文作成
          $sql = 'UPDATE users SET pass = :pass WHERE email = :email AND delete_flg = 0';
          $data= array(':email' => $_SESSION['email'],':pass' => password_hash($pass,PASSWORD_DEFAULT));

          // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);

          // クエリ成功の場合
          if($stmt){
            debug('クエリ成功');

            // メール送信
            $from = 'toruhayakawa1006@gmail.com';
            $to = $_SESSION['email'];
            $subject ='パスワード再発行完了';
            $comment = <<<EOT
本メールアドレス宛にパスワードの再発行を致しました。
下記のURLにて再発行パスワードをご入力頂き、ログインください。

ログインページ：http://localhost:8888/webservice_practice07/login.php
再発行パスワード：{$pass}
※ログイン後、パスワードのご変更をお願い致します。
EOT;
            sendMail($from, $to, $subject, $comment);
            // セッション削除
            session_unset();
            $_SESSION['msg_success'] = SUC03;
            debug('セッション変数の中身：'.print_r($_SESSION,true));

            header("Location:login.php");
          }else{
            debug('クエリに失敗しました。');
            $err_msg['common'] = MSG07;
            $_SESSION['err_msg'] = $err_msg['common'];
            header("Location:passRemindRecieveForm.php");
            exit();
          }
        }catch( Exception $e){
          error_log('エラー発生:' . $e->getMessage());
          $err_msg['common'] = MSG07;
        }

      }else{
        $_SESSION['err_msg'] = $err_msg['token'];
        header("Location:passRemindRecieveForm.php");
      }
    }else{
      $_SESSION['err_msg'] = $err_msg['token'];
      header("Location:passRemindRecieveForm.php");
    }

  }else{
    $_SESSION['err_msg'] = $err_msg['token'];
    header("Location:passRemindRecieveForm.php");
  }
}

?>
