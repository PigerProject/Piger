<?php
if(!isset($_SESSION['id'])){
	header('Location: index.php');
}

$id = $_SESSION['id'];

$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');
$query = $mysqli->query("SELECT displayname, bio, pp_url FROM users WHERE id = '$id'");
$row = $query->fetch_assoc();

$displayname = addslashes(html_entity_decode($row['displayname']));
$bio = addslashes(html_entity_decode($row['bio']));
$pp_url = addslashes($row['pp_url']);
?>

<div id="profileOptionsApp">
	<div id="buttons">
		<button id="saveBtn" :disabled="sizeError" :class="{ pending: savePending }" @click="save">Speichern</button>
		<p class="error" :class="{ visible: error }">Deine Änderungen konnten nicht gespeichert werden.</p>
	</div>
	<div id="wrapper">
		<h2 id="title"></h2>
		<div class="sections">
			<div class="section">
				<div class="inputDesc">Name</div>
				<input v-model="displayName" type="text" maxlength="16">

				<div class="inputDesc">Über Mich</div>
				<textarea id="bioInput" v-model="bio" type="text" rows="5" maxlength="180"></textarea>
			</div>

			<div class="section">
				<div class="inputDesc">Profilbild URL</div>
				<input v-model="ppUrlInput" @input="onInput" type="text" maxlength="256" placeholder="http://...">
				<p class="error" :class="{ visible: sizeError }">Das Bild darf nicht größer als 4000 × 3000 Pixel sein.</p>

				<div class="inputDesc">Vorschau</div>
				<div class="profile-picture">
					<img id="previewImg" :src="ppUrl" @load="onLoad" @error="onLoadError">
				</div>
			</div>
		</div>
	</div>
</div>

<script src="js/core/Utils.js"></script>
<script src="js/core/Network.js"></script>
<script>
	const DEFAULT_IMG_URL = "img/default_profile_picture.svg";

	var origDisplayName = `<?php echo $displayname ?>`,
		origBio = `<?php echo $bio ?>`,
		origPpUrl = "<?php echo $pp_url ?>";

	var profileOptionsApp = new Vue({
		el: "#profileOptionsApp",
		data: {
			displayName: origDisplayName,
			bio: origBio,
			ppUrlInput: origPpUrl,
			ppUrl: origPpUrl,
			invalid: true,
			savePending: false,
			error: false,
			sizeError: false
		},
		methods: {
			onLoadError: function(){
				this.invalid = true;
				this.ppUrl = DEFAULT_IMG_URL;
			},
			onInput: function(){
				this.invalid = false;
				this.ppUrl = this.ppUrlInput;

				if(this.error)
					this.error = false;
			},
			onLoad: function(){
				var previewImg = document.getElementById("previewImg");
				previewImg.className = previewImg.offsetWidth > previewImg.offsetHeight ? "wider" : "higher";

				this.sizeError = previewImg.naturalWidth * previewImg.naturalHeight > 12000000; // 4000 * 3000
			},
			save: function(){
				// no (valid) input at all, no need to save
				if(this.displayName === origDisplayName && this.bio === origBio && this.ppUrl === origPpUrl){
					location.href = "piges.php";
					return;
				}

				this.savePending = true;
				this.error = false;

				Network.updateProfile(this.displayName, this.bio, this.ppUrl === DEFAULT_IMG_URL ? "" : this.ppUrl)
				.then(success => {
					if(success)
						location.href = "piges.php";

					this.savePending = false;

					if(!success)
						this.error = true;
				});
			}
		}
	});
</script>