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
					$('form.pass button').removeAttr("disabled");
				} else if(passVal[0] != passVal[1]) {
					$(pass[1]).css("border-color","#E74C3C");
					$('form.pass button').attr("disabled","true");
				} else {
					$(pass[1]).removeAttr("style");
					$('form.pass button').attr("disabled","true");
				};
			};
		});
	</script>
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
		<p class="alert">an edit type is required, redirecting...</p>
		<?php
					redirect("./admin.php");
				} else {
					$type = $_GET['t'];

					if(!isset($_GET['id'])) {
		?>
		<p class="alert">an id is required, redirecting...</p>
		<?php
						redirect("./admin.php");
					} else {
						$id = $_GET['id'];

						if($type == 0) {
							$getUser = $users->prepare("SELECT userID,userName,userFirst,userLast,userEmail,userPerms FROM users WHERE userID=? AND userPerms < 5");
							$getUser->bind_param("i", $id);
							$getUser->execute();
							$getUser->store_result();
							if($getUser->num_rows == 0) {
		?>
		<p class="alert">user not found, redirecting...</p>
		<?php
								redirect("./admin.php");
							} else {
								$getUser->bind_result($userID,$userName,$userFirst,$userLast,$userEmail,$userPerms);
								while($getUser->fetch()) {
		?>
		<form class="clr mrg-btm-x-lrg" method="post" action="./action.php?a=edit">
			<input name="formID" type="hidden" value="<?php echo $userID; ?>" required />
			<input name="formType" type="hidden" value="0" required />
			<table class="fixed">
				<tr>
					<td colspan="2"><h1 class="mrg-btm-x-lrg">Edit <?php echo $userName; ?> / <?php echo $userFirst. ' ' . $userLast; ?></h1></td>
				</tr>
				<tr>
					<td><p>Edit Username</p></td>
					<td><input name="formName" type="text" value="<?php echo $userName; ?>" autocomplete="off" required /></td>
				</tr>
				<tr>
					<td><p>Edit Email</p></td>
					<td><input name="formEmail" type="text" value="<?php echo $userEmail; ?>" autocomplete="off" required /></td>
				</tr>
				<tr>
					<td><p>Edit First Name</p></td>
					<td><input name="formFirst" type="text" value="<?php echo $userFirst; ?>" autocomplete="off" required /></td>
				</tr>
				<tr>
					<td><p>Edit First Last</p></td>
					<td><input name="formLast" type="text" value="<?php echo $userLast; ?>" autocomplete="off" required /></td>
				</tr>
				<tr>
					<td><p>Edit Permissions</p></td>
					<td>
						<select name="formPerms" required>
							<option disabled>Select an Option</option>
		<?php
									if($userPerms == 1) {
		?>
							<option value="1" selected>Normal User</option>
		<?php
									} else {
		?>
							<option value="1">Normal User</option>
		<?php
									};
									if($userPerms == 4) {
		?>
							<option value="4" selected>Admin User</option>
		<?php
									} else {
		?>
							<option value="4">Admin User</option>
		<?php
									};
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</td>
			</table>
		</form>

		<form class="clr pass" method="post" action="./action.php?a=edit">
			<input name="formID" type="hidden" value="<?php echo $userID; ?>" required />
			<input name="formType" type="hidden" value="1" required />
			<table class="fixed">
				<tr>
					<td><p>New Password</p></td>
					<td><input name="formPass" type="password" placeholder="New Password" autocomplete="off" required />
				</tr>
				<tr>
					<td><p>Retype Password</p></td>
					<td><input name="rePassword" type="password" placeholder="Retype Password" autocomplete="off" required />
				</tr>
				<tr>
					<td></td>
					<td><button class="confirm btn-warning" type="submit" disabled="true">Submit</button></td>
				</tr>
			</table>
		</form>
		<?php
								};
							};
							$getUser->close();
						} else if($type == 1) {
							$getItem = $con->prepare("SELECT itemID,itemName,itemDesc,itemComs,itemType,itemPerms FROM items WHERE itemID=?");
							$getItem->bind_param("i", $id);
							$getItem->execute();
							$getItem->store_result();
							if($getItem->num_rows == 0) {
		?>
		<p class="alert">a valid item id is required, redirecting...</p>
		<?php
								redirect("./admin.php");
							} else {
								$getItem->bind_result($itemID,$itemName,$itemDesc,$itemComs,$itemType,$itemPerms);
								while($getItem->fetch()) {
									$itemType = $itemType;

									$itemID = $itemID;
									$itemName = $itemName;
									$itemDesc = $itemDesc;
									$itemComs = $itemComs;
									$itemPerms = $itemPerms;
								};

								if($itemType == 1) {
		?>
		<form class="clr" method="post" action="./action.php?a=edit">
			<input name="formID" type="hidden" value="<?php echo $itemID; ?>" required />
			<input name="formType" type="hidden" value="2" required />
			<table class="fixed">
				<tr>
					<td colspan="2"><h1 class="mrg-btm-x-lrg">Edit <?php echo ucwords($itemName); ?></h1></td>
				</tr>
				<tr>
					<td><p>Edit Category Name</p></td>
					<td><input name="formName" type="text" value="<?php echo ucwords($itemName); ?>" autocomplete="off" required /></td>
				</tr>
				<tr>
					<td><p>Edit Permissions</p></td>
					<td>
						<select name="formPerms" required>
							<option disabled>Select an Option</option>
		<?php
									if($itemPerms == 1) {
		?>
							<option value="1" selected>Visible</option>
		<?php
									} else {
		?>
							<option value="1">Visible</option>
		<?php
									};
									if($itemPerms == 0) {
		?>
							<option value="0" selected>Not Visible</option>
		<?php
									} else {
		?>
							<option value="0">Not Visible</option>
		<?php
									};
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</td>
			</table>
		</form>
		<?php
								} else if($itemType == 2) {
		?>
		<form class="clr" method="post" action="./action.php?a=edit">
			<input name="formID" type="hidden" value="<?php echo $itemID; ?>" required />
			<input name="formType" type="hidden" value="3" required />
			<table class="fixed">
				<tr>
					<td colspan="2"><h1 class="mrg-btm-x-lrg">Edit <?php echo ucwords($itemName); ?></h1></td>
				</tr>
				<tr>
					<td><p>Edit Sub Category Name</p></td>
					<td><input name="formName" type="text" value="<?php echo ucwords($itemName); ?>" autocomplete="off" required /></td>
				</tr>
				<tr>
					<td><p>Edit Permissions</p></td>
					<td>
						<select name="formPerms" required>
							<option disabled>Select an Option</option>
		<?php
									if($itemPerms == 1) {
		?>
							<option value="1" selected>Visible</option>
		<?php
									} else {
		?>
							<option value="1">Visible</option>
		<?php
									};
									if($itemPerms == 0) {
		?>
							<option value="0" selected>Not Visible</option>
		<?php
									} else {
		?>
							<option value="0">Not Visible</option>
		<?php
									};
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</td>
			</table>
		</form>

		<form class="clr mrg-top-x-lrg" method="post" action="./action.php?a=edit">
			<input name="formID" type="hidden" value="<?php echo $itemID; ?>" required />
			<input name="formType" type="hidden" value="4" required />
			<table class="fixed">
				<tr>
					<td colspan="2"><p>Edit <?php echo ucwords($itemName); ?>'s Parent</p></td>
				</tr>
				<tr>
					<td><p>Select Parent</p></td>
					<td>
						<select name="formParent" required>
							<option disabled>Select an Option</option>
		<?php
									$childID = $itemID;
									$getParent = $con->prepare("SELECT parentID FROM links WHERE childID=?");
									$getParent->bind_param("i", $childID);
									$getParent->execute();
									$getParent->store_result();
									$getParent->bind_result($parentID);
									while($getParent->fetch()) {
										$parentID = $parentID;
									};
									$getParent->close();

									$getParents = $con->prepare("SELECT itemID,itemName FROM items WHERE itemID!=? AND (itemType=1 OR itemType=2) ORDER BY itemType ASC");
									$getParents->bind_param("i", $childID);
									$getParents->execute();
									$getParents->store_result();
									if($getParents->num_rows > 0) {
										$getParents->bind_result($itemID,$itemName);
										while($getParents->fetch()) {
											if($itemID == $parentID) {
		?>
							<option value="<?php echo $itemID; ?>" selected><?php echo ucwords($itemName); ?></option>
		<?php
											} else {
		?>
							<option value="<?php echo $itemID; ?>"><?php echo ucwords($itemName); ?></option>
		<?php
											};
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
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</tr>
			</table>
		</form>
		<?php
								} else if($itemType == 3) {
		?>
		<form method="post" action="./action.php?a=edit">
			<input name="formID" type="hidden" value="<?php echo $itemID; ?>" required />
			<input name="formType" type="hidden" value="5" required />
			<table class="fixed">
				<tr>
					<td colspan="2"><h1 class="mrg-btm-x-lrg">Edit <?php echo ucwords($itemName); ?></h1></td>
				</tr>
				<tr>
					<td><p>Edit Item Type Name</p></td>
					<td><input name="formName" type="text" value="<?php echo ucwords($itemName); ?>" autocomplete="off" required /></td>
				</tr>
				<tr>
					<td><p>Edit Permissions</p></td>
					<td>
						<select name="formPerms" required>
							<option disabled>Select an Option</option>
		<?php
									if($itemPerms == 1) {
		?>
							<option value="1" selected>Visible</option>
		<?php
									} else {
		?>
							<option value="1">Visible</option>
		<?php
									};
									if($itemPerms == 0) {
		?>
							<option value="0" selected>Not Visible</option>
		<?php
									} else {
		?>
							<option value="0">Not Visible</option>
		<?php
									};
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</td>
			</table>
		</form>
		<?php
								} else if($itemType == 4) {
		?>
		<form class="clr" method="post" action="./action.php?a=edit">
			<input name="formID" type="hidden" value="<?php echo $itemID; ?>" required />
			<input name="formType" type="hidden" value="6" required />
			<table class="fixed">
				<tr>
					<td colspan="2"><h1 class="mrg-btm-x-lrg">Edit <?php echo ucwords($itemName); ?></h1></td>
				</tr>
				<tr>
					<td>
						<p>Edit Item Name</p>
					</td>
					<td>
						<input name="formName" type="text" value="<?php echo ucwords($itemName); ?>" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>Edit Item Type</td>
					<td>
						<select name="formItemType">
		<?php
								$getType = $con->prepare("SELECT itemVer,itemSerial,itemAssetNum,itemLocCur,itemLoc,itemType FROM itemdetails WHERE itemID=?");
								$getType->bind_param("i", $itemID);
								$getType->execute();
								$getType->store_result();
								$getType->bind_result($itemVer,$itemSerial,$itemAssetNum,$itemLocCur,$itemLoc,$itemTypeID);
								while($getType->fetch()) {
									$itemVer = $itemVer;
									$itemSerial = $itemSerial;
									$itemAssetNum = $itemAssetNum;
									$itemLocCur = $itemLocCur;
									$itemLoc = $itemLoc;
									$currentTypeID = $itemTypeID;
								};
								$getType->close();

								$getTypes = $con->prepare("SELECT itemID,itemName FROM items WHERE itemType=3");
								$getTypes->execute();
								$getTypes->store_result();
								if($getTypes->num_rows > 0) {
		?>
						<option disabled>Select an Item Type</option>
		<?php
									$getTypes->bind_result($typeID,$typeName);
									while($getTypes->fetch()) {
										if($typeID == $currentTypeID) {
		?>
							<option value="<?php echo $typeID; ?>" selected><?php echo $typeName; ?></option>
		<?php
										} else {
		?>
							<option value="<?php echo $typeID; ?>"><?php echo $typeName; ?></option>
		<?php
										};
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
						<p>Edit Item Description</p>
					</td>
					<td>
						<input name="formDesc" type="text" value="<?php echo $itemDesc; ?>" autocomplete="off" required />
					</td>
				</tr>
					<td>
						<p>Edit Item Comments</p>
					</td>
					<td>
						<input name="formComs" type="text" value="<?php echo $itemComs; ?>" autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Edit Item Version</p>
					</td>
					<td>
						<input name="formVer" type="text" value="<?php echo $itemVer; ?>" autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Edit Default Location</p>
					</td>
					<td>
						<input name="formLoc" type="text" value="<?php echo $itemLoc; ?>" autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Edit Current Location</p>
					</td>
					<td>
						<input name="formLocCur" type="text" value="<?php echo $itemLocCur; ?>" autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td><p>Edit Permissions</p></td>
					<td>
						<select name="formPerms" required>
							<option disabled>Select an Option</option>
		<?php
									if($itemPerms == 1) {
		?>
							<option value="1" selected>Visible</option>
		<?php
									} else {
		?>
							<option value="1">Visible</option>
		<?php
									};
									if($itemPerms == 0) {
		?>
							<option value="0" selected>Not Visible</option>
		<?php
									} else {
		?>
							<option value="0">Not Visible</option>
		<?php
									};
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</tr>
			</table>
		</form>

		<form class="clr mrg-top-x-lrg" method="post" action="./action.php?a=edit">
			<input name="formID" type="hidden" value="<?php echo $itemID; ?>" required />
			<input name="formType" type="hidden" value="7" required />
			<table class="fixed">
				<tr>
					<td colspan="2"><p>Edit <?php echo ucwords($itemName); ?>'s Parent</p></td>
				</tr>
				<tr>
					<td><p>Select Parent</p></td>
					<td>
						<select name="formParent" required>
							<option disabled>Select an Option</option>
		<?php
									$childID = $itemID;
									$getParent = $con->prepare("SELECT parentID FROM links WHERE childID=?");
									$getParent->bind_param("i", $childID);
									$getParent->execute();
									$getParent->store_result();
									$getParent->bind_result($parentID);
									while($getParent->fetch()) {
										$parentID = $parentID;
									};
									$getParent->close();

									$getParents = $con->prepare("SELECT itemID,itemName FROM items WHERE itemID!=? AND (itemType=1 OR itemType=2) ORDER BY itemType ASC");
									$getParents->bind_param("i", $childID);
									$getParents->execute();
									$getParents->store_result();
									if($getParents->num_rows > 0) {
										$getParents->bind_result($itemID,$itemName);
										while($getParents->fetch()) {
											if($itemID == $parentID) {
		?>
							<option value="<?php echo $itemID; ?>" selected><?php echo ucwords($itemName); ?></option>
		<?php
											} else {
		?>
							<option value="<?php echo $itemID; ?>"><?php echo ucwords($itemName); ?></option>
		<?php
											};
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
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</tr>			</table>
		</form>
		<?php
								} else {
		?>
		<p class="alert">a valid item type is required, redirecting...</p>
		<?php
									redirect("./admin.php");
								};
							};
							$getItem->close();
						} else {
		?>
		<p class="alert">a valid edit type is required, redirecting...</p>
		<?php
							redirect("./admin.php");
						};
					};
				};
			};
		?>
	</div>
</body>
</html>
