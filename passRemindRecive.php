<?php

//共通関数・関数ファイルを読み込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('パスワード再発行認証キー入力ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証は無し(ログインできない人が使う画面のため)

//SESSIONに認証キーがあるか確認、なければリダイレクト
if(empty($_SESSION['auth.key'])) {
	header("Location:passRemindSend.php"); //認証キー送信ページへ
}

//================================
//画面処理
//================================
//post送信されていた場合
if(!empty($_POST)){
	debug('POST送信があります。');
	debug('POST情報:'.print_r($_POST,true));
	
	//変数に認証キーを代入
	$auth_key = $_POST['token'];
	
	//未入力チェック
	validRequired($auth_key, 'token');
	
	if(empty($err_msg)){
		debug('未入力チェックOK');
		
		//固定長チェック
		　validLength($auth_key,'token');
		//半角チェック
		validHalf($auth_key,'token');
		
		if(empty($err_msg)){
			debug('バリデーションOK');
			
			if($auth_key !== $_SESSION['auth_key']){
				$err_msg['common'] = MSG13;
			}
			if(time() > $_SESSION['auth_key_limit']) {
				$err_msg['common'] = MSG14;
			}
			
			if(empty($err_msg)){
				debug('認証OK');
				
				$pass = makeRandKey(); //パスワード生成
				
				//例外処理
				try {
					//DBへ接続
					$dbh = dbConnect();
					//SQL文作成
					$sql = 'UPDATE users SET password= :pass WHERE email = :email AND delete_flg = 0';
					$data = array(':email' => $_SESSION['auth_email'], ':pass' =>password_hash($pass,PASSWORD_DEFAULT));
					//クエリ実行
					$stmt = queryPost($dbh, $sql, $data);
					
					//クエリ成功の場合
					if($stmt){
						debug('クエリ成功');
						
						//メールを送信
						$from = 'info@webukatu.com';
						$to = $_SESSION['auth_email'];
						$subject = '[パスワード再発行完了] | B.B.Progress Management';
						$comment = <<<EOT
本メールアドレス宛にパスワードの再発行をいたしました。
下記のURLにて再発行のパスワードをご入力頂き、ログインください。

ログインページ: http://localhost:8888/baskenote/login.php
再発行パスワード: {$pass}
※ログイン後、パスワードの変更をお願いいたします。

////////////////////////////////////////
B.B.Progress Managementカスタマーセンター
URL
E-mail info@webukatu.com
EOT;
						sendMail($from, $to, $subject, $comment);
						
						//セッション削除
						session_unset();
						$_SESSION['msg_successs'] = SUC03;
						debug('セッション変数の中身:'.print_r($_SESSION,true));
						
						header("Location:login.php");

					}else{
						debug('ログインに失敗しました。');
						$err_msg['common'] = MSG07;
					}
				} catch(Exception $e) {
					error_log('クエリに失敗しました。');
					$err_msg['common'] = MSG07;
					
				}
			}
		}
	}
}
?>



<?php
$siteTitle = 'パスワード再発行認証';
require('head.php');
?>

<body class="page-signup page-1colum">

	<!-- メニュー -->
	<?php
	require('header.php');
	?>
	<p id="js-show-msg" sytle="disply:none;" class="msg-slide">
		<?php echo getSessionFlash('msg_success'); ?>
	</p>

	<!-- メインコンテンツ -->
	<div id="contents" class="site-width">

		<section id="main">

			<div class="form-container">

				<form action="" method="post" class="form">
					<p>ご指定のメールアドレスをお送りした[パスワード再発行認証]メール内にある「認証キー」をご入力ください。</p>
					<div class="area-msg">
						<?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
					</div>
					<label class="<?php if(!empty($err_msg['token']))  echo 'err' ?>"></label>
					<input type="text" name="token" valud="<?php echo getFormData('token'); ?>">
					<div class="area-msg">
						<?php if(!empty($err_msg['token'])) echo $err_msg['token']; ?>
					</div>
					<div class="btn-container">
						<input type="submit" class="btn btn-mid" value="再発行する">
					</div>
				</form>
			</div>
			<a href="passRemindSend.php">&lt; パスワード再発行メールを再度発送する</a>
		</section>
	</div>


	<!-- footer -->
	<?php
	require('footer.php');
	?>
