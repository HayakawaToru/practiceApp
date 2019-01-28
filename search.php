<?php
// 共通変数と関数の読み込み
require "functions.php";
require "auth.php";
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　検索処理　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// 検索ワードが「０」もありうるのでisset
if(isset($_POST)){
  debug('POST情報：'.print_r($_POST,true));

  // 検索キーワードの取得とLIKE条件句用に％で囲んだ文字列を格納し直し
  $search_word = $_POST['search-word'];
  $search_word = "%".$search_word."%";
  // ページネーション用の変数定義
  $currentMinNum = 1;
  $span = 20;

  // 例外処理
  try{
    debug('検索処理開始');
    // DBへ接続
    $dbh = dbConnect();
    // 件数用のSQL文を作成
    $sql = 'SELECT id FROM posts WHERE post LIKE :search_word ORDER BY updated_at DESC ';
    $data = array(":search_word" => $search_word);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // totalには取得投稿数が格納されている。この数を１ページあたりの投稿数で割ることでページ数が算出される
    $rst['total'] = $stmt->rowCount();
    $rst['total_page'] = ceil($rst['total']/$span);
    if(!$stmt){
      debug('クエリ実行時のページング処理失敗');
      // 処理の中断
      exit();
      header("Location:mypage.php");
    }

    // ページング用のSQL文作成
    $sql = 'SELECT * FROM posts WHERE post LIKE :search_word';
    $sql .= ' ORDER BY updated_at DESC LIMIT '.$span.' OFFSET '.$currentMinNum;
    $data = array(":search_word" => $search_word);
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt) {

      $rst['data'] = $stmt->fetchAll();
      $_SESSION['search-result'] = $rst;
      header("Location:mypage.php");
    }
  }catch (Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}

?>
