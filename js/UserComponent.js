Vue.component('user-profile', {
    props: ['user'],
    template: `
		<a :href='"profile.php?id=" + user.id' class="pige" style="display: block">
			<div class="pigeMain">
				<div class="profile-picture">
					<img class="profile-picture-default" :src="user.ppUrl" onload="this.className = this.offsetWidth > this.offsetHeight ? 'wider' : 'higher';" onerror="Utils.loadDefaultProfilePicture(this)">
				</div>
				<div class="text">
					<div class="textHeader">
						<span class="displayName" v-html="user.displayName"></span>
						<span class="userName">@{{ user.userName }}</span>
					</div>
					<span class="content" v-html="user.bio"></span>
				</div>
			</div>
		</a>`
});