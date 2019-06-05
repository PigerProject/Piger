var postsApp = new Vue({
    el: "#piges",
    data: {
        posts: [],
        isDownloading: false,
        loggedIn: clientId !== "",
        lastFetchTime: 0,
        hasLoadedAllPosts: false,
        olderPostsLoadIndex: 0
    },
    methods: {
        loadPostsOfUser: function(userId){
            this.isDownloading = true;
            Network.getPostsByUser(userId)
            .then(postsJson => {
                postsJson = Utils.initDownloadedPosts(postsJson, clientId);
                // add the downloaded posts to the posts array
                this.posts = postsJson.concat(Utils.initDownloadedPosts(this.posts, clientId));
                this.handleDownload(postsJson);
                this.isDownloading = false;
            });
        },
        loadOlderPostsOfUser: function(userId){
            if(this.hasLoadedAllPosts)
                return;
            
            this.isDownloading = true;

            this.olderPostsLoadIndex += 32;
        
            Network.getOlderPostsByUser(userId, this.olderPostsLoadIndex)
            .then(postsJson => {
                if(postsJson.length === 0)
                    this.hasLoadedAllPosts = true;
                
                postsJson = Utils.initDownloadedPosts(postsJson, clientId);
                this.posts = this.posts.concat(Utils.initDownloadedPosts(postsJson, clientId));
                this.handleDownload(postsJson);
                this.isDownloading = false;
            });
        },
        loadOlderPosts: function(){
            if(this.hasLoadedAllPosts)
                return;

            this.isDownloading = true;
            
            Network.getOlderPosts(this.olderPostsLoadIndex)
            .then(postsJson => {
                if(postsJson.length === 0)
                    this.hasLoadedAllPosts = true;
                
                postsJson = Utils.initDownloadedPosts(postsJson, clientId);
                this.posts = this.posts.concat(Utils.initDownloadedPosts(postsJson, clientId));
                this.handleDownload(postsJson);
                this.isDownloading = false;
            });

            this.olderPostsLoadIndex += 32;
        },
        like: function(post){
            if(!this.loggedIn){
                loginApp.open();
                return;
            }
            
            post.liked = !post.liked;

            if(post.liked){
                post.likes++;

                Network.likePost(post.id)
                .then(success => {
                    // fire an event for the post like
                    var likeEvent = new CustomEvent("pigerpostlike", {
                        detail: {
                            post
                        }
                    });
                    document.dispatchEvent(likeEvent);
                });
            }else{
                post.likes--;
                Network.unlikePost(post.id)
                .then(success => {
                    // fire an event for the post unlike
                    var unlikeEvent = new CustomEvent("pigerpostunlike", {
                        detail: {
                            post
                        }
                    });
                    document.dispatchEvent(unlikeEvent);
                });
            }
        },
        delete: function(post){
            var post = this.getPostById(post.id);
            post.pending = true;
            Network.deletePost(post.id)
            .then(success => {
                if(success){
                    this.removePost(post);
                }else{
                    errorApp.showError("Dein Post konnte nicht gelöscht werden. Bitte versuche es erneut.");
                    post.pending = false;
                }
            });
        },
        removePost: function(post){
            // get the list of this post
            var list = this.getListById(post.id);
            var listIndex = list.indexOf(post);
            var isMainPost = listIndex === 0;

            // check if the post was a reply and the only/last reply made by the user in that list, if so remove the entire list
            var mustDeleteList = false;
            if(!location.pathname.includes("piges.php") && !isMainPost && list[0].user.id !== userId){ // make sure the main post is not from the user
                var otherRepliesByUser = [...list];
                otherRepliesByUser.shift(); // remove the main post
                var replyLength = otherRepliesByUser.filter((replyPost, index) => replyPost.user.id === userId).length - 1;
                mustDeleteList = replyLength < 1;
            }
            
            if(isMainPost || mustDeleteList){

                // go through all posts of this post list to trigger necessary events (such as pigerpostdelete)

                for(let i = 1; i < list.length; i++){
                    var deleteEvent = new CustomEvent("pigerpostdelete", {
                        detail: {
                            post: list[i],
                            isReply: !isMainPost
                        }
                    });
                    document.dispatchEvent(deleteEvent);
                }

                this.posts.splice(this.posts.indexOf(list), 1); // delete the entire postList
            }else{
                list.splice(listIndex, 1); // only delete the post
            }

            // fire an event for the post delete
            var deleteEvent = new CustomEvent("pigerpostdelete", {
                detail: {
                    post,
                    isReply: !isMainPost
                }
            });
            document.dispatchEvent(deleteEvent);
        },
        getPostById: function(id) {
            for(let i = 0; i < this.posts.length; i++){
                for(let j = 0; j < this.posts[i].length; j++){
                    if(this.posts[i][j].id === id)
                        return this.posts[i][j];
                }
            }
        },
        getListById: function(id){
            for(let i = 0; i < this.posts.length; i++){
                for(let j = 0; j < this.posts[i].length; j++){
                    if(this.posts[i][j].id === id)
                        return this.posts[i];
                }
            }
        },
        handleDownload: function(posts){
            var count = Utils.countPostAndReplies(posts);
            this.fireDownloadEvent(count);
        },
        fireDownloadEvent: function(count){
            var downloadEvent = new CustomEvent("pigesdownloaded", {
                detail: count
            });

            document.dispatchEvent(downloadEvent);
        }
    }
});