<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
	<?php
		if($_GET['t'] == 0) {
	?>
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
	<?php
		};
	?>
</head>
<body>
	<?php include('nav.php'); ?>
	<div class="main wrp">
		<?php
			if($_SESSION['userPerms1'] < 4) {
		?>
		<p class="alert">you do not have enough permissions to view this page, redirecting...</p>
		<?php
				redirect("./");
			} else {
				if(!isset($_GET['t'])) {
		?>
		<p class="alert">a creation type is required, redirecting...</p>
		<?php
					redirect("./admin.php");
				} else {
					$type = $_GET['t'];

					if($type == 0) {
		?>
		<form class="register" method="post" action="./action.php?a=create">
			<input name="formType" type="hidden" value="0" required />
			<table>
				<tr>
					<td colspan="2">
						<h1 class="mrg-btm-x-lrg">Create a User<h1>
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
					<td>
						<p>Select Permissions</p>
					</td>
					<td>
						<select name="formPerms">
							<option selected disabled>Select an Option</option>
							<option value="1">Normal User</option>
							<option value="4">Admin User</option>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit" disabled="true">Create</button>
					</td>
				</tr>
			</table>
		</form>
		<?php
			} else if($type == 1) {
		?>
		<form method="post" action="./action.php?a=create">
			<input name="formType" type="hidden" value="1" required />
			<table>
				<tr>
					<td colspan="2">
						<h1 class="mrg-btm-x-lrg">Create a Category<h1>
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter a Category Name</p>
					</td>
					<td>
						<input name="formName" type="text" placeholder="Enter a Category Name" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Select Permissions</p>
					</td>
					<td>
						<select name="formPerms">
							<option selected disabled>Select an Option</option>
							<option value="1">Visible</option>
							<option value="0">Not Visible</option>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Create</button>
					</td>
				</tr>
			</table>
		</form>
		<?php
			} else if($type == 2) {
		?>
		<form method="post" action="./action.php?a=create">
			<input name="formType" type="hidden" value="2" required />
			<table>
				<tr>
					<td colspan="2">
						<h1 class="mrg-btm-x-lrg">Create a Sub Category<h1>
					</td>
				</tr>
				<tr>
					<td>Select a Parent</td>
					<td>
						<select name="formParent">
		<?php
								$getParents = $con->prepare("SELECT itemID,itemName FROM items WHERE itemType=1 OR itemType=2 ORDER BY itemType ASC");
								$getParents->execute();
								$getParents->store_result();
								if($getParents->num_rows > 0) {
		?>
							<option selected disabled>Select a Parent</option>
		<?php
									$getParents->bind_result($itemID,$itemName);
									while($getParents->fetch()) {
		?>
							<option value="<?php echo $itemID; ?>"><?php echo $itemName; ?></option>
		<?php
									};
								} else {
		?>
							<option selected disabled>No Parents Found</option>
		<?php
								};
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter a Category Name</p>
					</td>
					<td>
						<input name="formName" type="text" placeholder="Enter a Category Name" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Select Permissions</p>
					</td>
					<td>
						<select name="formPerms">
							<option selected disabled>Select an Option</option>
							<option value="1">Visible</option>
							<option value="0">Not Visible</option>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Create</button>
					</td>
				</tr>
			</table>
		</form>
		<?php
			} else if($type == 3) {
		?>
		<form method="post" action="./action.php?a=create">
			<input name="formType" type="hidden" value="3" required />
			<table>
				<tr>
					<td colspan="2">
						<h1 class="mrg-btm-x-lrg">Create an Item Type<h1>
					</td>
				</tr>
				<tr>
					<td>Enter an Item Type Name</td>
					<td><input name="formName" type="text" placeholder="Enter an Item Type Name" autocomplete="off" required />
				</tr>
				<tr>
					<td>
						<p>Select Permissions</p>
					</td>
					<td>
						<select name="formPerms">
							<option selected disabled>Select an Option</option>
							<option value="1">Visible</option>
							<option value="0">Not Visible</option>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Create</button>
					</td>
				</tr>
			</table>
		</form>
		<?php
			} else if($type == 4) {
		?>
		<form method="post" action="./action.php?a=create">
			<input name="formType" type="hidden" value="4" required />
			<table>
				<tr>
					<td colspan="2">
						<h1 class="mrg-btm-x-lrg">Create an Item<h1>
					</td>
				</tr>
				<tr>
					<td>Select a Parent *</td>
					<td>
						<select name="formParent">
		<?php
								$getParents = $con->prepare("SELECT itemID,itemName FROM items WHERE itemType=1 OR itemType=2 ORDER BY itemType ASC");
								$getParents->execute();
								$getParents->store_result();
								if($getParents->num_rows > 0) {
		?>
							<option selected disabled>Select a Parent</option>
		<?php
									$getParents->bind_result($itemID,$itemName);
									while($getParents->fetch()) {
		?>
							<option value="<?php echo $itemID; ?>"><?php echo $itemName; ?></option>
		<?php
									};
								} else {
		?>
							<option selected disabled>No Parents Found</option>
		<?php
								};
								$getParents->close();
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Select an Item Type *</td>
					<td>
						<select name="formItemType">
		<?php
								$getTypes = $con->prepare("SELECT itemID,itemName FROM items WHERE itemType=3");
								$getTypes->execute();
								$getTypes->store_result();
								if($getTypes->num_rows > 0) {
		?>
						<option selected disabled>Select an Item Type</option>
		<?php
									$getTypes->bind_result($itemID,$itemName);
									while($getTypes->fetch()) {
		?>
							<option value="<?php echo $itemID; ?>"><?php echo $itemName; ?></option>
		<?php
									};
								} else {
		?>
							<option selected disabled>No Item Types Found</option>
		<?php
								};
								$getTypes->close();
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter a Item Name *</p>
					</td>
					<td>
						<input name="formName" type="text" placeholder="Enter a Item Name" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter Item Version</p>
					</td>
					<td>
						<input name="formVer" type="text" placeholder="Enter Item Version" autocomplete="off" />
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter Item Serial</p>
					</td>
					<td>
						<input name="formSerial" type="text" placeholder="Enter Item Serial Number" autocomplete="off" />
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter Item Asset Number</p>
					</td>
					<td>
						<input name="formAssetNum" type="text" placeholder="Enter Item Asset Number" autocomplete="off" />
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter Item Description</p>
					</td>
					<td>
						<input name="formDesc" type="text" placeholder="Enter Item Description" autocomplete="off" />
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter Item Comments</p>
					</td>
					<td>
						<input name="formComs" type="text" placeholder="Enter Item Comments" autocomplete="off" />
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter Item Location (default) *</p>
					</td>
					<td>
						<input name="formLoc" type="text" placeholder="Enter Item Location" autocomplete="off" />
					</td>
				</tr>
				<tr>
					<td>
						<p>Select Permissions</p>
					</td>
					<td>
						<select name="formPerms">
							<option selected disabled>Select an Option</option>
							<option value="1">Visible</option>
							<option value="0">Not Visible</option>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn-default" type="submit">Submit</button>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p>Fields with a <b>*</b> are <b>required</b>.</p>
						<p>Default Location means: Where the Item <b>should</b> be.</p>
						<p>You can <b>edit the Current Location at a later stage</b>.</p>
					</td>
			</table>
		</form>
		<?php
			} else {
		?>
		<p class="alert">a valid creation type is required, redirecting...</p>
		<?php
						redirect("./admin.php");
					};
				};
			};
		?>
	</div>
</body>
</html>
