Vue.component('user-post', {
    props: ['post', 'pending', 'replies', 'isreply'],
    template: `
		<div class="pige" ref="post" :class="{ pending: post.pending, reply: isreply }">
			<div class="pigeMain">
				<a class="profile-picture" :href='"profile.php?id=" + post.user.id'>
					<img class="profile-picture-default" :src="post.user.ppUrl" onload="this.className = this.offsetWidth > this.offsetHeight ? 'wider' : 'higher';" onerror="Utils.loadDefaultProfilePicture(this)">
				</a>
				<div class="text">
					<div class="textHeader">
						<span class="displayName" v-html="post.user.displayName"></span>
						<a class="userName" :href='"profile.php?id=" + post.user.id'>@{{ post.user.userName }}</a>
					</div>
					<div class="content">
						<div v-html="post.message"></div>
						<img v-if="post.imgUrl !== ''" :src="post.imgUrl" ref="image" @error="onImageLoadError" @click="Utils.zoomImage(post.imgUrl)">
					</div>
				</div>
			</div>
			
			<div class="pigeData">
				<div :class="{ liked: post.liked }" class="bar heartBar" @click="postsApp.like(post); likeClick();">
					<span>{{ post.likes }}</span>
					<svg class="heart" :class="{ animation: likeAnimationPlaying }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#f88" stroke-width="2	" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 	0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">	</path></svg>
				</div>
				<div class="bar replyBar" @click="doPost" v-if="!isreply">
					<span>{{ replies }}</span>
					
					<svg class="reply" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#88f" stroke-width="2	" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 	8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 	4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
				</div>
				
				<span class="time">vor {{ time }}</span>
			</div>

			<svg class="delete" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
	 viewBox="0 0 486.4 486.4" xml:space="preserve" v-if="post.composer" @click="postsApp.delete(post)">
	<path d="M446,70H344.8V53.5c0-29.5-24-53.5-53.5-53.5h-96.2c-29.5,0-53.5,24-53.5,53.5V70H40.4c-7.5,0-13.5,6-13.5,13.5
		S32.9,97,40.4,97h24.4v317.2c0,39.8,32.4,72.2,72.2,72.2h212.4c39.8,0,72.2-32.4,72.2-72.2V97H446c7.5,0,13.5-6,13.5-13.5
		S453.5,70,446,70z M168.6,53.5c0-14.6,11.9-26.5,26.5-26.5h96.2c14.6,0,26.5,11.9,26.5,26.5V70H168.6V53.5z M394.6,414.2
		c0,24.9-20.3,45.2-45.2,45.2H137c-24.9,0-45.2-20.3-45.2-45.2V97h302.9v317.2H394.6z"/>
	<path d="M243.2,411c7.5,0,13.5-6,13.5-13.5V158.9c0-7.5-6-13.5-13.5-13.5s-13.5,6-13.5,13.5v238.5
		C229.7,404.9,235.7,411,243.2,411z"/>
	<path d="M155.1,396.1c7.5,0,13.5-6,13.5-13.5V173.7c0-7.5-6-13.5-13.5-13.5s-13.5,6-13.5,13.5v208.9
		C141.6,390.1,147.7,396.1,155.1,396.1z"/>
	<path d="M331.3,396.1c7.5,0,13.5-6,13.5-13.5V173.7c0-7.5-6-13.5-13.5-13.5s-13.5,6-13.5,13.5v208.9
			C317.8,390.1,323.8,396.1,331.3,396.1z"/>
</svg>

		</div>`,
		data: function(){
			return {
				likeAnimationPlaying: false,
				time: ""
			}
		},
		methods: {
			likeClick: function(){
				if(postsApp.loggedIn)
					this.likeAnimationPlaying = !this.liked;
			},
			updateTime: function(){
				var now = Math.floor(Utils.getUTCNow() / 1000);
				this.time = Utils.convertSecondsToTime(now - Math.floor(new Date(this.$props.post.date) / 1000));
			},
			doPost: function(){
				if(!postsApp.loggedIn){
					loginApp.open();
					return;
				}

				navbarApp.openPostBox(Mode.REPLY, this.$props.post);

				window.scroll({
					top: this.$refs.post.offsetTop - 24,
					behavior: "smooth"
				});
			},
			onImageLoadError: function(){
				this.$refs.image.parentNode.removeChild(this.$refs.image); // remove the image if it couldn't be loaded
			}
		},
		created: function() {
			setInterval(this.updateTime, 1000);
			this.updateTime();
		}
});