<?php
// 共通変数・関数ファイル読み込み
require "functions.php";
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード再発行メール送信処理　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります');
  debug('POST情報：'.print_r($_POST,true));

  $email = $_POST['email'];
  // 未入力チェック
  validRequired($email, 'email');

  if(empty($err_msg)){
    debug('未入力チェックOK');

    // Email形式チェック
    validEmail($email, 'email');
    // 最大文字数チェック
    validMaxLen($email, 'email');

    if(empty($err_msg)){

      // 例外処理
      try{
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg =0';
        $data = array(':email' => $email);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // クエリ結果の値を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt && array_shift($result)){
          debug('クエリ成功,DB登録確認');
          $_SESSION['msg_success'] = SUC03;
          // 認証キー作成
          $auth_key = makeRandKey();
          // メール送信
          $from = 'toruhayakawa1006@gmail.com';
          $to = $email;
          $subject = 'パスワード再発行認証';
          $comment = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。

パスワード再発行認証キー入力ページ：http://localhost:8888/webservice_practice07/passRemindRecieve.php
認証キー：{$auth_key}
※認証キーの有効期限は30分となります.
EOT;
          sendMail($from, $to, $subject, $comment);

          // 認証に必要な情報を保存
          $_SESSION['auth_key'] = $auth_key;
          $_SESSION['email'] = $email;
          $_SESSION['auth_key_limit'] = time() + (60*30);
          $_SESSION['msg_success'] = SUC03;
          debug('セッションの中身：'.print_r($_SESSION,true));
          header("Location:passRemindRecieveForm.php");
        }else{
          debug('クエリに失敗したかDBに登録のないEmailが入力されました。');
          $_SESSION['err_msg'] = MSG08;
        }

      }catch(Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = MSG07;
        $_SESSION['err_msg'] = $err_msg['common'];
        header("Location:passRemindForm.php");
      }

    }else{
      $_SESSION['err_msg'] = $err_msg['email'];
      header("Location:passRemindForm.php");
      exit();
    }

  }else{
    $_SESSION['err_msg'] = $err_msg['email'];
    header("Location:passRemindForm.php");
  }
}
?>
