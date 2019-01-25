<?php
// 共通変数・関数読み込み
require "functions.php";

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ajaxPost　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// Ajax処理
//================================

if(isset($_POST['postId']) && isset($_SESSION['user_id']) && isLogin()){
  debug('POST送信があります');
  $p_id = $_POST['postId'];
  $u_id = $_SESSION['user_id'];
  debug('postID:'.$p_id);
  // 例外処理
  try{
    // DB接続
    $dbh = dbConnect();
    // すでにお気に入りレコードがあるか検索
    $sql = 'SELECT * FROM likes WHERE post_id = :p_id AND user_id = :u_id';
    $data = array(":p_id" => $p_id, "u_id" => $u_id);
    $stmt = queryPost($dbh, $sql, $data);
    $resultCount = $stmt->rowCount();
    debug($resultCount);
    // レコードが一件でもある場合
    if(!empty($resultCount)){
      // レコードを削除する
      $sql = 'DELETE FROM likes WHERE post_id = :p_id AND user_id = :u_id';
      $data = array(":p_id" => $p_id, ":u_id" => $u_id);
      $stmt = queryPost($dbh, $sql, $data);
    }else{
      $sql = 'INSERT INTO likes(post_id, user_id) VALUES (:p_id, :u_id)';
      $data = array(":p_id" => $p_id, ":u_id" => $u_id);
      $stmt = queryPost($dbh, $sql, $data);
    }

  }catch (Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}else{
  debug('POST送信に失敗しました');
}



?>
