<?php
$id = $_SESSION['id'];

$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');
$query = $mysqli->query("SELECT username, displayname, pp_url FROM users WHERE id = '$id'");
$row = $query->fetch_assoc();

$username = $row['username'];
$displayname = addslashes($row['displayname']);
$pp_url = addslashes($row['pp_url']);
?>

<header>
	<link rel="stylesheet" type="text/css" href="css/navbar.css">
	<script src="js/core/Utils.js"></script>
</header>

<div class="line"></div>
<div id="navbar">
	<?php if(!isset($is_limited)){ ?>
	<svg id="post" viewBox="0 0 448 448" xmlns="http://www.w3.org/2000/svg" @click="togglePostBox(Mode.POST)" :class="{ close: postOpen }">
		<path d="m408 184h-136c-4.417969 0-8-3.582031-8-8v-136c0-22.089844-17.910156-40-40-40s-40 17.910156-40 40v136c0 4.417969-3.582031 8-8 8h-136c-22.089844 0-40 17.910156-40 40s17.910156 40 40 40h136c4.417969 0 8 3.582031 8 8v136c0 22.089844 17.910156 40 40 40s40-17.910156 40-40v-136c0-4.417969 3.582031-8 8-8h136c22.089844 0 40-17.910156 40-40s-17.910156-40-40-40zm0 0"/>
	</svg>

	<?php } ?>

	<div id="profileSection">
		<div id="profile" class="profile-picture" @click="profileClick">
			<img src="<?php echo $pp_url ?>" onload="this.className = this.offsetWidth > this.offsetHeight ? 'wider' : 'higher';" onerror="Utils.loadDefaultProfilePicture(this)">
		</div>
		<div class="box" id="profileMenu" v-if="profileMenuOpen">
			<div class="line"></div>

			<a href="piges.php" class="option">
				<div class="icon">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
						 viewBox="0 0 512.001 512.001" xml:space="preserve">
					<g>
						<g>
							<path d="M503.402,228.885L273.684,19.567c-10.083-9.189-25.288-9.188-35.367-0.001L8.598,228.886
								c-8.077,7.36-10.745,18.7-6.799,28.889c3.947,10.189,13.557,16.772,24.484,16.772h36.69v209.721
								c0,8.315,6.742,15.057,15.057,15.057h125.914c8.315,0,15.057-6.741,15.057-15.057V356.932h74.002v127.337
								c0,8.315,6.742,15.057,15.057,15.057h125.908c8.315,0,15.057-6.741,15.057-15.057V274.547h36.697
								c10.926,0,20.537-6.584,24.484-16.772C514.147,247.585,511.479,236.246,503.402,228.885z"/>
						</g>
					</g>
					<g>
						<g>
							<path d="M445.092,42.73H343.973l116.176,105.636v-90.58C460.149,49.471,453.408,42.73,445.092,42.73z"/>
						</g>
					</svg>


				</div>
				<div class="text">Piger</div>
			</a>

			<a href="profile.php?id=<?php echo $id ?>" class="option">
				<div class="icon">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
					viewBox="0 0 258.75 258.75" xml:space="preserve">
						<circle cx="129.375" cy="60" r="60"/>
						<path d="M129.375,150c-60.061,0-108.75,48.689-108.75,108.75h217.5C238.125,198.689,189.436,150,129.375,150z"/>
					</svg>
				</div>
				<div class="text">Mein Profil</div>
			</a>

			<a href="edit_profile.php" class="option">
				<div class="icon">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 528.899 528.899" xml:space="preserve">
						<path d="M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981
						c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611
						C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069
						L27.473,390.597L0.3,512.69z"/>
					</svg>

				</div>
				<div class="text">Profil bearbeiten</div>
			</a>

			<a href="server/logout.php" class="option">
				<div class="icon">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 96.943 96.943" xml:space="preserve">
						<path d="M61.168,83.92H11.364V13.025H61.17c1.104,0,2-0.896,2-2V3.66c0-1.104-0.896-2-2-2H2c-1.104,0-2,0.896-2,2v89.623
							c0,1.104,0.896,2,2,2h59.168c1.105,0,2-0.896,2-2V85.92C63.168,84.814,62.274,83.92,61.168,83.92z"/>
						<path d="M96.355,47.058l-26.922-26.92c-0.75-0.751-2.078-0.75-2.828,0l-6.387,6.388c-0.781,0.781-0.781,2.047,0,2.828
							l12.16,12.162H19.737c-1.104,0-2,0.896-2,2v9.912c0,1.104,0.896,2,2,2h52.644L60.221,67.59c-0.781,0.781-0.781,2.047,0,2.828
							l6.387,6.389c0.375,0.375,0.885,0.586,1.414,0.586c0.531,0,1.039-0.211,1.414-0.586l26.922-26.92
							c0.375-0.375,0.586-0.885,0.586-1.414C96.943,47.941,96.73,47.433,96.355,47.058z"/>
					</svg>

				</div>
				<div class="text">Ausloggen</div>
			</a>
		</div>
	</div>
	<div id="postBoxWrapper">
		<div id="postBox" :class="{ open: postOpen }">
			<div class="title">{{ postBoxTitle }}</div>
			<textarea id="postBoxInput" class="darker" maxlength="180" v-model="postText"></textarea>
			<div class="image" v-if="mode === Mode.POST">
				<div class="addImage">
				<div class="checkBox" :class="{ checked: withImage }" @click="withImage = !withImage"></div>
					Bild anfügen
				</div>
				<div v-if="withImage">
					<div class="inputDesc">Bild URL</div>
					<input v-model="imgUrl" type="text"maxlength="256" placeholder="http://...">
				</div>
			</div>
			<div class="buttons">
				<button @click="post" :class="{ pending: postPending }">Posten</button>
				<button class="secondary danger" @click="togglePostBox">Abbrechen</button>
			</div>
		</div>
	</div>
