<?php
//================================
// ログ
//================================
//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');
//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = true;
// デバッグログ関数
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ:'.$str);
  }
}
//================================
// セッション準備・セッション有効期限を延ばす
//================================
// セッションファイルの置き場を変更する(/var/tmp/以下に置くと30日削除されない)
session_save_path("/var/tmp/");
// ガーベージコレクションが削除するセッションの有効期限を設定(30日以上経っているものにたいしてだけ100分の1の確率で削除)
ini_set('session.gc_maxlifetime', 60*60*24*30);
// ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime', 60*60*24*30);
// セッションを使う
session_start();
// 現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();
//================================
// 画面表示処理開始ログ吐き出し関数
//================================
function debugLogStart(){
  debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
  debug('セッションID:'.session_id());
  debug('セッション変数の中身:'.print_r($_SESSION, true));
  debug('現在日時タイムスタンプ:'.time());
  if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
    debug('ログイン期限日時タイムスタンプ:'.($_SESSION['login_date'] + $_SESSION['login_limit']));
  }
}
//================================
// 定数
//================================
// エラーメッセージを定数に設定
define('MSG01','入力必須です');
define('MSG02','Emailの形式で入力してください');
define('MSG03','そのEmailはすでに登録されています');
define('MSG04','パスワードが一致していません');
define('MSG05','6文字以上入力してください');
define('MSG06','256文字以内で入力してください');
define('MSG07','半角英数字のみご利用いただけます');
define('MSG08','エラーが発生しました。しばらく経ってからやり直してください');
define('MSG09','メールアドレスまたはパスワードが違います');
define('MSG10','名前は50文字以内で入力してください');
define('MSG11','入力された古いパスワードが合っていません');
define('MSG12','古いパスワードと新しいパスワードが同じです');
define('MSG13','文字で入力してください');
define('MSG14', '正しくありません');
define('MSG15', '有効期限が切れています');
define('MSG16', 'メッセージの送信に失敗しました');
define('MSG17','投稿は140文字以内で入力してください');
// 処理成功時メッセージを定数で設定
define('SUC01','パスワードを変更しました');
define('SUC02','プロフィールを変更しました');
define('SUC03','メールを送信しました');
define('SUC04','登録しました');


//================================
// バリデーション関数
//================================
// エラーメッセージ格納用配列
$err_msg = array();

// バリデーション関数（未入力チェック）
function validRequired($str,$key){
  if($str === ''){
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}
// Email形式チェック
function validEmail($str,$key){
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}
// Email重複登録チェック
function validEmailDup($email){
  global $err_msg;
  // 例外処理
  try{
    // DB接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT count(*) users FROM users WHERE email = :email';
    $data = array(':email' => $email);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // クエリ結果の取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //array_shift関数は配列の先頭を取り出す関数です。クエリ結果は配列形式で入っているので、array_shiftで1つ目だけ取り出して判定します
    if(!empty(array_shift($result))){
      $err_msg['email'] = MSG03;
    }
  } catch (Exception $e) {
    error_log('エラー発生:'. $e->getMessage());
    $err_msg['common'] = MSG08;
  }
}
// 入力値一致チェック
function validMatch($str1, $str2, $key){
  if($str1 !== $str2){
    global $err_mag;
    $err_msg[$key] = MSG04;
  }
}
// 最小値入力値チェック
function validMinLen($str, $key, $min = 6){
  if(mb_strlen($str) < 6){
    global $err_msg;
    $err_msg[$key] = MSG05;
  }
}
// 最大値入力チェック
function validMaxLen($str, $key, $max = 256){
  if(mb_strlen($str) > $max){
    global $err_msg;
    $err_msg[$key] = MSG06;
  }
}
// 半角英数字入力チェック
function validHalf($str, $key){
  if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG07;
  }
}

function validNameLen($username, $key, $max = 50){
  if(mb_strlen($username) > $max){
    global $err_msg;
    $err_msg[$key] = MSG10;
  }
}

function validPostLen($tweet, $key, $max = 140){
  if(mb_strlen($tweet) > $max){
    global $err_msg;
    $err_msg = MSG17;
  }
}

function validLength($str, $key, $len = 8){
  if(mb_strlen($str) !== $len){
    global $err_msg;
    $err_msg[$key] = $len.MSG13;
  }
}

function validPass($str, $key){
  // 半角英数字チェック
  validHalf($str, $key);
  // 最大文字数チェック
  validMaxLen($str, $key);
  // 最小文字数チェック
  validMinLen($str, $key);
}

