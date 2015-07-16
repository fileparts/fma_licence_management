<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
</head>
<body>
	<?php include('nav.php'); ?>
	<div class="main wrp">
		<h1 class="mrg-btm-x-lrg">Browse</h1>
		<?php
			if($_SESSION['userPerms1'] > 3) {
				$mainQuery = "SELECT itemID,itemName FROM items WHERE itemType=1";
			} else {
				$mainQuery = "SELECT itemID,itemName FROM items WHERE itemType=1 AND itemPerms=1";
			};

			$getMains = $con->prepare($mainQuery);
			$getMains->execute();
			$getMains->store_result();
			if($getMains->num_rows > 0) {
		?>
		<table class="full outline">
			<tr class="head">
				<th><p>Category Name</p></th>
				<th class="options"></th>
			</tr>
		<?php
				$getMains->bind_result($itemID,$itemName);
				while($getMains->fetch()) {
		?>
			<tr>
				<td><a href="./view.php?t=1&id=<?php echo $itemID; ?>"><?php echo ucwords($itemName); ?></a></td>
				<td class="options">
					<a href="./view.php?t=1&id=<?php echo $itemID; ?>"><i class="fa fa-fw fa-eye"></i></a>
				</td>
			</tr>
		<?php
				};
			} else {
		?>
		<p class="alert">No Categories Found</p>
		<?php
			};
			$getMains->close();
		?>
	</div>
</body>
</html>
