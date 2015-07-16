<script src="./scripts/jquery-1.11.3.min.js"></script>
<script src="./scripts/global.js"></script>
<script>
	$(document).ready(function() {
		$('.confirm').on('click', function () {
			return confirm('Are you sure?');
		});
	});
</script>
