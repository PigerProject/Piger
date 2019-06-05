<header>
	<link rel="stylesheet" type="text/css" href="css/core.css">
	<link rel="stylesheet" type="text/css" href="css/profile_not_found.css">
	<script src="https://cdn.jsdelivr.net/npm/vue"></script>

	<link href="https://fonts.googleapis.com/css?family=Catamaran:600,700" rel="stylesheet">
</header>

<?php
$is_limited = true;
include Utils::isLoggedIn() ? 'navbar.php' : 'navbar_guest.php';
?>

<div class="wrapper">
	<div class="content">
		<?php include 'img/piger.svg' ?>
		<p>Das Profil konnte nicht gefunden werden.</p>
		<button onclick="Utils.goBack()">Zurück</button>
	</div>
</div>