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
		?>
		<form method="post" action="./action.php?a=login">
			<table>
				<tr>
					<td colspan="2">
						<h2>Login</h2>
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter a Username</p>
					</td>
					<td>
						<input name="formName" type="text" placeholder="Enter a Username" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter a Password</p>
					</td>
					<td>
						<input name="formPass" type="password" placeholder="Enter a Password" autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn-default" type="submit">Login</button>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<a href="./register.php">Need an Account?</a>
					</td>
				</tr>
			</table>
		</form>
		<?php
			} else {
		?>
		<p class="alert">You Are Already Logged In, Redirecting...</p>
		<?php
				redirect("./");
			};
		?>
	</div>
</body>
</html>