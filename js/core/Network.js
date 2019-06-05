class Network {
	static get POST_HEADERS(){
		return new Headers({
			"Content-Type": "application/x-www-form-urlencoded"
		});
	}

	static get USERNAME_ERROR_MESSAGES(){
		return ["Der Username ist bereits vergeben.", "Der Username ist zu lang.", "Der Username darf nur Buchstaben und Zahlen enthalten.", "Bitte gebe einen Usernamen ein."];
	}

	static get PASSWORD_ERROR_MESSAGES(){
		return ["Das Passwort ist zu kurz.", "Das Passwort ist zu lang.", "Das Passwort ist unzulässig. Bitte nehme ein anderes Passwort.", "Bitte gebe ein Passwort ein."];
	}

	static getUsernameErrorMessage(errorCode){
		if(errorCode == 0)
			return "";

		return Network.USERNAME_ERROR_MESSAGES[errorCode - 1];
	}

	static getPasswordErrorMessage(errorCode){
		if(errorCode == 0)
			return "";

		return Network.PASSWORD_ERROR_MESSAGES[errorCode - 1];
	}


	static async signUp(username, password){
		var data = {
			username,
			password
		};

		return await Network.createPostFetch("server/signup.php", data)
		.then(response => {
			return response.json();
		})
		.then(json => {
			return json;
		});
	}

	static async login(username, password){
		var data = {
			username,
			password
		};

		return await Network.createPostFetch("server/login.php", data)
		.then(response => {
			return response.status === 200;
		});
	}

	static async updateProfile(displayName, bio, ppUrl){
		var data = {
			displayName,
			bio,
			ppUrl
		};

		return await Network.createPostFetch("server/update_profile.php", data)
		.then(response => {
			return response.status === 200;
		});
	}

	static async deleteAccount(){
		return await Network.createPostFetch("server/delete_account.php", {})
		.then(response => {
			return response.status === 200;
		});
	}

	// posts a post to the server and returns the ID the post has gotten from the server
	static async post(message, replyId = "", imgUrl = ""){
		var data = {
			message,
			replyId,
			imgUrl
		};

		return await Network.createPostFetch("server/post.php", data)
		.then(response => {
			return response.text();
		});
	}

	static async getPostsByUser(userId){
		var data = {
			userId
		};

		return await fetch("server/posts.php?id=" + userId)
		.then(response => {
			if(response.status !== 200)
				return false;

			return response.json();
		});
	}

	static async getOlderPostsByUser(userId, index){
		return await fetch("server/posts.php?id=" + userId + "&index=" + index)
		.then(response => {
			if(response.status !== 200)
				return false;

			return response.json();
		});
	}

	static async getRecentPosts(lastFetchTime){
		return await fetch("server/posts.php?lastFetchTime=" + lastFetchTime)
		.then(response => {
			if(response.status !== 200)
				return false;

			return response.json();
		});
	}

	static async getOlderPosts(index){
		return await fetch("server/posts.php?index=" + index)
		.then(response => {
			if(response.status !== 200)
				return false;

			return response.json();
		});
	}

	static async search(username, searchIndex){
		return await fetch("server/search.php?username=" + username + "&searchIndex=" + searchIndex)
		.then(response => {
			if(response.status !== 200)
				return false;

			return response.json();
		});
	}

	static async likePost(id){
		var data = {
			id
		};

		return await Network.createPostFetch("server/like_post.php", data)
		.then(response => {
			return response.status === 200;
		});
	}

	static async unlikePost(id){
		var data = {
			id
		};

		return await Network.createPostFetch("server/unlike_post.php", data)
		.then(response => {
			return response.status === 200;
		});
	}

	static async deletePost(id){
		var data = {
			id
		};

		return await Network.createPostFetch("server/delete_post.php", data)
		.then(response => {
			return response.status === 200;
		});
	}

	static createPostFetch(url, data){
		return fetch(url, {
			method: "POST",
			body: Utils.jsonToUrl(data),
			headers: Network.POST_HEADERS
		});
	}

	static createGetFetch(url, data){
		return fetch(url, {
			method: "GET",
			body: Utils.jsonToUrl(data),
			headers: Network.POST_HEADERS
		});
	}
}