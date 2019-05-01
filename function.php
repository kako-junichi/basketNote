<?php
//==============================
//ログ
//==============================
//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');

//==============================
//デバッグ
//==============================
//デバッグフラグ
$debug_flg = true;
//デバッグ関数
function debug($str){
	global $debug_flg;
	if(!empty($debug_flg)){
		error_log('デバッグ:'.$str);
	}
}

//===============================
//セッション準備・セッション有効期限を伸ばす
//===============================
//セッションファイルの置き場を変更する(/var/tmp/いかにおくと３０日は削除されない)
session_save_path("/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定(30日以上経っているものに対して100分の1の確率で削除)
ini_set('session.gc_maxlifetime',60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を伸ばす
ini_set('session.cookie_lifetime', 60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成されたものと置き換える
session_regenerate_id();


//==============================
//画面表示開始ログ吐き出し関数
//==============================
function debugLogStart(){
	debug('>>>>>>>>>>>>>>>>>>>>>>>>>>画面表示処理開始');
	debug('セッションID'.session_id());
	debug('セッション変数の中身'.print_r($_SESSION,true));
	debug('現在日時スタンプ:'.time());
	if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
		debug('ログイン期限日時スタンプ:'.($_SESSION['login_date'] + $_SESSION['login_limit']));
	}
	}

//=============================
//定数
//=============================
//エラーメッセージを定数に設定
define('MSG01','入力必須です');
define('MSG02', 'Emailの形式で入力してください');
define('MSG03','パスワード（再入力）が合っていません');
define('MSG04','半角英数字のみご利用いただけます');
define('MSG05','6文字以上で入力してください');
define('MSG06','256文字以内で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEmailは既に登録されています');
define('MSG09','メールまたはパスワードが違います');
define('MSG10','古いパスワードが違います。');
define('MSG11','古いパスワードと同じです。');
define('MSG12','文字で入力してください。');
define('MSG13','正しくありません。');
define('MSG14','有効期限が切れています。');
define('MSG15','半角数字のみご利用いただけます。');
define('SUC01','パスワードを変更しました。');
define('SUC02','プロフィールを変更しました。');
define('SUC03','メールを送信しました。');
define('SUC04','登録しました。');
define('SUC05','');

//==============================
//バリデーション関数
//==============================
//エラーメッセージ格納用の配列
$err_msg = array();

//バリデーション関数(未入力チェック)
function validRequired($str, $key){
  if($str === ''){
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}

//バリデーション関数（Email形式チェック）
function validEmail($str, $key){
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}
//バリデーション関数（Email重複チェック）
function validEmailDup($email){
  global $err_msg;
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
    $data = array(':email' => $email);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // クエリ結果の値を取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //array_shift関数は配列の先頭を取り出す関数です。クエリ結果は配列形式で入っているので、array_shiftで1つ目だけ取り出して判定します
    if(!empty(array_shift($result))){
      $err_msg['email'] = MSG08;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}
//バリデーション関数（同値チェック）
function validMatch($str1, $str2, $key){
  if($str1 !== $str2){
    global $err_msg;
    $err_msg[$key] = MSG03;
  }
}
//バリデーション関数（最小文字数チェック）
function validMinLen($str, $key, $min = 6){
  if(mb_strlen($str) < $min){
    global $err_msg;
    $err_msg[$key] = MSG05;
  }
}
//バリデーション関数（最大文字数チェック）
function validMaxLen($str, $key, $max = 256){
  if(mb_strlen($str) > $max){
    global $err_msg;
    $err_msg[$key] = MSG06;
  }
}
//バリデーション関数（半角チェック）
function validHalf($str, $key){
  if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG04;
  }
}

//半角数字チェック
function validNumber($str,$key){
	if(!preg_match("/^[0-9]+$/", $str)){
		global $err_msg;
		$err_msg[$key] = MSG15;
	}
}

//固定長チェック
function validLength($str, $key, $len = 8){
	if(mb_strlen($str) !== $len){
		global $err_msg;
		$err_msg[$key] = $len.MSG12;
	}
}
//パスワードチェック
function validPass($str, $key){
	//半角英数字チェック
	validHalf($str, $key);
	//最大文字数チェック
	validMaxLen($str, $key);
	//最小文字数チェック
	validMinLen($str, $key);
}

function validSelect($str,$key){
	if(!preg_match("/^[0-9]+$/", $str)){
		global $err_msg;
		$err_msg[$key] = MSG13;
	}
}
//エラーメッセージ表示
function getErrMsg($key){
	global $err_msg;
	if(!empty($err_msg[$key])){
		return $err_msg[$key];
	}
}

//================================
//ログイン認証
//================================
function isLogin(){
	//ログインしている場合
	if(!empty($_SESSION['login_date'])){
		debug('ログイン済みユーザーです。');
		
		//現在日時が最終ログイン日時＋有効期限を超えていた場合
		if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
			debug('ログイン有効期限オーバーです。');
			
			//セッションを削除(ログアウトする)
			session_destroy();
			return false;
		}else{
			debug('ログイン有効期限いないです。');
			return true;
		}
	}else{
		debug('未ログインユーザーです。');
		return false;
	}
}
//===============================
//データベース
//===============================
//DB接続関数
function dbConnect(){
	//DBへの接続準備
	$dsn = 'mysql:dbname=baskenote;host=localhost;charset=utf8';
	$user = 'root';
	$password = 'root';
	$options = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
	);
	//PDOオブジェクト生成(DBへ接続)
	$dbh = new PDO($dsn, $user, $password, $options);
	return $dbh;
}
//SQL実行関数
function queryPost($dbh, $sql, $data){
	//クエリー作成
	$stmt = $dbh->prepare($sql);
	//プレースホルダに値をセットし、SQL文を実行
	if(!$stmt->execute($data)){
		debug('クエリに失敗しました。');
		debug('失敗したSQL:'.print_r($stmt,true));
		global $err_msg;
		$err_msg['common'] = MSG07;
		return 0;
	}else{
		debug('クエリ成功');
		return $stmt;
	}
}
function getUser($u_id){
	debug('ユーザー情報を取得します。');
	//例外処理
	try {
		//DBへ接続
		$dbh = dbConnect();
		//SQL文作成
		$sql = 'SELECT * FROM users WHERE id = :u_id AND delete_flg = 0';
		$data = array(':u_id' => $u_id);
		//クエリ実行
		$stmt = queryPost($dbh, $sql, $data);

		//クエリ結果のデータを１レコード返却
		if($stmt){
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}else{
			return false;
		}
	} catch (Exception $e) {
		error_log('エラー発生:'. $e->getMessage());
	}
}

function getProductList($currentMinNum = 1, $category, $sort, $span = 20){
  debug('商品情報を取得します。');
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // 件数用のSQL文作成
    $sql = 'SELECT id FROM product';
    if(!empty($category)) $sql .= ' WHERE category_id = '.$category;
    if(!empty($sort)){
      switch($sort){
        case 1:
          $sql .= ' ORDER BY create_date ASC';
          break;
        case 2:
          $sql .= ' ORDER BY create_date DESC';
          break;
      }
    } 
    $data = array();
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $rst['total'] = $stmt->rowCount(); //総レコード数
    $rst['total_page'] = ceil($rst['total']/$span); //総ページ数
    if(!$stmt){
      return false;
    }
    
    // ページング用のSQL文作成
    $sql = 'SELECT * FROM product';
    if(!empty($category)) $sql .= ' WHERE category_id = '.$category;
    if(!empty($sort)){
      switch($sort){
        case 1:
          $sql .= ' ORDER BY create_date ASC';
          break;
        case 2:
          $sql .= ' ORDER BY create_date DESC';
          break;
      }
    } 
    $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
    $data = array();
    debug('SQL：'.$sql);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      // クエリ結果のデータを全レコードを格納
      $rst['data'] = $stmt->fetchAll();
      return $rst;
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

function getProduct($u_id, $p_id){
	debug('ノート情報を取得します。');
	debug('ユーザーID:'.$u_id);
	debug('ノートID:'.$p_id);
	//例外処理
	try {
		//DBへ接続
		$dbh = dbConnect();
		//SQL文作成
		$sql = 'SELECT * FROM product WHERE user_id = :u_id AND id = :p_id AND delete_flg = 0';
		$data = array(':u_id' => $u_id, ':p_id' => $p_id);
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt){
			//クエリ結果のデータを１レコード返却
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}else{
			return false;
			
		}
	} catch (Exception $e) {
		error_log('エラー発生:'.$e->getMessage());
	}
}
function getProductOne($p_id){
	debug('ノート情報を取得します。');
	debug('商品ID:'.$p_id);
	//例外処理
	try{
		$dbh = dbConnect();
		//SQL文作成
		$sql = 'SELECT p.id, p.name, p.comment, p.user_id, p.create_date, p.update_date, c.name AS category FROM product AS p LEFT JOIN category AS c ON p.category_id = c.id WHERE p.id = :p_id AND p.delete_flg = 0 AND c.delete_flg = 0';
		$data = array(':p_id' => $p_id);
		//クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt){
			//クエリ結果のデータを１レコード返却
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}else{
			return false;
		}
	} catch(Exception $e) {
		error_log('エラー発生:'.$e->getMessage());
	}
}
function getMyProducts($u_id){
	debug('自分の商品情報を取得します。');
	debug('ユーザーID:'.$u_id);
	//例外処理
	try {
		//DBへ接続
		$dbh = dbConnect();
		//SQL文作成
		$sql = 'SELECT * FROM product WHERE user_id = :u_id AND delete_flg = 0';
		$data = array(':u_id' => $u_id);
		//クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt){
			//クエリ結果のデータを全レコード返却
		return $stmt->fetchAll();
		}else{
			return false;
		}
	} catch(Exception $e) {
		error_log('エラー発生:'.$e->getMessage());
	}
	
}
function getMsgsAndBord($id){
  debug('msg情報を取得します。');
  debug('掲示板ID：'.$id);
  //例外処理
  try {
    
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT m.id AS m_id, product_id, board_id, to_user, from_user, msg, b.create_date FROM message AS m RIGHT JOIN board AS b ON b.id = m.board_id WHERE b.id = :id AND m.delete_flg = 0 ORDER BY create_date ASC';
    $data = array(':id' => $id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      // クエリ結果の全データを返却
      return $stmt->fetchAll();
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getMyMsgsAndBord($u_id){
  debug('自分のmsg情報を取得します。');
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    
    // まず、掲示板レコード取得
    // SQL文作成
    $sql = 'SELECT * FROM board AS b WHERE b.from_user = :id OR b.to_user = :id AND b.delete_flg = 0';
    $data = array(':id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $rst = $stmt->fetchAll();
    if(!empty($rst)){
      foreach($rst as $key => $val){
        // SQL文作成
        $sql = 'SELECT * FROM message WHERE board_id = :id AND delete_flg = 0 ORDER BY create_date DESC';
        $data = array(':id' => $val['id']);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $rst[$key]['msg'] = $stmt->fetchAll();
      }
    }
    
    if($stmt){
      // クエリ結果の全データを返却
      return $rst;
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getCategory(){
	debug('カテゴリー情報を取得します。');
	//例外処理
	try {
		$dbh = dbConnect();
		//SQL文作成
		$sql = 'SELECT * FROM category';
		$data = array();
		//クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt){
			//クエリ結果用の全データ返却
			return $stmt->fetchAll();
		}else{
			return false;
		}
	} catch(Exception $e) {
		error_log('エラー発生：'.$e->getMessage());
	}
}
function isLike($u_id, $p_id){
	debug('お気に入り情報があるか確認します。');
	debug('ユーザーID:'.$u_id);
	debug('ノートID:'.$p_id);
	try {
		//DBへ接続
		$dbh = dbConnect();
		//SQl文作成
		$sql = 'SELECT * FROM `like` WHERE product_id = :p_id AND user_id = :u_id';
		$data = array(':u_id' => $u_id, ':p_id' => $p_id);
		//クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt->rowCount()){
			debug('お気に入りです。');
			return true;
		}else{
			debug('特に気に入ってません。');
			return false;
		}
	}catch(Exception $e){
		error_log('エラー発生:'.$e->getMessage());
	}
}
function getMyLike($u_id){
  debug('自分のお気に入り情報を取得します。');
  debug('ユーザーID：'.$u_id);
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT * FROM `like` AS l LEFT JOIN product AS p ON l.product_id = p.id WHERE l.user_id = :u_id';
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      // クエリ結果の全データを返却
      return $stmt->fetchAll();
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
//====================================
//メール送信
//====================================
function sendMail($from, $tooo, $subject, $comment){
	if(!empty($tooo) && !empty($subject) && !empty($comment)){
		//文字化けしないように設定
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		
		//メールを送信
		$result = mb_send_mail($tooo, $subject, $comment, "FROM:".$from);
		//送信結果を判定
		if($result){
			debug('メールを送信しました。');
		}else{
			debug('[エラー発生]メールの送信に失敗しました。');
		}
	}
}
//====================================
//その他
//====================================
//サニタイズ
function sanitize($str){
	return htmlspecialchars($str,ENT_QUOTES);
}
//フォーム入力保持
function getFormData($str, $flg = false){
	if($flg){
		$method= $_GET;
	}else{
		$method = $_POST;
	}
	global $err_msg;
	global $dbFormData;
	//ユーザー情報がある場合
	if(!empty($dbFormData)){
		//フォームのエラーがある場合
		if(!empty($err_msg[$str])){
			//POSTにデータがある場合
			if(isset($method[$str])){
				return sanitize($method[$str]);
			}else{
				//ない場合(基本ありえない)はDBの情報を表示
				return sanitize($dbFormData[$str]);
			}
		}else{
			//POSTにデータがあり、DBの情報と違う場合
			if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
				return sanitize($method[$str]);
			}
		}
	}else{
		if(isset($method[$str])){
			return sanitize($method[$str]);
		}
	}
}

function getSessionFlash($key){
	if(!empty($_SESSION[$key]))
		global $data;
	$data = $_SESSION[$key];
	$_SESSION[$key] = '';
	return $data;
}

function makeRandKey($length = 8){
	static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$str = '';
	for ($i = 0; $i < $length; ++$i) {
		$str .=$chars[mt_rand(0,61)];
	}
	return $str;
}
function uploadImg($file, $key){
	debug('画像アップロード処理開始');
	debug('FILE情報'.print_r($file,true));
	
	if(isset($file['error']) && is_int($file['error'])){
		try {
			//バリデーション
			switch ($file['error']) {
				case UPLOAD_ERR_OK: //OK
					break;
				case UPLOAD_ERR_NO_FILE: //ファイル未選択の場合
					throw new RuntimeException('ファイルが選択されていません');
				case UPLOAD_ERR_INI_SIZE: //php.ini定義の最大サイズが超過した場合
					throw new RuntimeException('ファイルサイズが大き過ぎます');
				case UPLOAD_ERR_FORM_SIZE: //フォーム定義の最大サイズが超過した場合
					throw new RuntimeException('ファイルサイズが大きすぎます');
				default: //その他の場合
					throw new RuntimeException('その他のエラーが発生しました');
			}
			$type = @exif_imagetype($file['tmp_name']);
      		if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
          	throw new RuntimeException('画像形式が未対応です');
	  		}
			$path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
			if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
				throw new RuntimeException('ファイル保存時にエラーが発生しました');
			}
			//保存したファイルのパーミッションを変更する
			chmod($path,0644);
			
			debug('ファイルは正常にアップロードされました。');
			debug('ファイルパス:'.$path);
			return $path;
		} catch(Exception $e) {
			debug($e->getMessage());
			global $err_msg;
			$err_msg[$key] = $e->getMessage();
		}
	}
}
//ページング
//$currentPageNum : 現在のページ数
//$totalPageNum : 総ページ数
//$link : 検索用GETパラメータリンク
//$pageColNum : ページネーション表示数
function pagination( $currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
	//現在のページが、総ページ数と同じ　かつ　総ページ数が表示項目数以上なら、左にリンク４個だす
	if( $currentPageNum == $totalPageNum && $totalPageNum > $pageColNum){
		$minPageNum = $currentPageNum - 4;
		$maxPageNum = $currentPageNum;
		//現在のページが、総ページ数の１ページ前なら、左にリンク３個、右に１個だす
	}elseif( $currentPageNum == ($totalPageNum - 1) && $totalPageNum > $pageColNum){
		$minPageNum = $currentPageNum - 3;
		$maxPageNum = $currentPageNum + 1;
		//現在のページが２の場合は左にリンク１個、右にリンク３個だす
	}elseif( $currentPageNum == 2 && $totalPageNum > $pageColNum){
		$minPageNum = $currentPageNum - 1;
		$maxPageNum = $currentPageNum + 3;
		//現ページが１の場合は左に何も出さない。右に５個だす・
	}elseif( $currentPageNum == 1 && $totalPageNum > $pageColNum){
		$minPageNum = $currentPageNum;
		$maxPageNum = 5;
		//総ページ数が表示項目数より少ない場合は、総ページ数をループのmax,るーぷのminを１に設定
	}elseif($totalPageNum < $pageColNum){
		$minPageNum = 1;
		$maxPageNum = $totalPageNum;
		//それ以外は左に２個だす
	}else{
		$minPageNum = $currentPageNum - 2;
		$maxPageNum = $currentPageNum + 2;
	}
	
	echo '<div class="pagination">';
	echo '<ul class="pagination-list">';
	if($currentPageNum !== 1){
		echo '<li class="list-item"><a href="p=1'.$link.'">&lt;</a></li>';
	}
		for($i = $minPageNum; $i <= $maxPageNum; $i++){
			echo '<li class="list-item ';
			if($currentPageNum == $i){ echo 'active'; }
			echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
		}
		if($currentPageNum !== $maxPageNum && $maxPageNum > 1){
			echo '<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
		}
		echo '</ul>';
		echo '</div>';
}
function showImg($path){
	if(empty($path)){
		return 'img/sample-img.png';
	}else{
		return $path;
	}
}
function appendGetParam($arr_del_key = array()){
	if(!empty($_GET)){
		$str = '?';
		foreach($_GET as $key => $val){
			if(!in_array($key,$arr_del_key,true)){
				$str .=$key. '='.$val.'&';
			}
		}
		$str = mb_substr($str, 0, -1,"UTF-8");
		return $str;
	}
}