// エラーメッセージ表示
function getErrMsg($key){
  global $err_msg;
  if(!empty($err_msg[$key])){
    return $err_msg[$key];
  }
}
//================================
// ログイン認証
//================================
function isLogin(){
  // ログインしている場合
  if(!empty($_SESSION['login_date'])){
    debug('ログイン済みのユーザーです');
    // 現在日時が最終ログイン日時＋有効期限を超えていた場合
    if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
      debug('有効期限オーバーです');
      // sessionの削除
      session_destroy();
      return false;
    }else{
      debug('ログイン有効期限以内です');
      return true;
    }
  }else{
    debug('未ログインユーザーです');
    return false;
  }
}

//================================
// データベース
//================================
function dbConnect(){
  // DBへの接続準備
  $dsn = 'mysql:dbname=practiceweb;host=localhost:8889;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = array(
    // SQL実行失敗時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  $dbh = new PDO($dsn, $user, $password, $options);
  return $dbh;
}
// SQL実行関数
function queryPost($dbh, $sql, $data){
  // クエリ作成
  $stmt = $dbh->prepare($sql);
  // プレースホルダに値をセットしてSQL文を実行
  if(!$stmt->execute($data)){
    debug('クエリに失敗しました');
    $err_msg['common'] = MSG08;
    return 0;
  }
  debug('クエリ成功');
  return $stmt;
}

function getUser($u_id){
  debug('ユーザー情報を取得します');
  // 例外処理
  try{
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT * FROM users WHERE id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    // クエリ結果のデータを1レコード返却
    if($stmt){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生：'. $e->getMessage());
  }
}

function getRecieveUser(){
  debug('メッセージ送信先情報の取得');
  // 例外処理
  try{
    //DBへ接続
    $dbh = dbConnect();
    // SQL文を作成
    $sql = 'SELECT * FROM users WHERE delete_flg = 0';
    $data = array();
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetchAll();
    }else{
      return false;
    }
  }catch (Exception $e){
    error_log('エラー発生：'. $e->getMessage());
  }
}

