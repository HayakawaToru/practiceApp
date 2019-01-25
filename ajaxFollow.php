<?php
// 共通変数・関数読み込み
require "functions.php";

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　AjaxFollow　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// Ajax処理
//================================

if(isset($_POST['followId']) && isset($_SESSION['user_id']) && isLogin()){
  debug('POST送信があります');
  $fwer_id = $_POST['followId'];
  $fw_id = $_SESSION['user_id'];
  debug('followerID:'.$fwer_id);
  // 例外処理
  try{
    // DB接続
    $dbh = dbConnect();
    // すでにお気に入りレコードがあるか検索
    $sql = 'SELECT * FROM follows WHERE follow_id = :fw_id AND follower_id = :fwer_id';
    $data = array(":fw_id" => $fw_id, "fwer_id" => $fwer_id);
    $stmt = queryPost($dbh, $sql, $data);
    $resultCount = $stmt->rowCount();
    debug($resultCount);
    // レコードが一件でもある場合
    if(!empty($resultCount)){
      // レコードを削除する
      $sql = 'DELETE FROM follows WHERE follow_id = :fw_id AND follower_id = :fwer_id';
      $data = array(":fw_id" => $fw_id, ":fwer_id" => $fwer_id);
      $stmt = queryPost($dbh, $sql, $data);
    }else{
      $sql = 'INSERT INTO follows(follow_id, follower_id) VALUES (:fw_id, :fwer_id)';
      $data = array(":fw_id" => $fw_id, ":fwer_id" => $fwer_id);
      $stmt = queryPost($dbh, $sql, $data);
    }

  }catch (Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}else{
  debug('POST送信に失敗しました');
}



?>
