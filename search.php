<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
	<script>
		$(document).ready(function() {
			var searchInput = "input[name=search]";

			$(searchInput).keyup(function() {
				searchInput = $(this).val();
				liveSearch();
			});

			function liveSearch() {
				$.ajax({
					method: "POST",
					url: "auto_search.php",
					data: { input: searchInput }
				})
				.done(function(html) {
					$('table.search').html('<tr class="head">'
						+'<th><p>Item Name</p></th>'
						+'<th><p>Type</p></th>'
						+'<th><p>Description</p></th>'
						+'<th><p>Default Location</p></th>'
						+'<th><p>Current Location</p></th>'
						+'<th><p>Availability</p></th>'
						+'<th class="options"></th>'
						+'</tr>'
						+html
					);
				});
			};
		});
	</script>
</head>
<body>
	<?php include('nav.php'); ?>
	<div class="main wrp">
		<h1 class="mrg-btm-x-lrg">Search</h1>
		<form class="mrg-btm-med" method="post">
			<input name="search" type="text" placeholder="Search..." autofocus autocomplete="off" required />
		</form>
		<table class="search full fixed outline">
			<tr class="head">
				<th><p>Item Name</p></th>
				<th><p>Type</p></th>
				<th><p>Description</p></th>
				<th><p>Default Location</p></th>
				<th><p>Current Location</p></th>
				<th><p>Availability</p></th>
				<th class="options"></th>
			</tr>
			<tr>
				<td colspan="7">
					<p class="alert">Search Something...</p>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>
