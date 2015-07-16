<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
</head>
<body>
	<?php include('nav.php'); ?>
	<div class="main wrp">
		<?php
			if(!isset($_SESSION['userID1'])) {
				echo '<p class="alert">You Need to be Logged In First, Redirecting...</p>';
				redirect("./login.php");
			} else{
				echo '<p class="alert">Logging You Out, Redirecting...</p>';
				redirect("./");
			};
		?>
	</div>
</body>
</html>