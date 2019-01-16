<?php
// 共通変数
require "functions.php";

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード変更処理開始　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require "auth.php";

//================================
// 画面処理
//================================
// DBからユーザーデータを取得
$userData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：'.print_r($userData, true));

if(!empty($_POST)){
  debug('ポスト送信があります');
  debug('POST情報：'.print_r($_POST, true));

  // 変数にユーザー情報を代入
  $pass_old = $_POST['pass_old'];
  $pass_new = $_POST['pass_new'];
  $pass_new_re = $_POST['pass_new_re'];

  // 未入力チェック
  validRequired($pass_old, 'pass_old');
  validRequired($pass_new, 'pass_new');
  validRequired($pass_new_re, 'pass_new_re');

  if(empty($err_msg)){
    debug('未入力バリデーションOK');

    // 古いパスワードをチェック
    validPass($pass_old, 'pass_old');
    // 新しいパスワードをチェック
    validPass($pass_new, 'pass_new');
    // 入力された旧パスワードとDBに登録された旧パスワード情報の照合
    if(!password_verify($pass_old, $userData['pass'])){
      $err_msg['pass_old'] = MSG11;
    }
    // 新しいパスワードと古いパスワードが異なっているかチェック
    if($pass_old === $pass_new){
      $err_msg['pass_new'] = MSG12;
    }
    validMatch($pass_new, $pass_new_re, 'pass_new_re');
    debug('$err_msg確認：',print_r($err_msg, true));
    if(empty($err_msg)){
      debug('バリデーションOK');

      // 例外処理
      try{
        // DBへ接続
        $dbh = dbConnect();
        // SQL文へ接続
        $sql = 'UPDATE users SET pass = :pass WHERE id = :id';
        $data = array(':id' => $userData['id'], ':pass' => password_hash($pass_new, PASSWORD_DEFAULT));
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        // クエリ成功の場合
        if($stmt){
          debug('クエリに成功しました');
          $_SESSION['msg_success'] = SUC01;

          // メールを送信
          $username = ($userData['name']) ? $userData['name'] : '名無し';
          $from = 'toruhayakawa1006@gmail.com';
          $to = $userData['email'];
          $subject = 'パスワードの変更通知';
          $comment = <<<EOT
{$username}　さん
パスワードが変更されました。

////////////////////////////////////////
E-mail toruhayakawa1006@gmail.com
////////////////////////////////////////
EOT;
          sendMail($from, $to, $subject, $comment);
          header("Location:mypage.php");

        }else{
          debug('クエリに失敗しました。');
          $err_msg['common'] = MSG08;
          header("Location:passForm.php");
          exit();
        }
      }catch (Exception $e){
        debug('エラー発生：'. $e->getMessage());
        $err_msg['common'] = MSG08;
        header("Location:passForm.php");
        exit();
      }
    }
  }
}
?>
