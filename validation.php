<?php
// ログをとるか
ini_set('log_errors','on');
// ログの出力ファイルを指定
ini_set('error_log','php_log');

define('MSG01','入力必須です');
define('MSG02','Emailの形式で入力してください');
define('MSG03','そのEmailはすでに登録されています');
define('MSG04','パスワードが一致していません');
define('MSG05','6文字以上入力してください');
define('MSG06','256文字以内で入力してください');
define('MSG07','半角英数字のみご利用いただけます');
define('MSG08','エラーが発生しました。しばらく経ってからやり直してください');

// エラーメッセージ格納用配列
$err_msg = array();

// バリデーション関数（未入力チェック）
function validRequired($str,$key){
  if(empty($str)){
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

?>
