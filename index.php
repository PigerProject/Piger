<?php session_start(); ?>
<!DOCTYPE html>
<html encoding="UTF-8">
<head>
	<title>Piger</title>

	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran:600,700" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="css/core.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">

	<script src="https://cdn.jsdelivr.net/npm/vue"></script>

	<link rel="prerender" href="signup.php">
</head>

<body>
	<?php
		include 'server/Utils.php';

		$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');
		$total_users = Utils::getTotalUserCount($mysqli);
		$total_posts_replies = Utils::getTotalPostsCount($mysqli) + Utils::getTotalRepliesCount($mysqli);
		$total_likes = Utils::getTotalLikesCount($mysqli);
		// todo add likes

		$is_limited = true;

		include Utils::isLoggedIn() ? 'navbar.php' : 'navbar_guest.php';

	?>
	<div id="landpage">
		<div id="landpageTextWrapper">
			<div id="landpageText">
				<h1>Rede mit Leuten aus aller Welt.</h1>
				<span>Verbinde dich mit Leuten, die die gleichen Interessen und Ideen haben. Kinderleicht in nur ein paar Mausklicks.</span>
				<br>
				<?php if(!Utils::isLoggedIn()){ ?>
				<a class="signUpLink" href="signup.php">
					<button class="signUpBtn">Registrieren</button>
				</a>
				<?php } ?>

				<a href="piges.php">
					<button class="toPigerBtn <?php if(!Utils::isLoggedIn()) echo 'white' ?>">Zu Piger</button>
				</a>
			</div>
		</div>
		<div id="scene">
			<?php include 'img/cover_girl.svg' ?>
			<?php include 'img/cover_boy.svg' ?>
		</div>
	</div>

	<section class="imageAndText">
		<img src="img/world_map.png">
		<div>
			<span class="subTitle">Up-to-date</span>
			<h2>Weltvernetzt</h2>
			<span>Rede mit Menschen aus aller Welt über alles was du möchtest kurz und bündig in 100 Zeichen. Egal wo du bist und welche Uhrzeit es gerade ist.</span>
		</div>
	</section>

	<section class="imageAndText textLeft">
		<img class="shadow" src="img/piger_messages.png">
		<div>
			<span class="subTitle">Finde Leute</span>
			<h2>Alle Interessen</h2>
			<span>Auf Piger sind alle Interessen vertreten. Finde und verbinde dich mit Leuten, die die gleichen Interessen wie du vertreten. </span>
		</div>
	</section>

	<section id="statsSection">
		<div class="title">
			<span class="subTitle">Unsere Weite</span>
			<h2>Piger in Zahlen</h2>
		</div>
		<div id="stats">
			<div class="box">
				<div class="line"></div>
				<div class="content">
					<h2 class="counter" data-value="<?php echo $total_users ?>">0</h2>
					<span>Nutzer registriert</span>
				</div>
			</div>
	
			<div class="box">
				<div class="line"></div>
				<div class="content">
					<h2 class="counter" data-value="<?php echo $total_posts_replies ?>">0</h2>
					<span>Posts und Antworten</span>
				</div>
			</div>
	
			<div class="box">
				<div class="line"></div>
				<div class="content">
					<h2 class="counter" data-value="<?php echo $total_likes ?>">0</h2>
					<span>Likes verteilt</span>
				</div>
			</div>
		</div>
	</section>

	<section class="imageAndText">
		<img class="shadow" src="img/piges.png">
		<div>
			<span class="subTitle">Registriere dich</span>
			<h2>Los geht es</h2>
			<span>Registriere dich im Handumdrehen und werde teil von Piger. Trete einer Community bei, in welcher alle Interessen vertreten sind.</span>
			<?php if(!Utils::isLoggedIn()){ ?>
			<br>
			<a href="signup.php">
				<button class="signUpBtn">Registrieren</button>
			</a>
			<?php } ?>
		</div>
	</section>

	<?php include 'footer.php' ?>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax/3.1.0/parallax.min.js"></script>
<script src="js/index.js"></script>
</html>