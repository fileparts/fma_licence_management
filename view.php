<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
</head>
<body>
	<?php include('nav.php'); ?>
	<div class="main wrp">
		<?php
			if(!isset($_GET['t'])) {
		?>
		<p class="alert">a view type is required, redirecting...</p>
		<?php
				redirect("./");
			} else {
				if(!isset($_GET['id'])) {
		?>
		<p class="alert">an id is required, redirecting...</p>
		<?php
					redirect("./");
				} else {
					$type = $_GET['t'];
					$id = $_GET['id'];

					if($type == 1) {
						if($_SESSION['userPerms1'] > 3) {
							$checkQuery = "SELECT itemID,itemType FROM items WHERE itemID=?";
							$childQuery = "SELECT itemType FROM items WHERE itemID=?";
						} else {
							$checkQuery = "SELECT itemID,itemType FROM items WHERE itemID=? AND itemPerms=1";
							$childQuery = "SELECT itemType FROM items WHERE itemID=? AND itemPerms=1";
						};

						$getItemType = $con->prepare($checkQuery);
						$getItemType->bind_param("i", $id);
						$getItemType->execute();
						$getItemType->store_result();
						if($getItemType->num_rows > 0) {
							$getItemType->bind_result($itemID,$itemType);
							while($getItemType->fetch()) {
								$itemID = $itemID;
								$itemType = $itemType;
							};

							if($itemType == 1 || $itemType == 2) {
								$childSub = array();
								$childItem = array();

								$getMain = $con->prepare("SELECT itemID,itemName FROM items WHERE itemID=?");
								$getMain->bind_param("i", $itemID);
								$getMain->execute();
								$getMain->store_result();
								$getMain->bind_result($mainID,$mainName);
								while($getMain->fetch()) {
									$mainID = $mainID;
									$mainName = $mainName;
								};
								$getMain->close();

		?>
		<table class="full outline mrg-btm-lrg">
			<tr class="head">
				<th><p><?php echo ucwords($mainName); ?></p></th>
				<th class="options">
					<?php
					if($itemType == 2) {
						$getParentID = $con->prepare("SELECT parentID FROM links WHERE childID=?");
						$getParentID->bind_param("i", $mainID);
						$getParentID->execute();
						$getParentID->store_result();
						$getParentID->bind_result($parentID);
						while($getParentID->fetch()) {
					?>
					<a href="./view.php?t=1&id=<?php echo $parentID; ?>"><i class="fa fa-fw fa-level-up"></i></a>
					<?php
						};
						$getParentID->close();
					} else {
					?>
					<a href="./browse.php"><i class="fa fa-fw fa-level-up"></i></a>
					<?php
					};
					?>
				</th>
			</tr>
		</table>
		<?php
								$getChildren = $con->prepare("SELECT childID FROM links WHERE parentID=?");
								$getChildren->bind_param("i", $mainID);
								$getChildren->execute();
								$getChildren->store_result();
								if($getChildren->num_rows > 0) {
									$getChildren->bind_result($childID);
									while($getChildren->fetch()) {
										$childType = $con->prepare($childQuery);
										$childType->bind_param("i", $childID);
										$childType->execute();
										$childType->bind_result($itemType);
										while($childType->fetch()) {
											if($itemType == 2) {
												$childSub[] = $childID;
											} else if($itemType == 4) {
												$childItem[] = $childID;
											};
										};
										$childType->close();
									};
								} else {
		?>
		<p class="alert">No Children Found</p>
		<?php
									};
									$getChildren->close();

								if(count($childSub) > 0) {
									$querySubs = "SELECT itemID,itemName FROM items WHERE itemID IN (";
									foreach($childSub as $sub) {
										$querySubs .= $sub. ',';
									};
									$querySubs = substr($querySubs, 0, -1);
									$querySubs .= ") AND itemType=2";

									$getSubs = $con->prepare($querySubs);
									$getSubs->execute();
									$getSubs->store_result();
									if($getSubs->num_rows > 0) {
		?>
		<table class="full outline mrg-btm-lrg">
			<tr class="head">
				<th><p>Sub Category Name</p></th>
				<th class="options"></th>
			</tr>
		<?php
										$getSubs->bind_result($itemID,$itemName);
										while($getSubs->fetch()) {
		?>
			<tr>
				<td><a href="./view.php?t=1&id=<?php echo $itemID; ?>"><?php echo ucwords($itemName); ?></a></td>
				<td class="options">
					<a href="./view.php?t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-eye"></i></a>
					<?php
						$childCount = $con->prepare("SELECT * FROM links WHERE parentID=?");
						$childCount->bind_param("i", $itemID);
						$childCount->execute();
						$childCount->store_result();
						if($childCount->num_rows == 0) {
							if($_SESSION['userPerms1'] > 3) {
					?>
					<a class="confirm" href="./action.php?a=delete&t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-times"></i></a>
					<?php
							};
						};
						$childCount->close();
					?>
				</td>
			</tr>
		<?php
										};
		?>
		</table>
		<?php
									};
									$getSubs->close();
								};
								if(count($childItem) > 0) {
									$queryItems = "SELECT itemID,itemName,itemDesc FROM items WHERE itemID IN (";
									foreach($childItem as $item) {
										$queryItems .= $item. ',';
									};
									$queryItems = substr($queryItems, 0, -1);
									$queryItems .= ") AND itemType=4";

									$getItems = $con->prepare($queryItems);
									$getItems->execute();
									$getItems->store_result();
									if($getItems->num_rows > 0) {
		?>
		<table class="full fixed outline">
			<tr class="head">
				<th><p>Item Name</p></th>
				<th><p>Type</p></th>
				<th><p>Description</p></th>
				<th><p>Default Location</p></th>
				<th><p>Current Location</p></th>
				<th><p>Availability</p></th>
				<th class="options"></th>
			</tr>
		<?php
										$getItems->bind_result($itemID,$itemName,$itemDesc);
										while($getItems->fetch()) {
											$getItemDetails = $con->prepare("SELECT itemLocCur,itemLoc,itemType FROM itemDetails WHERE itemID=?");
											$getItemDetails->bind_param("i", $itemID);
											$getItemDetails->execute();
											$getItemDetails->store_result();
											$getItemDetails->bind_result($itemLocCur,$itemLoc,$itemTypeID);
											while($getItemDetails->fetch()) {
												$itemVer = $itemVer;
												$itemLocCur = $itemLocCur;
												$itemLoc = $itemLoc;

												$getType = $con->prepare("SELECT itemName FROM items WHERE itemID=?");
												$getType->bind_param("i", $itemTypeID);
												$getType->execute();
												$getType->store_result();
												$getType->bind_result($itemTypeName);
												while($getType->fetch()) {
													$itemTypeName = $itemTypeName;
												};
												$getType->close();
											};
											$getItemDetails->close();
											$getItemStatus = $con->prepare("SELECT statusOut,statusIn FROM itemstatus WHERE itemID=?");
											$getItemStatus->bind_param("i", $itemID);
											$getItemStatus->execute();
											$getItemStatus->bind_result($statusOut,$statusIn);
											while($getItemStatus->fetch()) {
												$statusOut = $statusOut;
												$statusIn = $statusIn;
											};
											$getItemStatus->close();
		?>
			<tr>
				<td><a href="./view.php?t=1&id=<?php echo $itemID; ?>"><?php echo ucwords($itemName); ?></a></td>
				<td>
					<p><?php echo ucwords($itemTypeName); ?></p>
				</td>
				<td>
					<?php
						if($itemDesc == "" || $itemDesc == NULL || $itemDesc == "Undefined") {
					?>
					<p class="danger">Undefined</p>
					<?php
						} else {
					?>
					<p><?php echo $itemDesc; ?></p>
					<?php
						};
					?>
				</td>
				<td>
					<?php
						if($itemLoc == "" || $itemLoc == NULL) {
					?>
					<p class="danger">Undefined</p>
					<?php
						} else {
					?>
					<p><?php echo $itemLoc; ?></p>
					<?php
						};
					?>
				</td>
				<td>
					<?php
						if($itemLocCur == "" || $itemLocCur == NULL) {
					?>
					<p class="danger">Undefined</p>
					<?php
						} else {
					?>
					<p><?php echo $itemLocCur; ?></p>
					<?php
						};
					?>
				</td>
				<td>
					<?php
						if($statusOut == NULL && $statusIn != NULL) {
					?>
					<p class="success">Available</p>
					<?php
						} else if($statusOut == NULL && $statusIn != NULL) {
					?>
					<p class="info">Not Available</p>
					<?php
						} else {
					?>
					<p class="danger">Undefined</p>
					<?php
						};
					?>
				</td>
				<td class="options">
					<?php
						if($_SESSION['userPerms1'] > 3) {
					?>
					<a href="./edit.php?t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-wrench"></i></a>
					<a class="confirm" href="./action.php?a=delete&t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-close"></i></a>
					<?php
						};
						if($_SESSION['userPerms1'] < 4 && isset($_SESSION['userPerms1'])) {
					?>
					<a href="./request.php?id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-bullhorn"></i></a>
					<?php
						};
					?>
					<a href="./view.php?t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-eye"></i></a>
				</td>
			</tr>
		<?php
									};
		?>
		</table>
		<?php
									};
									$getItems->close();
								};
							} else if($itemType == 4) {

								//Labels
								$dayLabels 			= array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
								$dayMiniLabels		= array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
								$monthLables 		= array("January","February","March","April","May","June","July","August","September","October","November","December");

								$forceMonth = $_GET['m'];
								$forceYear = $_GET['y'];

								$currentDate			= date("Y-m-d");
								$explodeDate		= explode("-", $currentDate);

								//Currents
								if(isset($forceMonth)) {
									if(strlen($forceMonth) == 1) {
										$forceMonth 	= sprintf("%02d", $forceMonth);
									};
									$currentMonth	= $forceMonth;
								} else {
									$currentMonth	= date("m");
								};

								if(isset($forceYear)) {
									if(strlen($forceYear) == 2) {
										$dt 				= DateTime::createFromFormat('y', $forceYear);
										$forceYear 	= $dt->format('Y');
									};
									$currentYear		= $forceYear;
								} else {
									$currentYear		= date("Y");
								};

								//variables
								$monthStart 			= date($currentYear. '-' .$currentMonth. '-01');
								$monthEnd   			= date($currentYear. '-' .$currentMonth. '-t');

								$prevMonth 			= sprintf("%02d", $currentMonth - 1);
								$nextMonth 			= sprintf("%02d", $currentMonth + 1);
								$prevYear 			= sprintf("%02d", $currentYear - 1);
								$nextYear 			= sprintf("%02d", $currentYear + 1);

								$daysInMonth 		= cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
								$firstDayofMonth	= date("D", strtotime("01-$currentMonth-$currentYear"));
								$firstDayofMonth	= array_search($firstDayofMonth, $dayMiniLabels);
								$firstDayofMonth	= $firstDayofMonth;

								if($firstDayofMonth == 0) {
									$firstDayofMonth = 7;
								};

								$bookings 			= array();
								$s 						= new DateTime($monthStart);
								$e 						= new DateTime("$monthEnd + 1 days");
								$oneday 				= new DateInterval('P1D');
								$dp 						= new DatePeriod($s, $oneday, $e);

								foreach ($dp as $d) {
									$bookings[$d->format('Y-m-d')] = '';
								};

								//counters
								$dayCount 	= 0;
								$startMonth 	= 0;
								$calDate 		= 0;

								$getItem = $con->prepare("SELECT itemID,itemName,itemDesc,itemComs FROM items WHERE itemID=?");
								$getItem->bind_param("i", $itemID);
								$getItem->execute();
								$getItem->store_result();
								$getItem->bind_result($itemID,$itemName,$itemDesc,$itemComs);
								while($getItem->fetch()) {
									$getItemDetails = $con->prepare("SELECT itemLocCur,itemLoc,itemType FROM itemDetails WHERE itemID=?");
									$getItemDetails->bind_param("i", $itemID);
									$getItemDetails->execute();
									$getItemDetails->store_result();
									$getItemDetails->bind_result($itemLocCur,$itemLoc,$itemTypeID);
									while($getItemDetails->fetch()) {
										$itemVer = $itemVer;
										$itemLocCur = $itemLocCur;
										$itemLoc = $itemLoc;

										$getType = $con->prepare("SELECT itemName FROM items WHERE itemID=?");
										$getType->bind_param("i", $itemTypeID);
										$getType->execute();
										$getType->store_result();
										$getType->bind_result($itemTypeName);
										while($getType->fetch()) {
											$itemTypeName = $itemTypeName;
										};
										$getType->close();
									};
									$getItemDetails->close();

									$getItemStatus = $con->prepare("SELECT statusOut,statusIn,statusAssigned FROM itemstatus WHERE itemID=?");
									$getItemStatus->bind_param("i", $itemID);
									$getItemStatus->execute();
									$getItemStatus->bind_result($statusOut,$statusIn,$statusAssigned);
									while($getItemStatus->fetch()) {
										$statusOut = $statusOut;
										$statusIn = $statusIn;
										$statusAssigned = $statusAssigned;
									};
									$getItemStatus->close();
		?>
		<div class="clr">
			<h1 class="mrg-btm-x-lrg">
				<?php
					echo ucwords($itemName);
					if($_SESSION['userPerms1'] > 3) {
				?>
				<a href="./edit.php?t=1&id=<?php echo $itemID; ?>"><i style="font-size:24px;color:#000;" class="fa fa-fw fa-wrench"></i></a>
				<?php
					};
				if($statusAssigned != NULL) {
					if($statusAssigned == $_SESSION['userID1'] || $_SESSION['userPerms1'] > 3) {
				?>
					<a href="./action.php?a=returned&id=<?php echo $itemID; ?>"><i style="font-size:24px;color:#000;" class="fa fa-fw fa-lock"></i></a>
				<?php
					} else if($statusAssigned != $_SESSION['userID1']) {
				?>
					<p style="display:inline-block;" title="Item is Booked Out"><i style="font-size:24px;" class="fa fa-fw fa-lock"></i></p>
				<?php
					};
				};
				?>
			</h1>
		</div>
		<div class="clr mrg-btm-med">
			<?php
				if($itemDesc == "" || $itemDesc == NULL) {
			?>
			<p class="danger">Description: Undefined</p>
			<?php
				} else {
			?>
			<p>Description: <?php echo $itemDesc; ?></p>
			<?php
				};
			?>
		</div>
		<div class="clr mrg-btm-med">
			<table class="full fixed outline">
				<tr class="head">
					<td colspan="6"><p>Item Details</p></td>
				</tr>
				<tr>
					<td><p>Type</p></td>
					<td>
						<?php
							if($itemTypeName == "" || $itemTypeName == NULL) {
						?>
						<p class="danger">Undefined</p>
						<?php
							} else {
						?>
						<p><?php echo ucwords($itemTypeName); ?></p>
						<?php
							};
						?>
					</td>

					<td><p>Default Location</p></td>
					<td>
						<?php
							if($itemLoc == "" || $itemLoc == NULL) {
						?>
						<p class="danger">Undefined</p>
						<?php
							} else {
						?>
						<p><?php echo $itemLoc; ?></p>
						<?php
							};
						?>
					</td>

					<td><p>Status</p></td>
					<td>
						<?php
							if($statusOut == NULL && $statusIn != NULL) {
						?>
						<p class="success">Available</p>
						<?php
							} else if($statusIn == NULL && $statusOut != NULL) {
						?>
						<p class="info">Not Available</p>
						<?php
							} else {
						?>
						<p class="danger">Undefined</p>
						<?php
							};
						?>
					</td>
				</tr>
				<tr>
					<td><p>Version</p></td>
					<td>
						<?php
							if($itemVer == "" || $itemVer == NULL) {
						?>
						<p class="danger">Undefined</p>
						<?php
							} else {
						?>
						<p><?php echo ucwords($itemVer); ?></p>
						<?php
							};
						?>
					</td>

					<td><p>Current Location</p></td>
					<td>
						<?php
							if($itemLocCur == "" || $itemLocCur == NULL) {
						?>
						<p class="danger">Undefined</p>
						<?php
							} else {
						?>
						<p><?php echo $itemLocCur; ?></p>
						<?php
							};
						?>
					</td>

					<td><p>Booked By</p></td>
					<td>
						<?php
							if($statusAssigned == NULL) {
						?>
							<p class="info">N/A</p>
						<?php
							} else {
								$getBooker = $con->prepare("SELECT userFirst,userLast,userEmail FROM users WHERE userID=?");
								$getBooker->bind_param("i", $statusAssigned);
								$getBooker->execute();
								$getBooker->bind_result($userFirst,$userLast,$userEmail);
								while($getBooker->fetch()) {
						?>
							<a href="mailto:<?php echo $userEmail; ?>"><?php echo $userFirst. ' ' .$userLast; ?></a>
						<?php
								};
								$getBooker->close();
							};
						?>
					</td>
				</tr>
				<tr>
					<td><p>Serial Number</p></td>
					<td>
						<?php
							if($itemSerial == "" || $itemSerial == NULL) {
						?>
						<p class="danger">Undefined</p>
						<?php
							} else {
						?>
						<p><?php echo ucwords($itemSerial); ?></p>
						<?php
							};
						?>
					</td>

					<td><p>Asset Number</p></td>
					<td>
						<?php
							if($itemAssetNum == "" || $itemAssetNum == NULL) {
						?>
						<p class="danger">Undefined</p>
						<?php
							} else {
						?>
						<p><?php echo ucwords($itemAssetNum); ?></p>
						<?php
							};
						?>
					</td>

					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
		<div class="clr">
			<?php
				if($itemComs == "" || $itemComs == NULL) {
			?>
			<p class="danger">Comments: Undefined</p>
			<?php
				} else {
			?>
			<p>Comments: <?php echo $itemComs; ?></p>
			<?php
				};
			?>
		</div>
		<div class="clr outline mrg-top-lrg">
			<table class="fixed full date-days">
				<tr class="head">
				<?php
					foreach($dayLabels as $day) {
						echo '<td><p>' .$day. '</p></td>';
					};
				?>
				</tr>
			</table>
			<table id="calendar" class="fixed full date-calendar">
				<?php
					foreach($bookings as $key=>$date) {
						$dayCount++;
						$calDate++;
						$calDate = sprintf("%02d", $calDate);
						$calCurrent = $currentYear. '-' .$currentMonth. '-' .$calDate;

						if($dayCount == 1) {
							echo '<tr>';
						};

						if($firstDayofMonth != 7) {
							while($startMonth < $firstDayofMonth) {
								echo '<td class="padding"></td>';
								$startMonth++;
								$dayCount++;
								$temp_dayCount = sprintf("%02d", $dayCount);
								$dayCount = $temp_dayCount;
							};
						};

							echo '
								<td class="date">
									<p>' .$calDate. '</p>
							';

							if(isset($_SESSION['userID1']) && ($statusOut == NULL && $statusIn != NULL)) {
								echo '<a class="date-bookNow confirm" title="Request This Date" href="./request.php?id=' .$itemID. '&d=' .$calCurrent. '"></a>';
							} else {
								echo '<a class="date-bookNow" title="Item is Booked Out"></a>';
							};

							echo '
								</td>
							';

						if($dayCount == 7) {
							echo '</tr>';
							$dayCount = 0;
						};
					};
				?>
			</table>
		</div>
		<?php
			if($_SESSION['userPerms1'] > 3) {
		?>
		<div class="clr mrg-top-lrg">
			<table class="full fixed outline">
				<tr class="head">
					<th><p>User Name</p></th>
					<th><p>User Email</p></th>
					<th><p>Requested Date</p></th>
					<th class="options"></th>
				</tr>
		<?php
					$getRequests = $con->prepare("SELECT requestID,userID,itemID,requestDate,requestStatus FROM requests WHERE itemID=? ORDER BY requestID DESC");
					$getRequests->bind_param("i", $itemID);
					$getRequests->execute();
					$getRequests->store_result();
					if($getRequests->num_rows > 0) {
						$getRequests->bind_result($requestID,$userID,$itemID,$requestDate,$requestStatus);
						while($getRequests->fetch()) {
							$getUser = $con->prepare("SELECT userFirst,userLast,userEmail FROM users WHERE userID=?");
							$getUser->bind_param("i", $userID);
							$getUser->execute();
							$getUser->store_result();
							$getUser->bind_result($userFirst,$userLast,$userEmail);
							while($getUser->fetch()) {
								$getItemStatus = $con->prepare("SELECT statusAssigned FROM itemStatus WHERE itemID=?");
								$getItemStatus->bind_param("i", $itemID);
								$getItemStatus->execute();
								$getItemStatus->bind_result($statusAssigned);
								while($getItemStatus->fetch()) {
		?>
		<tr>
			<td><p><?php echo $userFirst. ' ' .$userLast; ?></p></td>
			<td><a href="mailto:<?php echo $userEmail; ?>"><?php echo $userEmail; ?></a></td>
			<td><p><?php echo $requestDate; ?></p></td>
			<td class="options">
		<?php
								if($requestStatus === 0 && $statusAssigned==NULL) {
		?>
				<a class="confirm" href="./action.php?a=approve&id=<?php echo $requestID; ?>"><i class="fa fa-fw fa-check"></i></a>
				<a class="confirm" href="./action.php?a=disapprove&id=<?php echo $requestID; ?>"><i class="fa fa-fw fa-close"></i></a>
		<?php
								} else if($requestStatus === 1) {
		?>
				<p class="success">Approved</p>
		<?php
								} else if($requestStatus === 2) {
		?>
				<p class="danger">Disapproved</p>
		<?php
								} else if($requestStatus === 3) {
		?>
				<p class="info">Returned</p>
		<?php
								} else if($statusAssigned != NULL) {
		?>
				<p class="info">Already Out</p>
		<?php
								} else {
		?>
				<p class="danger">Undefined</p>
		<?php
								};
		?>
			</td>
		<?php
								};
								$getItemStatus->close();
							};
							$getUser->close();
						};
					} else {
		?>
				<tr>
					<td colspan="4"><p class="alert">No Requests Found</p></td>
				</tr>
		<?php
					};
					$getRequests->close();
		?>
			</table>
		</div>
		<?php
			};
								};
								$getItem->close();
							};
						} else {
		?>
		<p class="alert">no item found, redirecting...</p>
		<?php
							redirect("./");
						};
						$getItemType->close();
					} else {
		?>
		<p class="alert">a valid view type is required, redirecting...</p>
		<?php
						redirect("./");
					};
				};
			};
		?>
	</div>
</body>
</html>