function getUserPost($u_id, $currentMinNum = 1, $span = 20) {
  debug('ユーザーの投稿を取得します');
  // 例外処理
  try{
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT * FROM posts WHERE id = :u_id AND delete_flg = 0';
    $sql .= ' ORDER BY updated_at DESC LIMIT '.$span.' OFFSET '.$currentMinNum;
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    // クエリ結果のデータを1レコード返却
    if($stmt){
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生：'. $e->getMessage());
  }
}

function getMsgsAndBoard($board_id){
  debug('メッセージ情報を取得します');
  debug('掲示板ID:'.$board_id);

  // 例外処理
  try{
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT m.id, m.board_id, m.sender_id, m.reciever_id, msg, create_date FROM messages AS m RIGHT JOIN boards AS b ON b.id = m.board_id WHERE b.id = :id AND m.delete_flg = 0 ORDER BY m.create_date ASC';
    $data = array(":id" => $board_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetchAll();
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}


function getPostList($u_id,$currentMinNum = 1, $span = 20){
  debug('投稿情報を取得します');
  // 例外処理
  try{
    // DBへ接続
    $dbh = dbConnect();
    // 件数用のSQL文作成
    $sql = 'SELECT id FROM posts';
    $data = array();
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $rst['total'] = $stmt->rowCount();
    // $spanには1ページあたりの表示投稿数が格納、その数値で全体の投稿数を割ることで
    // 全ページ数を算出している
    $rst['total_page'] = ceil($rst['total']/$span);
    if(!$stmt){
      return false;
    }

    // ページング用のSQL文作成
    $sql = 'SELECT * FROM posts WHERE id = :u_id OR id IN (SELECT follower_id FROM follows WHERE follow_id = :u_id)';
    $sql .= ' ORDER BY updated_at DESC LIMIT '.$span.' OFFSET '.$currentMinNum;
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      // クエリ結果のデータを全レコードを格納
      $rst['data'] = $stmt->fetchAll();
      return $rst;
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}

function isLike($p_id, $u_id){
  debug('お気に入り情報があるか確認します');
  debug('ユーザID：'.$u_id);
  debug('投稿ID：'.$p_id);

  // 例外処理
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM likes WHERE post_id = :p_id AND user_id = :u_id';
    $data = array(":p_id" => $p_id, ":u_id" => $u_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt->rowCount()){
      debug('お気に入りです');
      return true;
    }else{
      debug('特に気に入っていません');
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}

function isFollow($follow_id, $follower_id){
  debug('お気に入り情報があるか確認します');
  debug('フォロー側ID：'.$follow_id);
  debug('フォロワー側ID'.$follower_id);

  // 例外処理
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM follows WHERE follow_id = :fw_id AND follower_id = :fwer_id';
    $data = array(":fw_id" => $follow_id, ":fwer_id" => $follower_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt->rowCount()){
      debug('お気に入りです');
      return true;
    }else{
      debug('特に気に入っていません');
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}

// 投稿数を取得
function countPosts($u_id){
  debug('ログインユーザの投稿数をカウントします');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT post FROM posts WHERE id = :u_id';
    $data = array(':u_id' => $u_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      debug('レコード数取得しました');
      $result = $stmt->fetchAll();
      $result = count($result);
      return $result;
    }else{
      debug('レコード数取得に失敗しました');
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}

// フォローしているアカウント数を取得
function countFollows($u_id){
  debug('フォローしているアカウント数をカウントします');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT follow_id FROM follows WHERE follow_id = :u_id';
    $data = array(':u_id' => $u_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      debug('レコード数取得しました');
      $result = $stmt->fetchAll();
      $result = count($result);
      return $result;
    }else{
      debug('レコード数取得に失敗しました');
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}

// フォローしているアカウント数を取得
function countFollowers($u_id){
  debug('フォローされているアカウント数をカウントします');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT follower_id FROM follows WHERE follower_id = :u_id';
    $data = array(':u_id' => $u_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      debug('レコード数取得しました');
      $result = $stmt->fetchAll();
      $result = count($result);
      return $result;
    }else{
      debug('レコード数取得に失敗しました');
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}
//================================
// メール送信
//================================
function sendMail($from, $to, $subject, $comment){
  if(!empty($to) && !empty($subject) &&!empty($comment)) {
    // 文字化けしないように設定
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");

    // メールを送信（送信結果はtrueかfalseで返ってくる)
    $result = mb_send_mail($to, $subject, $comment, 'From:'.$from);
    // 送信結果を判定
    if($result) {
      debug('メールを送信しました');
    }else{
      debug('メールの送信に失敗しました');
    }
  }
}


//================================
// その他
//================================
// フォーム入力保持
function getFormData($str){
  global $dbFormData;
  // ユーザーデータがある場合
  if(!empty($dbFormData)){
    // フォームのエラーがある場合
    if(!empty($err_msg[$str])){
      // POSTにデータがある場合
      if(isset($_POST[$str])){
        return $_POST[$str];
      }else {
        return $dbFormData[$str];
      }
    }else{
      // POSTにデータがあり、DBの情報と違う場合（このフォームにエラーはないが、他のフォームでエラーが発生している場合の処理）
      if(isset($_POST[$str]) && $_POST[$str] !== $dbFormData[$str]){
        return $_POST[$str];
      }else{
        return $dbFormData[$str];
      }
    }
  }else{
    if(isset($_POST[$str])){
      return $_POST[$str];
    }
  }
}

// sessionを一回だけ取得できる
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }
}

// 認証キー作成
function makeRandKey($length = 8){
  static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
  $str = '';
  for($i = 0; $i < 8; ++$i) {
    $str .= $chars[mt_rand(0,61)];
  }
  return $str;
}

// 画像処理
function uploadImg($file, $key){
  debug('画像のアップロード処理開始');
  debug('FILE情報：'.print_r($file, true));

    if(isset($file['error']) && is_int($file['error'])){
      try{
      // バリデーション
      // file['error']の値を確認。配列内には「UPLOAD_ERR_OK」などの定数が入っている
      // 「UPLOAD_ERR_OK」などの定数はphpでファイルアップロード時に自動的に定義される。定数には値として0や1が入っている
      switch ($file['error']){
        case UPLOAD_ERR_OK:
          break;
        case UPLOAD_ERR_NO_FILE:
          throw new RuntimeException('ファイルが選択されていません');
          break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FROM_SIZE:
          throw new RuntimeException('ファイルサイズが大きすぎます');
        default:
          throw new RuntimeException('その他のエラーが発生しました');
      }

      // file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
      // exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
      $type = @exif_imagetype($file['tmp_name']);
      if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) { // 第三引数にはtrueを設定すると厳密にチェックしてくれるので必ずつける
        throw new RnttimeException('画像形式が 未対応です');
      }

      // ファイルデータからSHA-1ハッシュをとってファイル名を決定し、ファイルを保存する
      // ハッシュ化しておかないとアップロードされたファイル名そのままで保存してしまうと同じファイル名がアップロードされる可能性があり、
      // DBにパスを保存した場合、どっちの画像のパスなのか判断つかなくなってしまう
      // image_type_to_extension関数はファイルの拡張子を取得するもの
      $path = '/Applications/MAMP/htdocs/practiceApp/uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
      debug('$path情報：'.print_r($path, true));
      // ファイルを移動する
      if (!move_uploaded_file($file['tmp_name'], $path)){
        throw new RuntimeException('ファイル保存時にエラーが発生しました');
      }
      // 保存したファイルパスのパーミッション（権限）を変更する
      chmod($path, 0644);

      debug('ファイルは正常にアップロードされました');
      debug('ファイルパス：'.$path);
      return $path;
    } catch (RuntimeException $e){
      debug($e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();
    }
  }
}

// 画像パス処理
function validImagePath($path) {
  return $path = str_replace('/Applications/MAMP/htdocs/practiceApp/','',$path);
}
?>
