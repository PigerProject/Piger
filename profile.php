<?php
		session_start();
		$userId = $_GET['id'];
		
		include 'server/Utils.php';
		
		if(Utils::checkInputsInvalid($userId))
			onError();
		
		$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');
		$stmt = $mysqli->prepare('SELECT username, displayname, pp_url, bio, registerdate FROM users WHERE id = ?');
		$stmt->bind_param('s', $userId);
		
		if(!$stmt->execute())
			onError();

		$row = $stmt->get_result()->fetch_assoc();

		$user_username = $row['username'];
		$user_displayname = $row['displayname'];
		$user_pp_url = $row['pp_url'];
		$user_bio = $row['bio'];

		if(strlen($user_bio) === 0)
			$user_bio = $user_username . ' schweigt lieber über sich.';

		$registerdate = $row['registerdate'];
		$date = new DateTime($registerdate);

		$day = $date->format('d');
		$month = $date->format('n');
		$year = $date->format('Y');

		$registerdate = $day . '. ' . Utils::monthToGerman($month) . ' ' . $year;

		$total_posts = Utils::getTotalPostsCountOfUser($mysqli, $userId);
		$total_replies = Utils::getTotalRepliesCountOfUser($mysqli, $userId);
		$total_likes = Utils::getTotalLikesCountOfUser($mysqli, $userId);

		// todo add infinite scrolling

		if($row === null)
			onError();
		
		function onError(){
			include 'profile_not_found.php';
			die();
		}

		if(Utils::isLoggedIn() && $userId !== $_SESSION['id'])
			$is_limited = true;
	?>
<!DOCTYPE html>
<html encoding="UTF-8">
<head>
	<title><?php echo $user_displayname ?> - Piger</title>

	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran:600,700" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="css/core.css">
	<link rel="stylesheet" type="text/css" href="css/pige.css">
	<link rel="stylesheet" type="text/css" href="css/profile.css">

	<script src="https://cdn.jsdelivr.net/npm/vue"></script>
</head>

<body>
	<?php include Utils::isLoggedIn() ? 'navbar.php' : 'navbar_guest.php' ?>

	<svg id="backBtn" version="1.1" id="backBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
		viewBox="0 0 309.143 309.143" xml:space="preserve" onclick="Utils.goBack()">
		<path d="M112.855,154.571L240.481,26.946c2.929-2.929,2.929-7.678,0-10.606L226.339,2.197 C224.933,0.79,223.025,0,221.036,0c-1.989,0-3.897,0.79-5.303,2.197L68.661,149.268c-2.929,2.929-2.929,7.678,0,10.606 l147.071,147.071c1.406,1.407,3.314,2.197,5.303,2.197c1.989,0,3.897-0.79,5.303-2.197l14.142-14.143 c2.929-2.929,2.929-7.678,0-10.606L112.855,154.571z"/>
	</svg>

	<div id="background">
	</div>

	<div id="about" class="box">
		<div class="profile-picture" v-on:click="profileClick">
			<img src="<?php echo $user_pp_url ?>" onload="this.className = this.offsetWidth > this.offsetHeight ? 'wider' : 'higher';" onerror="Utils.loadDefaultProfilePicture(this)" onclick="Utils.zoomImage('<?php echo $user_pp_url ?>')" style="cursor: pointer">
		</div>

		<div class="content">
			<div class="names">
				<div class="displayname"><?php echo $user_displayname ?></div>
				<div class="username">@<?php echo $user_username ?></div>
			</div>
			<div id="stats">
				<div>
					<div class="desc">Posts</div>
					<div class="value">{{ totalPosts }}</div>
				</div>

				<div>
					<div class="desc">Antworten</div>
					<div class="value">{{ totalReplies }}</div>
				</div>

				<div>
					<div class="desc">Likes</div>
					<div class="value">{{ totalLikes }}</div>
				</div>
			</div>
		</div>
		<div id="aboutText">
			<div>
				<div class="desc">Über mich</div>
				<div class="ln"></div>
				<div class="value"><?php echo $user_bio ?></div>
			</div>
			<div>
				<div class="desc">Mitglied seit</div>
	
				<div class="ln"></div>
				<div class="value"><?php echo $registerdate ?></div>
			</div>
		</div>
	</div>
	<div id="piges">
		<div class="pigeList" v-for="postList in posts">
			<div v-for="(post, index) in postList">
				<user-post :post="post" :replies="index == 0 ? postList.length - 1 : 0" :isreply="index > 0"></user-post>
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
	postsApp.loadPostsOfUser(userId);

	document.body.onscroll = () => {
    	if(window.scrollY + window.innerHeight * 1.5 > document.documentElement.offsetHeight){
     		postsApp.loadOlderPostsOfUser(userId);
    	}
	}

	// parallax
	var background = document.getElementById("background");
	document.onscroll = () => background.style.backgroundPositionY = window.scrollY / 2 + "px";

	const initPostsCount = <?php echo $total_posts ?>;
	const initRepliesCount = <?php echo $total_replies ?>;
	const initLikesCount = <?php echo $total_likes ?>;

	var downloadedPostsCount = 0;
	var downloadedRepliesCount = 0;
	var downloadedLikesCount = 0;

	document.addEventListener("pigesdownloaded", ev => {
		downloadedPostsCount += ev.detail.posts;
		downloadedRepliesCount += ev.detail.replies;
		downloadedLikesCount += ev.detail.likes;
	});

	// keeps track of the user's stats (posts, replies, likes)
	var statsApp = new Vue({
		el: "#stats",
		computed: {
			totalPosts: function(){
				var count = 0, currentCount = postsApp.posts.reduce((count, postList) => count += postList[0].user.id === userId ? 1 : 0, 0);
				count = currentCount - downloadedPostsCount + initPostsCount;
				return count;
			},
			totalReplies: function(){
				var count = 0, currentCount = postsApp.posts.reduce((count, postList) => count += postList.length - 1, 0);
				count = currentCount - downloadedRepliesCount + initRepliesCount;
				return count;
			},
			totalLikes: function(){
				var count = 0, currentCount = postsApp.posts.reduce((count, postList) => {
					for(let post of postList)
						count += post.user.id === userId ? post.likes : 0;
					return count;
				}, 0);

				return currentCount - downloadedLikesCount + initLikesCount;
			}
		}
	});
</script>
</html>