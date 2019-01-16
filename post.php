<?php
  // 共通変数・関数読み込み
  require "functions.php";
  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debug('「　投稿処理開始　');
  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debugLogStart();

  // POST送信時処理
  //================================
  if(!empty($_POST)){
    debug('POST送信があります');
    debug('POST情報：'.print_r($_POST,true));
    debug('FILE情報：'.print_r($_FILES,true));

    $tweet =$_POST['tweet'];
    // 画像をアップロードし、パスを格納
    // 画像が選択されない投稿の場合は$_FILESに格納されているエラー番号で条件分岐
    if(($_FILES['pic1']['error'] !== 4)){
      $pic1 = uploadImg($_FILES['pic1'], 'pic1');
    } else {
      $_FILES = '';
    }
    // 未入力チェック
    validRequired($tweet, 'tweet');

    if(empty($err_msg)){
      debug('未入力チェックOK');
      // 最大入力文字数チェック
      validPostLen($tweet, 'tweet');

      if(empty($err_msg)){
        debug('投稿バリデーションOKです');
        // 例外処理
        try{
          // DB接続
          $dbh = dbConnect();

          // クエリ分作成(画像投稿があった場合)
            $sql = 'INSERT INTO posts(id, post, media_path_01,created_at, updated_at)VALUES(:id, :post, :media_path_01, :created_at, :updated_at)';
            $data = array(':id' => $_SESSION['user_id'], ':post' => $tweet,
            ':media_path_01' => $pic1,
            ':created_at' => date('Y-m-d H:i:s'),
            ':updated_at' => date('Y-m-d H:i:s')
            );

          // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);
          if($stmt){
            header('Location:mypage.php');
          }else{
            $_SSESSION['err_msg'] = $err_msg['common'];
            header('Location:mypage.php');
          }
        }catch (Exception $e){
          error_log('エラー発生：'. $e->getMessage());
          $_SESSION['err_msg'] = MSG08;
          header("Location:signup.php");
        }
      }else{
        debug('入力可能な文字数を超えています');
        $_SESSION['err_msg'] = MSG17;
        header('Location:mypage.php');
      }
    }else{
      debug('未入力で投稿しています');
      $_SESSION['err_msg'] = MSG01;
      header('Location:mypage.php');
    }
  }else{
    debug('POST送信がありません');
    $_SESSION['err_msg'] = MSG16;
    header('Location:mypage.php');
  }
?>
