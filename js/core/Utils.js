class Utils {
	// code by Tareq
	// source: https://stackoverflow.com/a/40287849
	static jsonToUrl(data){
		return Object.entries(data).map(e => e.join('=')).join('&');
	}

	static loadDefaultProfilePicture(img){
		img.src = "img/default_profile_picture.svg";
	}

	static goBack(fallbackLink = "piges.php"){
		if(document.referrer.length > 0)
			history.go(-1);
		else
			location.href = fallbackLink;
	}

	static initDownloadedPosts(postLists, clientId){
		// check if all the posts are from the user
		for(let i = 0; i < postLists.length; i++){
			var posts = postLists[i];
			for(let j = 0; j < posts.length; j++){
				let postUserId = posts[j].user.id;
				posts[j].composer = postUserId === clientId;
				posts[j].pending = false;
			}
		}

		return postLists;
	}

	static convertSecondsToTime(seconds){
		// seconds
		if(seconds < 60){
			return seconds + "s";
		}

		// minutes
		if(seconds < 3600){
			return Math.floor(seconds / 60) + "m";
		}

		// hours
		if(seconds < 86400){
			return Math.floor(seconds / 3600) + "h";
		}

		// days
		if(seconds < 604800){
			var days = Math.floor(seconds / 86400);
			return days + " Tag" + (days > 1 ? "en" : "");
		}

		// weeks
		if(seconds < 2678400 ){
			var weeks = Math.floor(seconds / 604800);
			return weeks + " Woche" + (weeks > 1 ? "n" : "");
		}

		// months
		if(seconds < 31557600 ){
			var months = Math.floor(seconds / 2678400);
			return months + " Monat" + (months > 1 ? "e" : "");
		}

		// years
		var years = Math.floor(seconds / 31536000);
		return years + " Jahr" + (years > 1 ? "e" : "");
	}

	static format(timeNumber){
		if(timeNumber < 10)
			timeNumber = "0" + timeNumber;

		return timeNumber;
	}

	// converts the given date into a valid format for requesting posts from the server
	static formatDate(date){
		return date.getFullYear() + "-" + Utils.format(date.getMonth() + 1) + "-" + Utils.format(date.getDate()) + " " + Utils.format(date.getHours()) + ":" + Utils.format(date.getMinutes()) + ":" + Utils.format(date.getSeconds());
	}

	// code by bjornd
	// source: https://stackoverflow.com/a/6234804
	static escapeHTML(unsafe) {
		return unsafe
			 .replace(/&/g, "&amp;")
			 .replace(/</g, "&lt;")
			 .replace(/>/g, "&gt;")
			 .replace(/"/g, "&quot;")
			 .replace(/'/g, "&#039;");
	 }

	 // code by Grass Double
	 // source: https://stackoverflow.com/a/11964609
	 static getUTCNow(){
		var now = new Date();
		var utc = new Date(now.getTime() + now.getTimezoneOffset() * 60000);
		return utc;
	 }

	 static countPostAndReplies(posts){
		 var count = {
			 posts: 0,
			 replies: 0,
			 likes: 0
		 };

		 var userId = new URL(location.href).searchParams.get("id");

		 posts.reduce((_accumulator, currentValue) => count.posts += currentValue[0].user.id === userId ? 1 : 0, 0);
		 posts.reduce((_accumulator, currentValue) => count.replies += currentValue.length - 1, 0);

		 count.likes = posts.reduce((count, postList) => {
			for(let post of postList)
				count += post.user.id === userId ? post.likes : 0;
			return count;
		}, 0);

		 return count;
	 }

	 static zoomImage(imgSrc){
		 var imageZoom = document.getElementById("imageZoom");

		 var img = document.getElementById("imageZoom").querySelector("img");
		 img.src = imgSrc;

		 img.onload = () => {
			document.body.className = "imgZoom";
			imageZoom.style.display = "flex";
		 }

		 var unzoomImage = () => {
			document.body.className = "";
			imageZoom.style.display = "none";
		 }

		 var clickListener = () => {
			 unzoomImage();
			 document.body.removeEventListener("click", unzoomImage);
		 }

		 var escapeListener = ev => {
			 if(ev.key.toLowerCase() === "escape"){
				 unzoomImage();
				 document.body.removeEventListener("keydown", unzoomImage);
			 }
		 }

		 document.body.addEventListener("click", clickListener);
		 document.body.addEventListener("keydown", escapeListener);
	 }
}