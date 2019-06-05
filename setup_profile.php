<?php
session_start();
?>
<!DOCTYPE html>
<html encoding="UTF-8">
<head>
	<title>Profil einrichten</title>

	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran:600,700" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="css/core.css">
	<link rel="stylesheet" type="text/css" href="css/profile_options.css">

	<script src="https://cdn.jsdelivr.net/npm/vue"></script>

	<link rel="prerender" href="piges.php">
</head>

<body>
	<div class="line"></div>
	<?php include 'profile_options.php' ?>
	<a id="cancelBtn" href="piges.php">
		<button class="secondary">Überspringen</button>
	</a>
</body>

<script>
	var cancelBtn = document.getElementById("cancelBtn");

	document.getElementById("buttons").insertAdjacentElement("afterbegin", cancelBtn);
	document.getElementById("title").textContent = "Profil einrichten";
</script>
</html>