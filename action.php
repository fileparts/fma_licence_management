<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
</head>
<body>
	<?php include('nav.php'); ?>
	<div class="main wrp">
		<?php
			if(!isset($_GET['a'])) {
		?>
		<p class="alert">An Action is Required, Redirecting...</p>
		<?php
				redirect("./");
			} else {
				$action = $_GET['a'];
				$okay = false;

				if($action == "register") {
					$formName = strtolower($_POST['formName']);
					$formFirst = $_POST['formFirst'];
					$formLast = $_POST['formLast'];
					$formEmail = $_POST['formEmail'];
					$formPass = $_POST['formPass'];
					$formPass = better_crypt($formPass);

					$checkName = $users->prepare("SELECT userID FROM users WHERE userName=?");
					$checkName->bind_param("s", $formName);
					$checkName->execute();
					$checkName->store_result();
					if($checkName->num_rows > 0) {
		?>
		<p class="alert">That Username is Already Taken, Redirecting...</p>
		<?php
						redirect("./register.php");
					} else {
						$okay = true;
					};
					$checkName->close();

					if($okay == true) {
						$createUser = $users->prepare("INSERT INTO users(userName,userFirst,userLast,userEmail,userPass) VALUES(?,?,?,?,?)");
						$createUser->bind_param("sssss", $formName,$formFirst,$formLast,$formEmail,$formPass);
						if($createUser->execute()) {
		?>
		<p class="alert">User Registered, Redirecting...</p>
		<?php
							redirect("./login.php");
						} else {
		?>
		<p class="alert">Execution Error: User Registration, Redirecting...</p>
		<?php
							redirect("./register.php");
						};
						$createUser->close();
					};
				} else if($action == "login") {
					$formName = strtolower($_POST['formName']);
					$formPass = $_POST['formPass'];

					$checkName = $users->prepare("SELECT userID FROM users WHERE userName=?");
					$checkName->bind_param("s", $formName);
					$checkName->execute();
					$checkName->store_result();
					if($checkName->num_rows > 0) {
						$checkName->bind_result($userID);
						while($checkName->fetch()) {
							$userID = $userID;
						};
						$okay = true;
					} else {
		?>
		<p class="alert">Incorrect Username, Redirecting...</p>
		<?php
						redirect("./login.php");
					};
					$checkName->close();

					if($okay == true) {
						$checkPass = $users->prepare("SELECT userID,userPass,userPerms FROM users WHERE userID=?");
						$checkPass->bind_param("s", $userID);
						$checkPass->execute();
						$checkPass->store_result();
						$checkPass->bind_result($userID,$userPass,$userPerms);
						while($checkPass->fetch()) {
							if(hash_equals($userPass, crypt($formPass,$userPass))) {
								$_SESSION['userID1'] = $userID;
								$_SESSION['userPerms1'] = $userPerms;
		?>
		<p class="alert">Successfully Logged In, Redirecting...</p>
		<?php
								redirect("./");
							} else {
		?>
		<p class="alert">Incorrect Password, Redirecting...</p>
		<?php
								redirect("./login.php");
							};
						};
						$checkPass->close();
					};
				} else if($action == "create") {
					if($_SESSION['userPerms1'] < 4) {
		?>
		<p class="alert">you do not have permission to view this page, redirecting...</p>
		<?php
						redirect("./");
					} else {
						$formType = $_POST['formType'];

						if($formType == 0) {
							$formName = strtolower($_POST['formName']);
							$formFirst = $_POST['formFirst'];
							$formLast = $_POST['formLast'];
							$formEmail = $_POST['formEmail'];
							$formPass = $_POST['formPass'];
							$formPass = better_crypt($formPass);
							$formPerms = $_POST['formPerms'];

							$checkName = $users->prepare("SELECT userID FROM users WHERE userName=?");
							$checkName->bind_param("s", $formName);
							$checkName->execute();
							$checkName->store_result();
							if($checkName->num_rows > 0) {
		?>
		<p class="alert">That Username is Already Taken, Redirecting...</p>
		<?php
								redirect("./create.php?t=0");
							} else {
								$okay = true;
							};
							$checkName->close();

							if($okay == true) {
								$createUser = $users->prepare("INSERT INTO users(userName,userFirst,userLast,userEmail,userPass,userPerms) VALUES(?,?,?,?,?,?)");
								$createUser->bind_param("sssssi", $formName,$formFirst,$formLast,$formEmail,$formPass,$formPerms);
								if($createUser->execute()) {
		?>
		<p class="alert">User Registered, Redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: User Registration, Redirecting...</p>
		<?php
									redirect("./create.php?t=0");
								};
								$createUser->close();
							};
						} else if($formType == 1) {
							$formName = strtolower($_POST['formName']);
							$formPerms = $_POST['formPerms'];

							$checkName = $con->prepare("SELECT itemID FROM items WHERE itemName=? AND itemType=1");
							$checkName->bind_param("s", $formName);
							$checkName->execute();
							$checkName->store_result();
							if($checkName->num_rows > 0) {
		?>
		<p class="alert">That Category Name is Already Taken, Redirecting...</p>
		<?php
								redirect("./create.php?t=1");
							} else {
								$okay = true;
							};
							$checkName->close();

							if($okay == true) {
								$createMain = $con->prepare("INSERT INTO items(itemName,itemType,itemPerms) VALUES(?,?,?)");
								$createMain->bind_param("sii", $formName,$formType,$formPerms);
								if($createMain->execute()) {
		?>
		<p class="alert">Category Created, redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: Category Creation, redirecting...</p>
		<?php
									redirect("./create.php?t=1");
								};
							};
						} else if($formType == 2) {
							$formParent = $_POST['formParent'];
							$formName = strtolower($_POST['formName']);
							$formPerms = $_POST['formPerms'];

							$checkName = $con->prepare("SELECT itemID FROM items WHERE itemName=? AND (itemType=2 OR itemType=1)");
							$checkName->bind_param("s", $formName);
							$checkName->execute();
							$checkName->store_result();
							if($checkName->num_rows > 0) {
		?>
		<p class="alert">That Category Name is Already Taken, Redirecting...</p>
		<?php
								redirect("./create.php?t=2");
							} else {
								$okay = true;
							};
							$checkName->close();

							if($okay == true) {
								$createSub = $con->prepare("INSERT INTO items(itemName,itemPerms,itemType) VALUES(?,?,?)");
								$createSub->bind_param("sii", $formName,$formPerms,$formType);
								$createSub->execute();
								$createSub->store_result();
								$childID = $createSub->insert_id;
								$createSub->close();

								$createLink = $con->prepare("INSERT INTO links(childID,parentID) VALUES(?,?)");
								$createLink->bind_param("ii", $childID,$formParent);
								if($createLink->execute()) {
		?>
		<p class="alert">Sub Category Created, redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
									$deleteItem = $con->prepare("DELETE FROM items WHERE itemID=?");
									$deleteItem->bind_param("i", $childID);
									$deleteItem->execute();
									$deleteItem->close();
		?>
		<p class="alert">Execution Error: Sub Category Creation, redirecting...</p>
		<?php
									redirect("./create.php?t=2");
								};
							};
						} else if($formType == 3) {
							$formName = strtolower($_POST['formName']);
							$formPerms = $_POST['formPerms'];

							$checkName = $con->prepare("SELECT itemID FROM items WHERE itemName=? AND itemType=3");
							$checkName->bind_param("s", $formName);
							$checkName->execute();
							$checkName->store_result();
							if($checkName->num_rows > 0) {
		?>
		<p class="alert">That Item Type Name is Already Taken, Redirecting...</p>
		<?php
								redirect("./create.php?t=3");
							} else {
								$okay = true;
							};
							$checkName->close();

							if($okay == true) {
								$createType = $con->prepare("INSERT INTO items(itemName,itemType,itemPerms) VALUES(?,?,?)");
								$createType->bind_param("sii", $formName,$formType,$formPerms);
								if($createType->execute()) {
		?>
		<p class="alert">Item Type Created, redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: Item Type Creation, redirecting...</p>
		<?php
									redirect("./create.php?t=3");
								};
							};
						} else if($formType == 4) {
							$formParent = $_POST['formParent'];
							$formItemType = $_POST['formItemType'];
							$formName = strtolower($_POST['formName']);
							$formVer = $_POST['formVer'];
							if($formVer == "") {$formVer = NULL;}
							$formSerial = $_POST['formSerial'];
							if($formSerial == "") {$formSerial = NULL;}
							$formAssetNum = $_POST['formAssetNum'];
							if($formAssetNum == "") {$formAssetNum = NULL;}
							$formDesc = $_POST['formDesc'];
							if($formDesc == "") {$formDesc = NULL;}
							$formComs = $_POST['formComs'];
							if($formComs == "") {$formComs = NULL;}
							$formLoc = $_POST['formLoc'];
							$formPerms = $_POST['formPerms'];

							$formDate = date("Y-m-d");

							$checkName = $con->prepare("SELECT itemID FROM items WHERE itemName=? AND itemType=4");
							$checkName->bind_param("s", $formName);
							$checkName->execute();
							$checkName->store_result();
							if($checkName->num_rows > 0) {
		?>
		<p class="alert">that item name is already taken, redirecting...</p>
		<?php
								redirect("./create.php?t=4");
							} else {
								$okay = true;
							};
							$checkName->close();

							if($okay == true) {
								$createItem = $con->prepare("INSERT INTO items(itemName,itemDesc,itemComs,itemType,itemPerms) VALUES(?,?,?,?,?)");
								$createItem->bind_param("sssii", $formName,$formDesc,$formComs,$formType,$formPerms);
								if($createItem->execute()) {
									$createItem->store_result();
									$childID = $createItem->insert_id;

									$createDetails = $con->prepare("INSERT INTO itemdetails(itemID,itemVer,itemSerial,itemAssetNum,itemLoc,itemLocCur,itemType) VALUES(?,?,?,?,?,?,?)");
									$createDetails->bind_param("isssssi", $childID,$formVer,$formSerial,$formAssetNum,$formLoc,$formLoc,$formItemType);
									if($createDetails->execute()) {
										$createDetails->store_result();

										$createStatus = $con->prepare("INSERT INTO itemstatus(itemID,statusIn) VALUES(?,?)");
										$createStatus->bind_param("is", $childID,$formDate);
										if($createStatus->execute()) {
											$createStatus->store_result();

											$createLink = $con->prepare("INSERT INTO links(childID,parentID) VALUES(?,?)");
											$createLink->bind_param("ii", $childID,$formParent);
											if($createLink->execute()) {
		?>
		<p class="alert">item created, redirecting...</p>
		<?php
												redirect("./admin.php");
											} else {
												$deleteStatus = $con->prepare("DELETE FROM itemstatus WHERE itemID=?");
												$deleteStatus->bind_param("i", $childID);
												$deleteStatus->execute();
												$deleteStatus->close();

												$deleteDetails = $con->prepare("DELETE FROM itemdetails WHERE itemID=?");
												$deleteDetails->bind_param("i", $childID);
												$deleteDetails->execute();
												$deleteDetails->close();

												$deleteItem = $con->prepare("DELETE FROM items WHERE itemID=?");
												$deleteItem->bind_param("i", $childID);
												$deleteItem->execute();
												$deleteItem->close();

												echo 'false';
											};
											$createLink->close();
										} else {
											$deleteDetails = $con->prepare("DELETE FROM itemdetails WHERE itemID=?");
											$deleteDetails->bind_param("i", $childID);
											$deleteDetails->execute();
											$deleteDetails->close();

											$deleteItem = $con->prepare("DELETE FROM items WHERE itemID=?");
											$deleteItem->bind_param("i", $childID);
											$deleteItem->execute();
											$deleteItem->close();

											echo 'false';
										};
										$createStatus->close();
									} else {
										$deleteItem = $con->prepare("DELETE FROM items WHERE itemID=?");
										$deleteItem->bind_param("i", $childID);
										$deleteItem->execute();
										$deleteItem->close();

										echo 'false';
									};
									$createDetails->close();
								} else {
									echo 'false';
								};
								$createItem->close();
							};
						} else {
		?>
		<p class="alert">a valid creation type is required, redirecting...</p>
		<?php
							redirect("./admin.php");
						};
					};
				} else if($action == "edit") {
					if($_SESSION['userPerms1'] < 4) {
		?>
		<p class="alert">you do not have permission to view this page, redirecting...</p>
		<?php
						redirect("./");
					} else {
						$formType = $_POST['formType'];

						if($formType == 0) {
							$formID = $_POST['formID'];
							$formName = strtolower($_POST['formName']);
							$formFirst = $_POST['formFirst'];
							$formLast = $_POST['formLast'];
							$formEmail = $_POST['formEmail'];
							$formPerms = $_POST['formPerms'];

							$checkName = $users->prepare("SELECT userID FROM users WHERE userName=?");
							$checkName->bind_param("s", $formName);
							$checkName->execute();
							$checkName->store_result();
							if($checkName->num_rows > 0) {
								$checkName->bind_result($userID);
								while($checkName->fetch()) {
									if($userID != $formID) {
		?>
		<p class="alert">That Username is Already Taken, Redirecting...</p>
		<?php
										redirect("./edit.php?t=0&id=$formID");
									} else {
										$okay = true;
									};
								};
							} else {
								$okay = true;
							};
							$checkName->close();

							if($okay == true) {
								$editUser = $users->prepare("UPDATE users SET userName=?,userFirst=?,userLast=?,userEmail=?,userPerms=? WHERE userID=?");
								$editUser->bind_param("ssssii", $formName,$formFirst,$formLast,$formEmail,$formPerms,$formID);
								if($editUser->execute()) {
		?>
		<p class="alert">User Edited, Redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: User Edit, redirecting...</p>
		<?php
									redirect("./edit.php?t=0&id=$formID");
								};
								$editUser->close();
							};
						} else if($formType ==  1) {
							$formID = $_POST['formID'];
							$formPass = $_POST['formPass'];
							$formPass = better_crypt($formPass);

							$editPass = $users->prepare("UPDATE users SET userPass=? WHERE userID=?");
							$editPass->bind_param("si", $formPass,$formID);
							if($editPass->execute()) {
		?>
		<p class="alert">User Password Edited, Redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: User Password Edit, redirecting...</p>
		<?php
								redirect("./edit.php?t=0&id=$formID");
							};
							$editPass->close();
						} else if($formType == 2) {
							$formID = $_POST['formID'];
							$formName = strtolower($_POST['formName']);
							$formPerms = $_POST['formPerms'];

							$checkName = $con->prepare("SELECT itemID FROM items WHERE itemName=? AND itemType=1");
							$checkName->bind_param("s", $formName);
							$checkName->execute();
							$checkName->store_result();
							if($checkName->num_rows > 0) {
								$checkName->bind_result($itemID);
								while($checkName->fetch()) {
									if($itemID != $formID) {
		?>
		<p class="alert">That Category Name is Already Taken, Redirecting...</p>
		<?php
										redirect("./edit.php?t=1&id=$formID");
									} else {
										$okay = true;
									};
								};
							} else {
								$okay = true;
							};
							$checkName->close();

							if($okay == true) {
								$editMain = $con->prepare("UPDATE items SET itemName=?,itemPerms=? WHERE itemID=?");
								$editMain->bind_param("sii", $formName,$formPerms,$formID);
								if($editMain->execute()) {
		?>
		<p class="alert">Category Edited, Redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: Category Edit, redirecting...</p>
		<?php
									redirect("./edit.php?t=1&id=$formID");
								};
								$editMain->close();
							};
						} else if($formType == 3) {
							$formID = $_POST['formID'];
							$formName = strtolower($_POST['formName']);
							$formPerms = $_POST['formPerms'];

							$checkName = $con->prepare("SELECT itemID FROM items WHERE itemName=? AND itemType=2");
							$checkName->bind_param("s", $formName);
							$checkName->execute();
							$checkName->store_result();
							if($checkName->num_rows > 0) {
								$checkName->bind_result($itemID);
								while($checkName->fetch()) {
									if($itemID != $formID) {
		?>
		<p class="alert">That Sub Category Name is Already Taken, Redirecting...</p>
		<?php
										redirect("./edit.php?t=1&id=$formID");
									} else {
										$okay = true;
									};
								};
							} else {
								$okay = true;
							};
							$checkName->close();

							if($okay == true) {
								$editSub = $con->prepare("UPDATE items SET itemName=?,itemPerms=? WHERE itemID=?");
								$editSub->bind_param("sii", $formName,$formPerms,$formID);
								if($editSub->execute()) {
		?>
		<p class="alert">Sub Category Edited, Redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: Sub Category Edit, redirecting...</p>
		<?php
									redirect("./edit.php?t=1&id=$formID");
								};
								$editSub->close();
							};
						} else if($formType == 4) {
							$formID = $_POST['formID'];
							$formParent = $_POST['formParent'];

							$editLink = $con->prepare("UPDATE links SET parentID=? WHERE childID=?");
							$editLink->bind_param("ii", $formParent,$formID);
							if($editLink->execute()) {
		?>
		<p class="alert">Sub Category Edited, Redirecting...</p>
		<?php
								redirect("./admin.php");
							} else {
		?>
		<p class="alert">Execution Error: Sub Category Edit, redirecting...</p>
		<?php
								redirect("./edit.php?t=1&id=$formID");
							};
							$editLink->close();
						} else if($formType == 5) {
							$formID = $_POST['formID'];
							$formName = strtolower($_POST['formName']);
							$formPerms = $_POST['formPerms'];

							$checkName = $con->prepare("SELECT itemID FROM items WHERE itemName=? AND itemType=3");
							$checkName->bind_param("s", $formName);
							$checkName->execute();
							$checkName->store_result();
							if($checkName->num_rows > 0) {
								$checkName->bind_result($itemID);
								while($checkName->fetch()) {
									if($itemID != $formID) {
		?>
		<p class="alert">That Item Type Name is Already Taken, Redirecting...</p>
		<?php
										redirect("./edit.php?t=1&id=$formID");
									} else {
										$okay = true;
									};
								};
							} else {
								$okay = true;
							};
							$checkName->close();

							if($okay == true) {
								$editMain = $con->prepare("UPDATE items SET itemName=?,itemPerms=? WHERE itemID=?");
								$editMain->bind_param("sii", $formName,$formPerms,$formID);
								if($editMain->execute()) {
		?>
		<p class="alert">Item Type Edited, Redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: Item Type Edit, redirecting...</p>
		<?php
									redirect("./edit.php?t=1&id=$formID");
								};
								$editMain->close();
							};
						} else if($formType == 6) {
							$formID = $_POST['formID'];
							$formName = strtolower($_POST['formName']);
							$formItemType = $_POST['formItemType'];
							$formDesc = $_POST['formDesc'];
							$formComs = $_POST['formComs'];
							$formVer = $_POST['formVer'];
							$formLoc = $_POST['formLoc'];
							$formLocCur = $_POST['formLocCur'];
							$formPerms = $_POST['formPerms'];

							$editItem = $con->prepare("UPDATE items SET itemName=?,itemDesc=?,itemComs=?,itemPerms=? WHERE itemID=?");
							$editItem->bind_param("sssii", $formName,$formDesc,$formComs,$formPerms,$formID);
							if($editItem->execute()) {
								$okay = true;
							} else {
		?>
		<p class="alert">Execution Error: Item Edit, redirecting...</p>
		<?php
								redirect("./edit.php?t=1&id=$formID");
							};
							$editItem->close();

							if($okay == true) {
								$editDetails = $con->prepare("UPDATE itemdetails SET itemVer=?,itemSerial=?,itemAssetNum=?,itemLocCur=?,itemLoc=?,itemType=? WHERE itemID=?");
								$editDetails->bind_param("sssssii", $formVer,$formSerial,$formAssetNum,$formLocCur,$formLoc,$formItemType,$formID);
								if($editDetails->execute()) {
		?>
		<p class="alert">Item Edited, redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: Item Edit, redirecting...</p>
		<?php
									redirect("./edit.php?t=1&id=$formID");
								};
								$editDetails->close();
							};
						} else if($formType == 7)  {
							$formID = $_POST['formID'];
							$formParent = $_POST['formParent'];

							$editLink = $con->prepare("UPDATE links SET parentID=? WHERE childID=?");
							$editLink->bind_param("ii", $formParent,$formID);
							if($editLink->execute()) {
		?>
		<p class="alert">Item Edited, Redirecting...</p>
		<?php
								redirect("./admin.php");
							} else {
		?>
		<p class="alert">Execution Error: Item Edit, redirecting...</p>
		<?php
								redirect("./edit.php?t=1&id=$formID");
							};
							$editLink->close();
						} else {
		?>
		<p class="alert">a valid edit type is required, redirecting...</p>
		<?php
							redirect("./admin.php");
						};
					};
				} else if($action == "delete") {
					if($_SESSION['userPerms1'] < 4) {
		?>
		<p class="alert">you do not have permission to view this page, redirecting...</p>
		<?php
						redirect("./");
					} else {
						$type = $_GET['t'];
						$id = $_GET['id'];

						if($type == 0) {
							$checkUser = $users->prepare("SELECT userID FROM users WHERE userID=?");
							$checkUser->bind_param("i", $id);
							$checkUser->execute();
							$checkUser->store_result();
							if($checkUser->num_rows > 0) {
									$okay = true;
							} else {
		?>
		<p class="alert">a valid user id is required, redirecting...</p>
		<?php
								redirect("./admin.php");
							};
							$checkUser->close();

							if($okay == true) {
								$deleteUser = $users->prepare("DELETE FROM users WHERE userID=?");
								$deleteUser->bind_param("i", $id);
								if($deleteUser->execute()) {
		?>
		<p class="alert">User Deleted, redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: User Deletion, redirecting...</p>
		<?php
									redirect("./admin.php");
								};
								$deleteUser->close();
							};
						} else if($type == 1) {
							$checkItem = $con->prepare("SELECT itemID,itemType FROM items WHERE itemID=?");
							$checkItem->bind_param("i", $id);
							$checkItem->execute();
							$checkItem->store_result();
							if($checkItem->num_rows > 0) {
								$checkItem->bind_result($itemID,$itemType);
								while($checkItem->fetch()) {
									$itemID = $itemID;
									$itemType = $itemType;
								};
							} else {
		?>
		<p class="alert">a valid item id is required, redirecting...</p>
		<?php
								redirect("./admin.php");
							};
							$checkItem->close();

							if($itemType == 1) {
								$deleteMain = $con->prepare("DELETE FROM items WHERE itemID=?");
								$deleteMain->bind_param("i", $itemID);
								if($deleteMain->execute()) {
		?>
		<p class="alert">Category Deleted, redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: Category Deletion, redirecting...</p>
		<?php
									redirect("./admin.php");
								};
								$deleteMain->close();
							} else if($itemType == 2) {
								$deleteLink = $con->prepare("DELETE FROM links WHERE childID=?");
								$deleteLink->bind_param("i", $itemID);
								if($deleteLink->execute()) {
									$okay = true;
								} else {
		?>
		<p class="alert">Execution Error: Sub Category Deletion, redirecting...</p>
		<?php
									redirect("./admin.php");
								};
								$deleteLink->close();

								if($okay == true) {
									$deleteSub = $con->prepare("DELETE FROM items WHERE itemID=?");
									$deleteSub->bind_param("i", $itemID);
									if($deleteSub->execute()) {
		?>
		<p class="alert">Sub Category Deleted, redirecting...</p>
		<?php
										redirect("./admin.php");
									} else {
		?>
		<p class="alert">Execution Error: Sub Category Deletion. database requires attention, redirecting...</p>
		<?php
										redirect("./admin.php");
									};
									$deleteSub->close();
								};
							} else if($itemType == 3) {
								$deleteType = $con->prepare("DELETE FROM items WHERE itemID=?");
								$deleteType->bind_param("i", $itemID);
								if($deleteType->execute()) {
		?>
		<p class="alert">Item Type Deleted, redirecting...</p>
		<?php
									redirect("./admin.php");
								} else {
		?>
		<p class="alert">Execution Error: Item Type Deletion, redirecting...</p>
		<?php
									redirect("./admin.php");
								};
								$deleteType->close();
							} else if($itemType == 4) {
								$deleteRequests = $con->prepare("DELETE FROM requests WHERE itemID=?");
								$deleteRequests->bind_param("i", $itemID);
								if($deleteRequests->execute()) {
									$deleteRequests->store_result();
									$deleteLinks = $con->prepare("DELETE FROM links WHERE childID=?");
									$deleteLinks->bind_param("i", $itemID);
									if($deleteLinks->execute()) {
										$deleteLinks->store_result();
										$deleteDetails = $con->prepare("DELETE FROM itemdetails WHERE itemID=?");
										$deleteDetails->bind_param("i", $itemID);
										if($deleteDetails->execute()) {
											$deleteDetails->store_result();
											$deleteStatus = $con->prepare("DELETE FROM itemstatus WHERE itemID=?");
											$deleteStatus->bind_param("i", $itemID);
											if($deleteStatus->execute()) {
												$deleteStatus->store_result();
												$deleteItem = $con->prepare("DELETE FROM items WHERE itemID=?");
												$deleteItem->bind_param("i", $itemID);
												if($deleteItem->execute()) {
												?>
												<p class="alert">Item Deleted, redirecting...</p>
												<?php
													redirect("./admin.php");
												} else {
												?>
												<p class="alert">Execution Error: Item Deletion [E005], redirecting...</p>
												<?php
													redirect("./admin.php");
												};
												$deleteItem->close();
											} else {
											?>
											<p class="alert">Execution Error: Item Deletion [E004], redirecting...</p>
											<?php
												redirect("./admin.php");
											};
											$deleteStatus->close();
										} else {
										?>
										<p class="alert">Execution Error: Item Deletion [E003], redirecting...</p>
										<?php
											redirect("./admin.php");
										};
										$deleteDetails->close();
									} else {
									?>
									<p class="alert">Execution Error: Item Deletion [E002], redirecting...</p>
									<?php
										redirect("./admin.php");
									};
									$deleteLinks->close();
								} else {
								?>
								<p class="alert">Execution Error: Item Deletion [E001], redirecting...</p>
								<?php
									redirect("./admin.php");
								};
								$deleteRequests->close();
							};
						} else {
		?>
		<p class="alert">a valid deletion type is required, redirecting...</p>
		<?php
							redirect("./admin.php");
						};
					};
				} else if($action == "approve") {
					if($_SESSION['userPerms1'] < 4) {
		?>
		<p class="alert">you do not have permission to view this page, redirecting...</p>
		<?php
						redirect("./");
					} else {
						if(!isset($_GET['id'])) {
		?>
		<p class="alert">a request id is required, redirecting...</p>
		<?php
							redirect("./");
						} else {
							$requestID = $_GET['id'];

							$checkID = $con->prepare("SELECT userID,itemID,requestDate FROM requests WHERE requestID=?");
							$checkID->bind_param("i", $requestID);
							$checkID->execute();
							$checkID->store_result();
							if($checkID->num_rows == 0) {
		?>
		<p class="alert">incorrect request ID, redirecting...</p>
		<?php
								redirect("./");
							} else {
								$checkID->bind_result($userID,$itemID,$requestDate);
								while($checkID->fetch()) {
									$approve = $con->prepare("UPDATE itemstatus SET statusOut=?,statusIn=NULL,statusAssigned=? WHERE itemID=?");
									$approve->bind_param("sii", $requestDate,$userID,$itemID);
									if($approve->execute()) {
										$updateRequest = $con->prepare("UPDATE requests SET requestStatus=1 WHERE requestID=?");
										$updateRequest->bind_param("i", $requestID);
										if($updateRequest->execute()) {
		?>
		<p class="alert">Request Accepted, redirecting...</p>
		<?php
											redirect("./view.php?t=1&id=$itemID");
										} else {
		?>
		<p class="alert">Execution Error: Update Request Status</p>
		<?php
										redirect("./view.php?t=1&id=$itemID");
										};
										$updateRequest->close();
									} else {
		?>
		<p class="alert">Execution Error: Update Item Status</p>
		<?php
										redirect("./view.php?t=1&id=$itemID");
									};
								};
							};
							$checkID->close();
						};
					};
				} else if($action == "disapprove") {
					if($_SESSION['userPerms1'] < 4) {
		?>
		<p class="alert">you do not have permission to view this page, redirecting...</p>
		<?php
						redirect("./");
					} else {
						if(!isset($_GET['id'])) {
		?>
		<p class="alert">a request id is required, redirecting...</p>
		<?php
							redirect("./");
						} else {
							$requestID = $_GET['id'];

							$checkID = $con->prepare("SELECT userID,itemID,requestDate FROM requests WHERE requestID=?");
							$checkID->bind_param("i", $requestID);
							$checkID->execute();
							$checkID->store_result();
							if($checkID->num_rows == 0) {
		?>
		<p class="alert">incorrect request ID, redirecting...</p>
		<?php
								redirect("./");
							} else {
								$checkID->bind_result($userID,$itemID,$requestDate);
								while($checkID->fetch()) {
									$updateRequest = $con->prepare("UPDATE requests SET requestStatus=2 WHERE requestID=?");
									$updateRequest->bind_param("i", $requestID);
									if($updateRequest->execute()) {
		?>
		<p class="alert">Request Rejected, redirecting...</p>
		<?php
										redirect("./view.php?t=1&id=$itemID");
									} else {
		?>
		<p class="alert">Execution Error: Update Request Status</p>
		<?php
									redirect("./view.php?t=1&id=$itemID");
									};
									$updateRequest->close();
								};
							};
							$checkID->close();
						};
					};
				} else if($action == "returned") {
					if(!isset($_GET['id'])) {
		?>
		<p class="alert">an item id is required, redirecting...</p>
		<?php
						redirect("./browse.php");
					} else {
						$itemID = $_GET['id'];
						$date = date("Y-m-d");

						$checkItem = $con->prepare("SELECT * FROM items WHERE itemID=? AND itemType=4");
						$checkItem->bind_param("i", $itemID);
						$checkItem->execute();
						$checkItem->store_result();
						if($checkItem->num_rows > 0) {
							$okay = true;
						} else {
		?>
		<p class="alert">no item found, redirecting...</p>
		<?php
							redirect("./browse.php");
						};
						$checkItem->close();

						if($okay == true) {
							$getItemStatus = $con->prepare("SELECT statusAssigned FROM itemstatus WHERE itemID=?");
							$getItemStatus->bind_param("i", $itemID);
							$getItemStatus->execute();
							$getItemStatus->store_result();
							$getItemStatus->bind_result($statusAssigned);
							while($getItemStatus->fetch()) {
								if($_SESSION['userID1'] == $statusAssigned || $_SESSION['userPerms1'] > 3) {
									$updateStatus = $con->prepare("UPDATE itemstatus SET statusOut=NULL, statusIn=?, statusAssigned=NULL WHERE itemID=?");
									$updateStatus->bind_param("si", $date,$itemID);
									if($updateStatus->execute()) {
		?>
		<p class="alert">item status updated, redirecting...</p>
		<?php
										$updateRequest = $con->prepare("UPDATE requests SET requestStatus=3 WHERE requestStatus=1 AND itemID=?");
										$updateRequest->bind_param("i", $itemID);
										$updateRequest->execute();
										$updateRequest->close();
										redirect("./view.php?t=1&id=$itemID");
									} else {
		?>
		<p class="alert">Execution Error: Item Status Update, redirecting...</p>
		<?php
										redirect("./view.php?t=1&id=$itemID");
									};
									$updateStatus->close();
								} else {
		?>
		<p class="alert">you do not have permission to view this page, redirecting...</p>
		<?php
									redirect("./view.php?t=1&id=$itemID");
								};
							};
							$getItemStatus->close();
						};
					};
				} else {
		?>
		<p class="alert">A Valid Action is Required, Redirecting...</p>
		<?php
					redirect("./");
				};
			};
		?>
	</div>
</body>
</html>
