<?php
require "functions.php";

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　プロフィール編集ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// ログインユーザー情報の取得
$dbFormData = getUser($_SESSION['user_id']);
debug('$dbFormData中身：'.print_r($dbFormData,true));

if(!empty($_POST)){
  debug('POST送信があります');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));


  // 変数にユーザー情報を代入
  $username = $_POST['username'];
  $bio = $_POST['bio'];
  $header_path = ( !empty($_FILES['header-img'])) ? uploadImg($_FILES['header-img'],'header-img') : '';

  if(!empty($_FILES['prof-img'])) {
    $profile_path = uploadImg($_FILES['prof-img'],'prof-img');
  } else{
    $profile_path = $dbFormData['profile_path'];
  }
  // DB情報とフォーム送信情報が異なる場合にバリデーションを行う
  if($dbFormData['name'] !== $username || $dbFormData['bio'] !== $bio || $dbFormData['header_path'] !== $header_path){
    // 名前の文字数チェック
    validNameLen($username, 'username');

    if(empty($err_msg['username'])){
      debug('名前の文字数チェックOKです');
      // 名前の未入力チェック
      validRequired($username,'username');

      if(empty($err_msg)){
        debug('バリデーションOKです');

        // 例外処理
        try{
          // DBへ接続
          $dbh = dbConnect();

          // SQL文作成
           $sql = 'UPDATE users SET name = :u_name, bio = :bio, header_path = :header_path, profile_path = :profile_path WHERE id = :u_id';
           $data = array(':u_name' => $username, ':bio' => $bio, 'u_id' => $dbFormData['id'],
                          ':header_path' => $header_path,
                          ':profile_path' => $profile_path
                        );
           // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);
          header("Location:profPage.php");

        } catch (Exception $e){
          error_log('エラー発生：'.$e->getMessage());
          $err_msg['common'] = MSG08;
          $_SESSION['err_msg'] = $err_msg['common'];
          exit();
        }
        // 画像ファイルのどちらかもしくは両方が選択されていないかつ他項目の変更があった場合の処理
      }else if(!empty($_POST) && ($_FILES['header-img']['error'] == 4 || $_FILES['prof-img']['error'] == 4)) {
        // 例外処理
        try{
          // DBへ接続
          $dbh = dbConnect();
          // SQL文作成
           $sql = 'UPDATE users SET name = :u_name, bio = :bio WHERE id = :u_id';
           $data = array(':u_name' => $username, ':bio' => $bio, 'u_id' => $dbFormData['id']);
           // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);
          header("Location:profPage.php");

        } catch (Exception $e){
          error_log('エラー発生：'.$e->getMessage());
          $err_msg['common'] = MSG08;
          $_SESSION['err_msg'] = $err_msg['common'];
          exit();
        }
      }else{
        debug('バリデーション失敗しました'.print_r($err_msg,true));
        debug('プロフィールページへ遷移します');
        var_dump($_FILES['header-img']['error']);
        $_SESSION['err_msg'] = $err_msg['username'];
        // header("Location:profPage.php");
      }
    }else{
      $_SESSION['err_msg'] = $err_msg['username'];
      header("Location:profPage.php");
    }
  } else {
    $_SESSION['err_msg'] = $err_msg['username'];
    header("Location:profPage.php");
  }
}
?>
