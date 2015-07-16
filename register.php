<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
	<script>
		$(document).ready(function() {
			var pass = ["input[name=formPass]","input[name=rePassword]"];
			var passVal = ["",""];
			
			$(pass[0]).keyup(function() {
				passVal[0] = $(this).val();
				comparePass();
			});
			$(pass[1]).keyup(function() {
				passVal[1] = $(this).val();
				comparePass();
			});
			function comparePass() {
				if(passVal[0] == passVal[1] && passVal[0].length > 0) {
					$(pass[1]).css("border-color","#2ECC71");
					$('form.register button').removeAttr("disabled");
				} else if(passVal[0] != passVal[1]) {
					$(pass[1]).css("border-color","#E74C3C");
					$('form.register button').attr("disabled","true");
				} else {
					$(pass[1]).removeAttr("style");
					$('form.register button').attr("disabled","true");
				};
			};
		});
	</script>
</head>
<body>
	<?php include('nav.php'); ?>
	<div class="main wrp">
		<?php
			if(!isset($_SESSION['userID1'])) {	
		?>
		<form class="register" method="post" action="./action.php?a=register">
			<table>
				<tr>
					<td colspan="2">
						<h2>Register</h2>
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
						<p>Enter a Email Address</p>
					</td>
					<td>
						<input name="formEmail" type="text" placeholder="Enter a Email Address" autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter First Name</p>
					</td>
					<td>
						<input name="formFirst" type="text" placeholder="Enter a First Name" autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter a Last Name(s)</p>
					</td>
					<td>
						<input name="formLast" type="text" placeholder="Enter a Last Name(s)" autocomplete="off" required />
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
					<td>
						<p>Retype Password</p>
					</td>
					<td>
						<input name="rePassword" type="password" placeholder="Retype Password" autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn-default" type="submit" disabled="true">Register</button>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<a href="./login.php">Already Have an Account?</a>
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