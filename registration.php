<?php
require "functions.php";

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ユーザー登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(!empty($_POST)){
  debug('POST送信があります');

// 未入力チェック
validRequired($_POST['email'],'email');
validRequired($_POST['pass'],'pass');
validRequired($_POST['pass_re'],'pass_re');

  if(empty($err_msg));
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];
  debug('POST送信を変数に格納しました');

    // Email形式のチェック
    validEmail($email,'email');
    // Emailの最大文字数チェック
    validMaxLen($email,'email');
    // Email登録重複チェック
    validEmailDup($email);

    // パスワードの半角英数字入力確認
    validHalf($pass,'pass');
    // パスワード最大文字数チェック
    validMaxLen($pass,'pass');
    // パスワードの最小文字数チェック
    validMinLen($pass, 'pass');


    // パスワード（再入力）の半角英数字入力確認
    validHalf($pass_re,'pass_re');
    // パスワード（再入力）最大文字数チェック
    validMaxLen($pass_re,'pass_re');
    // パスワード（再入力）の最小文字数チェック
    validMinLen($pass_re, 'pass_re');
    debug('バリデエラーチェック'.print_r($err_msg,true));


    if(empty($err_msg)){
      debug('POST入力バリデーションOK');

      // 入力パスワードが同値かチェック
      validMatch($pass, $pass_re, 'pass_re');

      if(empty($err_msg)){
          debug('バリデーションOKです');
        // 例外処理
        try{
          // DBへ接続
          $dbh = dbConnect();

          // SQL文作成
          $sql = 'INSERT INTO users (email,pass,create_date,login_time,name,bio,header_path,profile_path) VALUES(:email,:pass,:create_date,:login_time,"","",:header_path,:profile_path)';
          $data = array(':email' => $email,':pass' => password_hash($pass,PASSWORD_DEFAULT),
                        ':create_date' => date('Y-m-d H:i:s'),
                        ':login_time' => date('Y-m-d H:i:s'),
                        'header_path'=> '/Applications/MAMP/htdocs/practiceApp/uploads/cd2a50074257ad5a502313180cbb85dad6060fe6.jpeg',
                        'profile_path' =>'/Applications/MAMP/htdocs/practiceApp/uploads/3a7c90a44d1f7e16b90c68a2ab8e3a0254a23fe6.jpeg'
          );
          // // クエリ実行
          $stmt = queryPost($dbh,$sql,$data);

          // クエリ成功の場合
          if($stmt){
              // ログイン有効期限（デフォルトを1時間とする）
              $sesLimit = 60*60;
              // 最終ログイン日時を現在日時に
              $_SESSION['login_date'] = time();
              $_SESSION['login_limit'] = $sesLimit;
              // ユーザーIDを格納
              $_SESSION['user_id'] = $dbh->lastInsertId();
              if($_SESSION['user_id'] != 0){
                debug('セッション変数の中身：'.print_r($_SESSION,true));
                header("Location:mypage.php?u_id=".$_SESSION['user_id']);
            }else{
                $_SESSION = NULL;
                header("Location:login.php");
            }
          }
        } catch (Exception $e) {
          error_log('エラー発生：'. $e->getMessage());
          $err_msg['common'] = MSG08;
          header("Location:signup.php");
        }
      }
    }
  }
?>
