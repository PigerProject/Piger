<?php
session_start();
$url = 'profile.php?id=' . $_SESSION['id'];
?>

<!DOCTYPE html>
<html encoding="UTF-8">
<head>
	<title>Profil bearbeiten</title>

	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran:600,700" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="css/core.css">
	<link rel="stylesheet" type="text/css" href="css/profile_options.css">

	<script src="https://cdn.jsdelivr.net/npm/vue"></script>
</head>

<body>
	<div class="line"></div>
	<?php include 'profile_options.php' ?>
	<button id="cancelBtn" class="secondary" onclick="Utils.goBack('<?php echo $url ?>')">Zurück</button>
	<div id="deleteBtnWrapper">
		<button id="deleteBtn" class="secondary danger" :class="{ pending: deletePending }" @click="deleteAccount" @mouseover="mouseOver" @mouseout="mouseOut">{{ hasConfirmedDelete ? "Bestätigen" : "Profil löschen" }}</button>
		<p class="error" v-bind:class="{ visible: deleteError }">Dein Profil konnte nicht gelöscht werden. Bitte versuche es erneut.</p>
	</div>
</body>

<script>
	var cancelBtn = document.getElementById("cancelBtn");
	var deleteBtn = document.getElementById("deleteBtn");

	// make the delete button maintain the same width when the text changes
	deleteBtn.style.minWidth = "172px";

	document.getElementById("buttons").insertAdjacentElement("afterbegin", cancelBtn);
	document.getElementById("title").textContent = "Profil bearbeiten";

	var deleteApp = new Vue({
		el: "#deleteBtnWrapper",
		data: {
			deletePending: false,
			hasConfirmedDelete: false,
			deleteError: false,
			cancelTimeout: null
		},
		methods: {
			deleteAccount: function(){

				if(!this.hasConfirmedDelete){
					this.hasConfirmedDelete = true;
					return;
				}

				this.deleteError = false;
				Network.deleteAccount()
				.then(success => {
					if(!success){
						this.deletePending = false;
						this.deleteError = true;
					}else
						location.href = "index.php";
				});
			},

			mouseOver: function(){
				if(this.cancelTimeout !== null){
					clearTimeout(this.cancelTimeout);
					this.cancelTimeout = null;
				}
			},

			mouseOut: function(){
				if(this.hasConfirmedDelete)
					this.cancelTimeout = setTimeout(() => this.hasConfirmedDelete = false, 250);
			}
		}
	});

	if(window.innerWidth <= 568){
		var previewImg = document.getElementById("previewImg");
		document.getElementById("deleteBtnWrapper").style.top = previewImg.offsetTop + previewImg.offsetHeight + 48 + "px";
	}
</script>
</html>