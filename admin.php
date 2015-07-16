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
		<p class="alert">You Must be Logged In to View This Page, Redirecting...</p>
		<?php
				redirect("./login.php");
			} else {
				$userPerms = $_SESSION['userPerms1'];

				if($userPerms < 4) {
		?>
		<p class="alert">You do not have permission to view this page, Redirecting...</p>
		<?php
					redirect("./");
				} else {
		?>
		<div class="clr mrg-btm-x-lrg">
      <h1>Admin Control Panel</h1>
    </div>
		<div class="admin-controls clr mrg-btm">
			<table>
				<tr>
					<td><p>Create:</p></td>
					<td>
						<a href="./create.php?t=0" class="btn btn-grp">User</a>
						<a href="./create.php?t=1" class="btn btn-grp">Main Category</a>
						<a href="./create.php?t=2" class="btn btn-grp">Sub Category</a>
						<a href="./create.php?t=3" class="btn btn-grp">Item Type</a>
						<a href="./create.php?t=4" class="btn btn-grp">Item</a>
					</td>
					<td><p>View:</p></td>
					<td>
						<a href="./admin.php?v=2" class="btn btn-grp">Categories</a>
						<a href="./admin.php?v=1" class="btn btn-grp">Item Types</a>
						<a href="./admin.php?v=3" class="btn btn-grp">Item Requests</a>
						<a href="./admin.php?v=0" class="btn btn-grp">Users</a>
						<a href="./admin.php" class="btn btn-grp">Default</a>
					</td>
				</tr>
			</table>
		</div>
		<?php
					if($_GET['v'] == 2 || !isset($_GET['v'])) {
		?>
		<table class="full fixed outline <?php if(!isset($_GET['v'])) {echo "mrg-top-med";}; ?>">
			<tr class="head">
				<th><p>Category Name</p></th>
				<th class="options"></th>
			</tr>
		<?php
						$getMains = $con->prepare("SELECT itemID,itemName FROM items WHERE itemType=1");
						$getMains->execute();
						$getMains->store_result();
						if($getMains->num_rows > 0) {
							$getMains->bind_result($itemID,$itemName);
							while($getMains->fetch()) {
		?>
			<tr>
				<td><a href="./view.php?t=1&id=<?php echo $itemID; ?>"><?php echo ucwords($itemName); ?></a></td>
				<td class="options">
					<a href="./edit.php?t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-wrench"></i></a>
		<?php
								$countChildren = $con->prepare("SELECT linkID FROM links WHERE parentID=?");
								$countChildren->bind_param("i", $itemID);
								$countChildren->execute();
								$countChildren->store_result();
		?>
					<a href="./view.php?t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-eye"></i></a>
		<?php
								if($countChildren->num_rows == 0) {
		?>
					<a class="confirm" href="./action.php?a=delete&t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-close"></i></a>
		<?php
								};
								$countChildren->close();
		?>
				</td>
			</tr>
		<?php
							};
						} else {
		?>
		<tr><td colspan="2"><p class="alert">No Categories Found</p></td></tr>
		<?php
						};
						$getMains->close();
		?>
		</table>
		<?php
					};
					if($_GET['v'] == 1 || !isset($_GET['v'])) {
		?>
		<table class="full fixed outline <?php if(!isset($_GET['v'])) {echo "mrg-top-med";}; ?>">
		<tr class="head">
			<th>
				<p>Item Type Name</p>
			</th>
			<th class="options"></th>
		</tr>
		<?php
						$getTypes = $con->prepare("SELECT itemID,itemName FROM items WHERE itemType=3");
						$getTypes->execute();
						$getTypes->store_result();
						if($getTypes->num_rows > 0) {
							$getTypes->bind_result($itemID,$itemName);
							while($getTypes->fetch()) {
		?>
			<tr>
				<td><p><?php echo ucwords($itemName); ?></p></td>
				<td class="options">
					<a href="./edit.php?t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-wrench"></i></a>
		<?php
								$getUsage = $con->prepare("SELECT itemType FROM itemdetails WHERE itemType=?");
								$getUsage->bind_param("i", $itemID);
								$getUsage->execute();
								$getUsage->store_result();
								if($getUsage->num_rows == 0) {
		?>
					<a class="confirm" href="./action.php?a=delete&t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-close"></i></a>
		<?php
								};
								$getUsage->close();
		?>
				</td>
			</tr>
		<?php
							};
						} else {
		?>
		<tr><td colspan="2"><p class="alert">No Item Types Found</p></td></tr>
		<?php
						};
						$getTypes->close();
		?>
		</table>
		<?php
					};
					if($_GET['v'] == 3 || !isset($_GET['v'])) {
		?>
		<table class="full fixed outline <?php if(!isset($_GET['v'])) {echo "mrg-top-med";}; ?>">
			<tr class="head">
				<th><p>User Name</p></th>
				<th><p>User Email</p></th>
				<th><p>Item Name</p></th>
				<th><p>Requested Date</p></th>
				<th class="options"></th>
			</tr>
		<?php
					$getRequests = $con->prepare("SELECT requestID,userID,itemID,requestDate,requestStatus FROM requests WHERE requestStatus=0 ORDER BY requestID DESC");
					$getRequests->execute();
					$getRequests->store_result();
					if($getRequests->num_rows > 0) {
						$getRequests->bind_result($requestID,$userID,$itemID,$requestDate,$requestStatus);
						while($getRequests->fetch()) {
							$getItem = $con->prepare("SELECT itemName FROM items WHERE itemID=?");
							$getItem->bind_param("i", $itemID);
							$getItem->execute();
							$getItem->store_result();
							$getItem->bind_result($itemName);
							while($getItem->fetch()) {
								$itemName = $itemName;
							};
							$getItem->close();

							$getUser = $users->prepare("SELECT userFirst,userLast,userEmail FROM users WHERE userID=?");
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
			<td><a href="./view.php?t=1&id=<?php echo $itemID; ?>"><?php echo ucwords($itemName); ?></a></td>
			<td class="options">
		<?php
								if($requestStatus==0 && $statusAssigned==NULL) {
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
					<td colspan="5"><p class="alert">No Requests Found</p></td>
				</tr>
		<?php
					};
					$getRequests->close();
		?>
			</table>
		<?php
					};
					if($_GET['v'] == 0 || !isset($_GET['v'])) {
		?>
		<table class="full fixed outline <?php if(!isset($_GET['v'])) {echo "mrg-top-med";}; ?>">
			<tr class="head">
				<th><p>Username</p></th>
				<th><p>User Name</p></th>
				<th><p>User Email</p></th>
				<th class="options"></th>
			</tr>
		<?php
						$getUsers = $users->prepare("SELECT userID,userName,userFirst,userLast,userEmail,userPerms FROM users");
						$getUsers->execute();
						$getUsers->store_result();
						if($getUsers->num_rows > 0) {
							$getUsers->bind_result($userID,$userName,$userFirst,$userLast,$userEmail,$userPerms);
							while($getUsers->fetch()) {
		?>
			<tr>
				<td><a href="./view.php?t=0&id=<?php echo $userID; ?>"><?php echo $userName; ?></a></td>
				<td><p><?php echo $userFirst; ?> <?php echo $userLast; ?></p></td>
				<td><p><?php echo $userEmail; ?></p></td>
				<td class="options">
					<?php
						if($userPerms < 5) {
					?>
					<a href="./edit.php?t=0&id=<?php echo $userID; ?>"><i class="fa fa-fw fa-wrench"></i></a>
					<a class="confirm" href="./action.php?a=delete&t=0&id=<?php echo $userID; ?>"><i class="fa fa-fw fa-close"></i></a>
					<?php
						};
					?>
				</td>
			</tr>
		<?php
							};
						} else {
		?>
		<tr><td colspan="2"><p class="alert">No Users Found</p></td></tr>
		<?php
						};
						$getUsers->close();
		?>
		</table>
		<?php
					};
				};
			};
		?>
	</div>
</body>
</html>
