<div class="nav">
	<ul class="wrp">
		<li><a href="../">Return to Projects</a></li>
		<li><a href="./"><i class="fa fa-home"></i> Home</a></li>
		<?php
			if(isset($_SESSION['userID1'])) {
		?>
		<li><a href="./browse.php"><i class="fa fa-archive"></i> Browse</a></li>
		<li><a href="./search.php"><i class="fa fa-search"></i> Search</a></li>
		<?php
			};
		?>
		<ul>
			<?php
				if(!isset($_SESSION['userID1'])) {
					echo '<li><a href="./login.php"><i class="fa fa-lock"></i> Login</a></li>';
				} else {
					$userID 		= $_SESSION['userID1'];
					$userPerms 	= $_SESSION['userPerms1'];
					if($userPerms > 3) {
						echo '<li><a href="./admin.php"><i class="fa fa-cog"></i> Admin</a></li>';
					};
					echo '<li><a href="./logout.php"><i class="fa fa-unlock-alt"></i> Logout</a></li>';
				};
			?>
			<li><a href="mailto:dexter.marks-barber@fma.uk.com"><i class="fa fa-life-ring"></i> Web Help</a></li>
		</ul>
	</ul>
</div>