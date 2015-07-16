<?php
	$currentPage = basename($_SERVER['PHP_SELF']);
	session_start();
	
	if($currentPage == "logout.php") {
		session_destroy();
	};
	
	$con = new mysqli("localhost", "root", "Password1", "licence_management");
	if ($con->connect_errno) {
		printf("Connect failed: %s\n", $con->connect_error);
		exit();
	};
	
	$users = new mysqli("localhost", "root", "Password1", "web_users");
	if ($users->connect_errno) {
		printf("Connect failed: %s\n", $users->connect_error);
		exit();
	};
	
	$cryptSalt = '$2y$06$PizWslhw9Z9oM9QSPt9zY.g9faOSoUdNLO7RemQrWTMY.NOpr3oTG';
?>