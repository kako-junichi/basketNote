	<footer id="footer">
		Copyright <a href="http://webukatu.com/">jun</a>. All Rights Reserved.
	</footer>

	<script src="js/vendor/jquery-3.3.1.min.js"></script>
	<script>
		$(function() {
			var $ftr = $('#footer');
			if (window.innerHeight > $ftr.offset().top + $ftr.outerHeight()) {
				$ftr.attr({
					'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;'
				});
			}
			//メッセージ表示
			var $jsShowMsg = $('#js-show-msg');
			var msg = $jsShowMsg.text();
			if (msg.replace(/^[\s　]+|[\s　]+$/g, "").length) {
				$jsShowMsg.slideToggle('slow');
				setTimeout(function() {
					$jsShowMsg.slideToggle('slow');
				}, 5000);
			}
		});


		//画像ライブプレビュー
		var $dropArea = $('.area-drop');
		var $fileInput = $('.input-file');

		$dropArea.on('dragover', function(e) {
			e.stopPropagation();
			e.preventDefault();
			$(this).css('border', '3px #ccc dashed');
		})
		$dropArea.on('dragleave', function(e) {
			e.stopPropagation();
			e.preventDefault();
			$(this).css('border', 'none');
		})
		$fileInput.on('change', function(e) {
			$dropArea.css('border', 'none');
			var file = this.files[0],
				$img = $(this).siblings('.prev-img'),
				fileReader = new FileReader();

			//読み込みが完了した際のイベントハンドラ
			fileReader.onload = function(event) {
				//読み込んだデータをimgに設定
				$img.attr('src', event.target.result).show();
			};

			//画像読み込み
			fileReader.readAsDataURL(file);
		});

		//テキストエリアカウント
		var $countUp = $('#js-count'),
			$countView = $('#js-count-view');
		$countUp.on('keyup', function(e) {
			$countView.html($(this).val().length);
		});


		//お気に入り削除・登録
		var $like,
			likeProductId;
		$like = $('.js-click-like') || null;
		likeProductId = $like.data('productid') || null;

		if (likeProductId !== undefined && likeProductId !== null) {
			$like.on('click', function() {
				var $this = $(this);
				$.ajax({
					type: "POST",
					url: "ajaxLike.php",
					data: {
						productId: likeProductId
					}
				}).done(function(data) {
					console.log('Ajax Success');
					//クラス属性をtoggleで着け外しする
					$this.toggleClass('active');
				}).fail(function(msg) {
					console.log('Ajax Error');
				});
			});
		}

	</script>
	</body>

	</html>
