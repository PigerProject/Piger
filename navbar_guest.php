<header>
	<link rel="stylesheet" type="text/css" href="css/navbar.css">
	<link rel="stylesheet" type="text/css" href="css/navbar_guest.css">
</header>

<div class="line"></div>
<div id="navbar">
	<div id="loginDetails">
		<button class="secondary label" v-on:click="open">Login</button>
		<div id="loginBox" class="box" v-show="isOpen">
			<div class="inputDesc">Username</div>
			<input class="darker" v-model="username" type="text" maxlength="16">
			<br>
			<div class="inputDesc">Password</div>
			<div id="passwordWrapper">
				<input id="passwordInput" class="darker" v-model="password" v-bind:type="passwordVisible ? 'text' : 'password'" maxlength="32">

				<svg class="eye" v-bind:class="{ toggled: passwordVisible }" v-on:click="eyeClick" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 488.85 488.85" xml:space="preserve">
					<path d="M244.425,98.725c-93.4,0-178.1,51.1-240.6,134.1c-5.1,6.8-5.1,16.3,0,23.1c62.5,83.1,147.2,134.2,240.6,134.2 s178.1-51.1,240.6-134.1c5.1-6.8,5.1-16.3,0-23.1C422.525,149.825,337.825,98.725,244.425,98.725z M251.125,347.025 c-62,3.9-113.2-47.2-109.3-109.3c3.2-51.2,44.7-92.7,95.9-95.9c62-3.9,113.2,47.2,109.3,109.3 C343.725,302.225,302.225,343.725,251.125,347.025z M248.025,299.625c-33.4,2.1-61-25.4-58.8-58.8c1.7-27.6,24.1-49.9,51.7-51.7 c33.4-2.1,61,25.4,58.8,58.8C297.925,275.625,275.525,297.925,248.025,299.625z"/>
				</svg>
			</div>
			<div class="bottom">
				<button id="loginBtn" :disabled="invalid" v-bind:class="{ pending: loginPending }" v-on:click="login">Login</button>
			</div>
			<p id="errorMessage" class="error" v-bind:class="{ visible: loginFailed }">
				Dein Username oder dein Passwort ist falsch. Bitte versuche es erneut.
			</p>
		</div>
	</div>
</div>
<script src="js/core/Utils.js"></script>
<script src="js/core/Network.js"></script>
<script type="text/javascript">
	var loginText = document.getElementById("loginText");
	var loginDetails = document.getElementById("loginDetails");
	var loginBox = document.getElementById("loginBox");
	var loginBtn = document.getElementById("loginBtn");

	var errorMessage = document.getElementById("errorMessage");

	const MINIMAL_PASSWORD_LENGTH = 4;

	var canCall = false;

	var loginApp = new Vue({
		el: loginDetails,
		data: {
			loginFailed: false,
			loginPending: false,
			username: "",
			password: "",
			passwordVisible: false,
			isOpen: false
		},
		methods: {
			login: function(){
				// check if a login is already pending or the inputs are invalid
				if(this.loginPending || this.invalid)
					return;

				this.loginPending = true;

				Network.login(this.username, this.password)
				.then(success => this.onResponse(success));
			},
			open: function(){
				this.isOpen = true;
				canCall = false;
			},
			close: function(){
				this.loginFailed = false;
				this.isOpen = false;
			},
			// called when the browser has received a response from the server after a login attempt
			onResponse: function(success){
				this.loginPending = false;

				this.loginFailed = !success; // if the login was successful, then the server returns 200, if not then 401

				// check if the user logged in successfuly
				if(success){
					location.href = "piges.php";
				}
			},
			eyeClick: function(){
				this.passwordVisible = !this.passwordVisible;
			}
		},
		computed: {
			invalid: function(){
				return this.username === "" || this.password === "" || this.password.length < MINIMAL_PASSWORD_LENGTH;
			}
		}
	});

	// event listeners

	document.addEventListener("keydown", ev => {
		switch(ev.key.toLowerCase()){
			case "escape":
				loginApp.close();
			break

			case "enter":
				loginApp.login();
			break;
		}
	});

	document.addEventListener("click", ev => {
		// block call right after the box was opened
		if(!canCall){
			canCall = true;
			return;
		}

		if(!ev.path.some(el => el.id == loginBox.id)){
			loginApp.close();
		}
	});
</script>