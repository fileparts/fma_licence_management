<?php
	include('config.php');
	include('auto_head.php');

	$needle 	= $_POST['input'];
	$found		= false;

	if(strlen($needle) > 0) {
		if($getNameResults = $con->prepare("SELECT itemID,itemName,itemDesc,itemComs FROM items WHERE itemName LIKE '%$needle%' AND itemPerms=1 AND itemType=4 ORDER BY itemName ASC")) {
			$getNameResults->execute();
			$getNameResults->store_result();
			if($getNameResults->num_rows > 0) {
				$found = true;

				$getNameResults->bind_result($itemID,$itemName,$itemDesc,$itemComs);
				while($getNameResults->fetch()) {
					$getItemDetails = $con->prepare("SELECT itemVer,itemSerial,itemAssetNum,itemLocCur,itemLoc,itemType FROM itemdetails WHERE itemID=?");
					$getItemDetails->bind_param("i", $itemID);
					$getItemDetails->execute();
					$getItemDetails->store_result();
					$getItemDetails->bind_result($itemVer,$itemSerial,$itemAssetNum,$itemLocCur,$itemLoc,$itemTypeID);
					while($getItemDetails->fetch()) {
						$getItemType = $con->prepare("SELECT itemName FROM items WHERE itemID=?");
						$getItemType->bind_param("i", $itemTypeID);
						$getItemType->execute();
						$getItemType->store_result();
						$getItemType->bind_result($itemTypeName);
						while($getItemType->fetch()) {
							$getItemStatus = $con->prepare("SELECT statusOut,statusIn FROM itemstatus WHERE itemID=?");
							$getItemStatus->bind_param("i", $itemID);
							$getItemStatus->execute();
							$getItemStatus->store_result();
							$getItemStatus->bind_result($statusOut,$statusIn);
							while($getItemStatus->fetch()) {
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
							$getItemStatus->close();
						};
						$getItemType->close();
					};
					$getItemDetails->close();
				};
			} else {
				$found = false;
			};
		};
		$getNameResults->close();

		if($found == false) {
				if($getTypeResults = $con->prepare("SELECT itemID,itemName FROM items WHERE itemName LIKE '%$needle%' AND itemPerms=1 AND itemType=3 ORDER BY itemName ASC")) {
					$getTypeResults->execute();
					$getTypeResults->store_result();
					if($getTypeResults->num_rows > 0) {
						$found = true;

						$getTypeResults->bind_result($itemTypeID,$itemTypeName);
						while($getTypeResults->fetch()) {
							$getItemID = $con->prepare("SELECT itemID,itemVer,itemSerial,itemAssetNum,itemLocCur,itemLoc FROM itemdetails WHERE itemType=?");
							$getItemID->bind_param("i", $itemTypeID);
							$getItemID->execute();
							$getItemID->store_result();
							$getItemID->bind_result($itemID,$itemVer,$itemSerial,$itemAssetNum,$itemLocCur,$itemLoc);
							while($getItemID->fetch()) {
								$getItem = $con->prepare("SELECT itemName,itemDesc,itemComs FROM items WHERE itemID=?");
								$getItem->bind_param("i", $itemID);
								$getItem->execute();
								$getItem->store_result();
								$getItem->bind_result($itemName,$itemDesc,$itemComs);
								while($getID->fetch()) {
?>
<tr>
	<td><p><?php echo ucwords($itemName); ?></p></td>
	<td><p><?php echo ucwords($itemTypeName); ?></p></td>
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
							$getItem->close();
						};
						$getItemID->close();
					};
				} else {
					$found = false;
				};
			};
			$getTypeResults->close();
		};

		if($found == false) {
			echo '<tr><td colspan="7"><p class="alert">No Items Found</p></td></tr>';
		};
	} else {
		echo '<tr><td colspan="7"><p class="alert">Search Something...</p></td></tr>';
	};

	error_reporting(E_all);
?>
