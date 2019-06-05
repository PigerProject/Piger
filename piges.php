<?php
    include 'server/Utils.php';
	session_start();
	$userId = '5cf02cd916e18';
?>
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
	<link rel="stylesheet" type="text/css" href="css/pige.css">
	<link rel="stylesheet" type="text/css" href="css/piges.css">

	<script src="https://cdn.jsdelivr.net/npm/vue"></script>
</head>

<body>
	<?php include Utils::isLoggedIn() ? 'navbar.php' : 'navbar_guest.php' ?>
	<div id="welcome">
		<a href="index.php">
			<svg id="backBtn" version="1.1" id="backBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 0 309.143 309.143" xml:space="preserve">
		    <path d="M112.855,154.571L240.481,26.946c2.929-2.929,2.929-7.678,0-10.606L226.339,2.197 C224.933,0.79,223.025,0,221.036,0c-1.989,0-3.897,0.79-5.303,2.197L68.661,149.268c-2.929,2.929-2.929,7.678,0,10.606 l147.071,147.071c1.406,1.407,3.314,2.197,5.303,2.197c1.989,0,3.897-0.79,5.303-2.197l14.142-14.143 c2.929-2.929,2.929-7.678,0-10.606L112.855,154.571z"/>
			</svg>
		</a>
		<h3>Piger Hauptseite</h3>
		<p>Hier kann jeder etwas posten, kommentieren oder einen Post liken.</p>
		<a href="search.php">
			<button>Suchen</button>
		</a>
	</div>
	<div id="piges">
		<div class="pigeList" v-for="postList in posts">
			<div v-for="(post, index) in postList">
				<user-post :post="post" :replies="index == 0 ? postList.length - 1 : 0" :isreply="index > 0"></user-post>
			</div>
        </div>

        <div class="skeletonPigeWrapper" v-if="isDownloading">
            <div class="pigeList" v-for="index in 8">
                <div class="skeletonPige pige pending">
		        	<div class="pigeMain">
		        		<div class="profile-picture"></div>
		        		<div class="text"></div>
		        	</div>
                </div>
            </div>
        </div>
	</div>
	
	<div id="imageZoom">
		<button class="secondary">Schließen</button>
		<img src="">
	</div>
</body>

<script>
	// define constants
	const userId = "<?php echo $userId ?>";
	const clientId = "<?php echo isset($_SESSION['id']) ? $_SESSION['id'] : '' ?>";
</script>
<script src="js/postsApp.js"></script>
<script src="js/PostComponent.js"></script>
<script>
	document.onload = () => console.log("loaded");
	postsApp.loadOlderPosts();

	document.body.onscroll = () => {
		if(window.scrollY + window.innerHeight * 1.5 > document.documentElement.offsetHeight){
			postsApp.loadOlderPosts();
		}
	}
</script>
</html>