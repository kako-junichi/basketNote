<?php

//共通変数・関数ファイルを読み込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('プロフィール編集ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//=============================
//画面処理
//=============================
//DBからユーザーデータを取得
$dbFormData = getUser($_SESSION['user_id']);

debug('取得したユーザー情報：'.print_r($dbFormData,true));

//postされていた場合
if(!empty($_POST)){
	debug('POST送信があります。');
	debug('POST情報:'.print_r($_POST,true));
	debug('FILE情報:'.print_r($_FILES,true));
	
	//変数にユーザー情報を代入
	$username = $_POST['username'];
	$age = $_POST['age'];
	$email = $_POST['email'];
	//画像をアップロードし、パスを格納
	$pic = (!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'],'pic') : '';
	//画像をPOSTしていない(登録していない)が既にDBに登録されている場合、DBのパスを入れる
	$pic = ( !empty($pic) && !empty($dbFormData['pic']) ) ? $dbFormData['pic'] : $pic;
	
	//DBの情報と入力情報が異なる場合にバリデーションを行う
	if($dbFormData['username'] !== $username){
		validMaxLen($username,'username');
	}
	if($dbFormData['age'] !== $age){
		validMaxLen($age,'age');
		validNumber($age,'age');
	}
	if($dbFormData['email'] !== $email){
		validMaxLen($email,'email');
		if(empty($err_msg['email'])){
			validEmailDup($email);
		}
		validEmail($email,'email');
		validRequired($email,'email');
	}
	if(empty($err_msg)){
		debug('バリデーションOKです。');
		
		//例外処理
		try {
			$dbh = dbConnect();
			//SQL文作成
			$sql = 'UPDATE users SET username = :u_name, age = :age, email = :email,pic = :pic WHERE id = :u_id';
			$data = array(':u_name' => $username, 'age' => $age, ':email' => $email, ':pic' => $pic, ':u_id' => $dbFormData['id']);
			//クエリ実行
			$stmt = queryPost($dbh, $sql, $data);
			
			//クエリ成功の場合
			if($stmt){
				$_SESSION['msg_success'] = SUC02;
				debug('マイページへ遷移します。');
				header("Location:mypage.php"); //マイページへ
			}
			
		} catch (Exception $e) {
			error_log('エラー発生:'.$e->getMessage());
			$err_msg['common'] = MSG07;
			
		}
	}
}
debug('画面表示処理終了　<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'プロフィール編集';
require('head.php');
?>

<body class="page-profEdit page-2colum page-logined">


	<?php
	require('header.php');
	?>

	<!-- メインコンテンツ -->
	<div id="contents" class="site-width">

		<!-- Main -->
		<section id="main">
			<div class="form-container">
				<form action="" method="post" class="form" enctype="multipart/form-data">
					<h2 class="title">プロフィール編集</h2>
					<div class="area-msg">
						<?php
					if(!empty($err_msg['common'])) echo $err_msg['common'];
					?>
					</div>
					<div class="area-msg">
						<?php
					if(!empty($err_msg['username'])) echo $err_msg['username']; 
					?>
					</div>
					<label class="<?php if(!empty($err_msg['username'])) echo 'err' ?>">
						名前
						<input type="text" name="username" value="<?php echo getFormData('username') ?>">
					</label>
					<div class="area-msg">
						<?php
					if(!empty($err_msg['age'])) echo $err_msg['age'];
					?>
					</div>
					<label style="text-align:left" class="<?php if(!empty($err_msg['age'])) echo 'err' ?>">
						年齢
						<input type="number" name="age" value="<?php echo getFormData('age'); ?>">
					</label>
					<div class="area-msg">
						<?php
					if(!empty($err_msg['email'])) echo $err_msg['email'];
					?>
					</div>
					<label class="<?php if(!empty($err_msg['email'])) echo 'err' ?>">
						Email
						<input type="text" name="email" value="<?php echo getFormData('email'); ?>">
					</label>

					<label class="area-drop <?php if(!empty($err_msg['pic'])) echo'err'; ?>" style="height:370px;line-height:370px;">
						<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
						<input type="file" name="pic" class="input-file" style="height:370px;">
						<img src="<?php echo getFormData('pic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo 'display:none;' ?>">
						ドラッグ＆ドロップ
					</label>
					<div class="area-msg">
						<?php
					if(!empty($err_msg['pic'])) echo $err_msg['pic'];
					?>
					</div>
					<div class="btn-container">
						<input type="submit" class="btn btn-mid" value="変更する">
					</div>
				</form>
			</div>
		</section>
		<?php
	require('sidebar.php');
	?>

	</div>
	<?php
require('footer.php');
?>
