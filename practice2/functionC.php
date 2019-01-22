<?php

function getMsgsAndBord($id){
  debug('msg情報を取得します');
  debug('掲示板ID',$id);

  // 例外処理
  try{
    $dbh = dbConnect();
    // SQL分作成
    $sql = 'SELECT m.id AS m_id, product_id, bord_id, send_date, to_user, from_user, sale_user, buy_user, msg, b.create_date From message AS m RIGHT JOIN bord AS b ON b.bord_id WHERE b.id = :id AND m.delete_flg = 0 ORDER BY send_date ASC';
    $data = array(':id' => $id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      return $stmt->fetchAll();
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMesage());
  }
}

function getProductOne($p_id){
  debug('商品情報を取得します');
  debug('商品ID：'.$p_id);

  // 例外処理
  try{
    $dbh = dbConnect();
    // SQL分作成
    $sql = 'SELECT p.id, p.name, p.comment, p.price, p.pic1, p.pic2, p.pic3, p.user_id, p.create_date, p.update_date, c.name AS category
            FROM product AS p LEFT JOIN category AS c ON p.category_id = c.id WHERE p.id = :p_id AND p.delete_flg = 0 AND c.delete_flg = 0';
    $data = array(':p_id' => $p_id);
    $stmt = queryPost($dbh, $sql, $data);
  }
}

?>
