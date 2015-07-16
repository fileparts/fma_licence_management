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
		<p class="alert">you must be logged in to view this page, redirecting...</p>
		<?php
				redirect("./login.php");
			} else {
				if(!isset($_GET['id'])) {
		?>
		<p class="alert">an id is required to request an item, redirecting...</p>
		<?php
					redirect("./browse.php");
				} else {
					if(!isset($_GET['d'])) {
		?>
		<p class="alert">a date is requred to request an item, redirecting...</p>
		<?php
						redirect("./browse.php");
					} else {
						$userID = $_SESSION['userID1'];
						$itemID = $_GET['id'];
						$date = $_GET['d'];
						$defaultStatus = 0;
						
						$okay = false;
						
						$checkItem = $con->prepare("SELECT itemName FROM items WHERE itemID=? AND itemType=4");
						$checkItem->bind_param("i", $itemID);
						$checkItem->execute();
						$checkItem->store_result();
						if($checkItem->num_rows > 0) {
							$checkItem->bind_result($itemName);
							while($checkItem->fetch()) {
							$createRequest = $con->prepare("INSERT INTO requests(userID,itemID,requestDate,requestStatus) VALUES(?,?,?,?)");
							$createRequest->bind_param("iisi", $userID,$itemID,$date,$defaultStatus);
							if($createRequest->execute()) {
		?>
		<p class="alert">Request Created for "<?php echo $itemName; ?>" for on the "<?php echo $date; ?>", redirecting...</p>
		<?php
								redirect("./view.php?t=1&id=$itemID");
							} else {
		?>
		<p class="alert">Execution Error: Request Creation</p>
		<?php
								redirect("./view.php?t=1&id=$itemID");
							};
							$createRequest->close();
							};
							$okay = true;
						} else {
		?>
		<p class="alert">This ID is not an Item, redirecting...</p>
		<?php
							redirect("./browse.php");
						};
						$checkItem->close();
						
						if($okay == true) {
						};
					};
				};
			};
		?>
	</div>
</body>
</html>