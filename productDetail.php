<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('ノート詳細ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//==============================
//画面処理
//==============================

//画面表示用データ取得
//==============================
//ノートIDのGETパラメータを取得
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
// DBからノートデータを取得
$viewData = getProductOne($p_id);
//パラメータに不正な値が入っているかチェック
if(empty($viewData)){
	error_log('エラー発生:指定ページに不正な値が入りました。');
	header("Location:index.php"); //トップページへ
}
debug('取得したDBデータ'.print_r($viewData,true));


//例外処理
try {
	//DB接続
	$dbh = dbConnect();
	//SQL文接続
	$sql = 'INSERT INTO board(from_user, to_user, product_id, create_date) VALUES (:f_uid, :t_uid, :p_id, :date)';
	$data = array(':f_uid' => $viewData['user_id'], ':t_uid' =>$_SESSION['user_id'], ':p_id' => $p_id, ':date' => date('Y-m-d H:i:s'));
	//クエリ実行
	$stmt = queryPost($dbh, $sql, $data);
	
	//クエリ成功の場合
	if($stmt){
		$_SESSION['msg_success'] = SUC05;
		debug('連絡掲示板へ遷移します。');
		header("Location:msg.php?m_id=".$dbh->lastInsertId()); //連絡掲示板へ
	}
	
} catch(Exception $e){
	error_log('エラー発生:'.$e->getMessage());
	$err_msg['common'] = MSG07;
	
}

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<'); ?>
<?php
$siteTitle = 'ノート詳細';
require('head.php');
?>

<body class="page-productDetail page-1colum">
	<style>
		.badge {
			padding: 5px 10px;
			color: white;
			background: #7acee6;
			margin-right: 10px;
			font-size: 16px;
			vertical-align: middle;
			position: relative;
			top: -4px;
		}

		#main {
			background: white;
		}

		#main .title {
			font-size: 28px;
			padding: 10px 0;
		}

		.product-img-container {
			width: 750px;
			background: #f6f5f4;
			padding: 15px;
			box-sizing: border-box;
			min-height: 600px;
			margin-left: 350px;
		}

		.product-img-container:hover {
			cursor: pointer;
		}

		.product-detail {
			padding: 15px;
			margin-top: 15px;
			min-height: 150px;
		}

		.product-buy {
			overflow: hidden;
			margin-top: 15px;
			margin-bottom: 50px;
			height: 50px;
			line-height: 50px;
		}

		.product-buy .item-left {
			float: left;
		}

		.product-buy .item-right {
			float: right;
		}

		.product-buy .price {
			font-size: 32px;
			margin-right: 30px;
		}

		.product-buy .btn {
			border: none;
			font-size: 18px;
			padding: 10px 30px;
		}

		.product-buy .btn:hover {
			cursor: pointer;
		}

		/*お気に入りアイコン*/
		.icn-like {
			float: right;
			color: #ddd;
		}

		.icn-like:hover {
			cursor: pointer;
		}

		.icn-like.active {
			float: right;
			color: #fe8a8b;
		}

	</style>
	<!-- ヘッダー -->
	<?php
	require('header.php');
	?>

	<!-- Main -->
	<section id="main">

		<div class="title">
			<span class="badge"><?php echo sanitize($viewData['category']); ?></span>
			<?php echo sanitize($viewData['name']); ?>
			<i class="fa fa-heart icn-like js-click-like <?php if(isLike($_SESSION['user_id'], $viewData['id'])){ echo 'active'; } ?>" aria-hidden="true" data-productid="<?php echo sanitize($viewData['id']); ?>"></i>
		</div>
		<div class="product-img-container">

			<p><?php echo sanitize($viewData['comment']); ?></p>
		</div>
		<div class="product-buy">
			<div class="item-left">
				<a href="index.php<?php echo appendGetParam(array('p_id')); ?>">&lt; ノート一覧に戻る</a>
			</div>
			<form action="" method="post">
				<div class="item-right">
					<input type="submit" value="コメントする" name="submit" class="btn btn-primary" style="margin-top:0;">
				</div>
			</form>
		</div>
	</section>

	<!-- footer -->
	<?php
	require('footer.php');
	?>
