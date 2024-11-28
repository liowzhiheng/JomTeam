	<?php

	$db_host = '127.0.0.1:3306';
	$db_user = 'u442919130_jomteam';
	$db_pass = 'Jom5201314.';
	$db_name = 'u442919130_jomteam';

	// login to MySQL Server from PHP
	$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	?>
