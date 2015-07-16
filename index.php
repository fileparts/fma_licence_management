<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
</head>
<body>
	<?php include('nav.php'); ?>
	<div class="main wrp">
		<div class="clr mrg-btm-x-lrg">
			<h1 class="mrg-btm">Licence Management</h1>
			<p>
				<?php if(!isset($_SESSION['userID1'])) {
					echo 'This website is not linked to the Active Directory, so please make a separate account.<br>';
				}; ?>
				This website allows people to book out Licences and Assets.
			</p>
		</div>
		<div class="clr">
			<?php if(!isset($_SESSION['userID1'])) { ?>
				<a class="btn btn-med btn-success" href="./login.php"><i class="fa fa-fw fa-user"></i> Login</a>
	      <a class="btn btn-med btn-info" href="./register.php"><i class="fa fa-fw fa-user-plus"></i> Register</a>
			<?php } else {
				if($_SESSION['userPerms1'] > 3) {
			?>
			      <a class="btn btn-med btn-warning" href="./admin.php"><i class="fa fa-fw fa-cog"></i> Admin</a>
			<?php
			  };
			?>
			      <a class="btn btn-med btn-danger" href="./logout.php"><i class="fa fa-fw fa-unlock-alt"></i> Logout</a>
			<?php }; ?>
		</div>
	</div>
</body>
</html>
