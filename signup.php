<!DOCTYPE html>
<html encoding="UTF-8">
<head>
	<title>Registrieren</title>

	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran:600,700" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="css/core.css">
	<link rel="stylesheet" type="text/css" href="css/signup.css">

	<script src="https://cdn.jsdelivr.net/npm/vue"></script>

	<link rel="prerender" href="index.php">
	<link rel="prerender" href="set_profile.php">
</head>

<body>
	<div class="line"></div>

	<div id="signUpApp">

		<svg version="1.1" id="backBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
		viewBox="0 0 309.143 309.143" xml:space="preserve" v-on:click="Utils.goBack()">
			<path d="M112.855,154.571L240.481,26.946c2.929-2.929,2.929-7.678,0-10.606L226.339,2.197 C224.933,0.79,223.025,0,221.036,0c-1.989,0-3.897,0.79-5.303,2.197L68.661,149.268c-2.929,2.929-2.929,7.678,0,10.606 l147.071,147.071c1.406,1.407,3.314,2.197,5.303,2.197c1.989,0,3.897-0.79,5.303-2.197l14.142-14.143 c2.929-2.929,2.929-7.678,0-10.606L112.855,154.571z"/>
		</svg>


		<div id="wrapper">
			<div class="title">
				<h2>Registrieren</h2>
			</div>
			<div class="inputDesc">Username</div>
			<input id="usernameInput" v-model="username" type="text" maxlength="16">
			<p id="usernameError" class="error" v-bind:class="{ visible: usernameInvalid }">{{ Network.getUsernameErrorMessage(lastUsernameErrorCode) }}</p>

			<div class="inputDesc">Passwort</div>
		
			<div id="passwordWrapper">
				<input id="passwordInput" v-model="password" v-bind:type="passwordVisible ? 'text' : 'password'" maxlength="32">

				<svg class="eye" v-bind:class="{ toggled: passwordVisible }" v-on:click="eyeClick" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 488.85 488.85" xml:space="preserve">
					<path d="M244.425,98.725c-93.4,0-178.1,51.1-240.6,134.1c-5.1,6.8-5.1,16.3,0,23.1c62.5,83.1,147.2,134.2,240.6,134.2 s178.1-51.1,240.6-134.1c5.1-6.8,5.1-16.3,0-23.1C422.525,149.825,337.825,98.725,244.425,98.725z M251.125,347.025 c-62,3.9-113.2-47.2-109.3-109.3c3.2-51.2,44.7-92.7,95.9-95.9c62-3.9,113.2,47.2,109.3,109.3 C343.725,302.225,302.225,343.725,251.125,347.025z M248.025,299.625c-33.4,2.1-61-25.4-58.8-58.8c1.7-27.6,24.1-49.9,51.7-51.7 c33.4-2.1,61,25.4,58.8,58.8C297.925,275.625,275.525,297.925,248.025,299.625z"/>
				</svg>
			</div>
			<p id="passwordError" class="error" v-bind:class="{ visible: passwordInvalid }">{{ Network.getPasswordErrorMessage(lastPasswordErrorCode) }}</p>

			<p class="small">Piger ist ein Schulprojekt und dient nur demonstrativen Zwecken. Verwende deswegen bitte nicht dein echtes Passwort.</p>
			<div class="buttonWrapper">
				<button :disabled="invalid" v-bind:class="{ pending: signUpPending }" v-on:click="signUp">Registrieren</button>
			</div>
		</div>
	</div>
</body>

<script src="js/core/Utils.js"></script>
<script src="js/core/Network.js"></script>
<script type="text/javascript">
	var passwordInput = document.getElementById("passwordInput");

	var usernameError = document.getElementById("usernameError");
	var passwordError = document.getElementById("passwordError");

	const MINIMAL_PASSWORD_LENGTH = 4;

	var signUpApp = new Vue({
		el: "#signUpApp",
		data: {
			username: "",
			password: "",
			passwordVisible: false,
			signUpPending: false,
			lastUsernameErrorCode: 0,
			lastPasswordErrorCode: 0
		},
		methods: {
			eyeClick: function(){
				this.passwordVisible = !this.passwordVisible;
			},
			signUp: function(){
				this.signUpPending = true;
				this.usernameInvalid = false;
				this.passwordInvalid = false;

				Network.signUp(this.username, this.password)
				.then(result => this.onResponse(result));
			},
			onResponse: function(result){
				this.lastUsernameErrorCode = result.username_error_code;
				this.lastPasswordErrorCode = result.password_error_code;

				this.signUpPending = false;

				// no error codes, sign-up was successful
				if(this.lastUsernameErrorCode === 0 && this.lastPasswordErrorCode === 0)
					location.href = "setup_profile.php";
			}
		},
		computed: {
			invalid: function(){
				return this.username === "" || this.password === "" || this.password.length < MINIMAL_PASSWORD_LENGTH;
			},
			passwordInputType: function(){
				return this.passwordVisible ? "text" : "password";
			},
			usernameInvalid: function(){
				return this.lastUsernameErrorCode !== 0;
			},
			passwordInvalid: function(){
				return this.lastPasswordErrorCode !== 0;
			}
		}
	});

	document.addEventListener("keydown", ev => {
		if(ev.key.toLowerCase() === "enter")
			signUpApp.signUp();
	});
</script>
</html>