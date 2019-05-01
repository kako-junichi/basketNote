<header class="header">
	<div class="site-width">
		<h1><a href="index.php">B.B.Progress Management</a></h1>
		<nav id="top-nav">
			<ul>
				<?php
				if(!empty($_SESSION['user_id'])){
				?>
				<li><a href="signup.php" class="btn btn-primary">Sign Up</a></li>
				<li><a href="login.php">Login</a></li>
				<?php
				}else{
					?>
				<li><a href="mypage.php">Mypage</a></li>
				<li><a href="logout.php">Logout</a></li>
				<?php
				}
				?>
			</ul>
		</nav>

	</div>
</header>