</div>

<div id="errorToastWrapper">
	<div id="errorToast" :class="{ visible: visible }">{{ text }}</div>
</div>

<script src="js/core/Network.js"></script>
<script>
	const Mode = {
		POST: 0,
		REPLY: 1
	};

	var errorApp = new Vue({
		el: "#errorToast",
		data: {
			visible: false,
			text: "",
			disappearTimeout: null
		},
		methods: {
			showError: function(text){
				this.text = text;
				this.visible = true;

				if(this.disappearTimeout){
					clearTimeout(this.disappearTimeout);
					this.disappearTimeout = null;
				}

				this.disappearTimeout = setTimeout(() => this.visible = false, 2500);
			}
		}
	});


	var canCall = false;

	var navbarApp = new Vue({
		el: "#navbar",
		data: {
			mode: Mode.POST,
			postText: "",
			profileMenuOpen: false,
			postOpen: false,
			postPending: false,
			postBoxTitle: "Post verfassen",
			replyId: "",
			withImage: false,
			imgUrl: ""
		},
		methods: {
			profileClick: function(){
				this.profileMenuOpen = !this.profileMenuOpen;
				canCall = false;
			},
			openPostBox: function(mode, post){
				this.postOpen = true;

				this.mode = mode;

				this.postBoxTitle = mode === Mode.REPLY ? post.user.displayName + " antworten" : "Post verfassen";
				this.replyId = mode === Mode.REPLY ? post.id : "";
				
				document.getElementById("postBoxInput").focus();
			},
			closePostBox: function(mode, post){
				this.postOpen = false;

				if(this.postPending)
					this.postPending = false;

				this.postText = this.imgUrl = ""; // clear text
				this.withImage = false;

			},
			togglePostBox: function(mode, post){
				if(this.postOpen)
					this.closePostBox(mode, post);
				else
					this.openPostBox(mode, post);
			},
			post: function(){
				// makes sure the message won't be interpreted as HTML, except line breaks which have to converted to HTML breaks first
				// replace code by eyelidlessness
				// source: https://stackoverflow.com/a/784547
				var message = Utils.escapeHTML(this.postText).replace(/(?:\r\n|\r|\n)/g, "<br>");

				this.postPending = true;
				postJson = {
					id: null, // id will stay null until it receives its value from the server
					message,
					likes: 0,
					date: Utils.getUTCNow(),
					imgUrl: this.imgUrl,
					composer: true,
					pending: true,
					user: {
						id: "<?php echo $id ?>",
						userName: "<?php echo $username ?>",
						displayName: "<?php echo $displayname ?>",
						ppUrl: "<?php echo $pp_url ?>"
					}
				};

				// check if the post is a post or a reply
				if(this.replyId === ""){
					// wrap it in a new array, because a new postList is being created
					postsApp.posts.unshift([
						postJson
					]);
				}else{
					// add to the target post's list
					postsApp.getListById(this.replyId).push(postJson);
				}

				Network.post(this.postText, this.replyId, this.withImage ? this.imgUrl : "")
				.then(id => {
					this.postPending = false;

					if(this.postOpen)
						this.togglePostBox();

					if(id){
						// call an event for the post
						var postEvent = new CustomEvent("pigerpost", {
							detail: {
								isReply: this.replyId !== ""
							}
						});
						document.dispatchEvent(postEvent);
						postJson.pending = false;
						postJson.id = id;
					}else{
						// post couldn't be saved
						postsApp.removePost(postJson);
						errorApp.showError("Dein Post konnte nicht gespeichert werden. Bitte versuche es erneut.");
					}
				});
			}
		}
	});

	document.addEventListener("keydown", ev => {
		if(ev.key.toLowerCase() === "escape"){
			navbarApp.profileMenuOpen = false;
			if(navbarApp.postOpen)
				navbarApp.togglePostBox();
		}
	});

	document.addEventListener("click", ev => {
		// block call right after the box was opened
		if(!canCall){
			canCall = true;
			return;
		}

		if(!ev.path.some(el => el.id == "profileMenu")){
			navbarApp.profileMenuOpen = false;
		}
	});
</script>