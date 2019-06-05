<?php
	session_start();

    $mysqli = new MySQLi('127.0.0.1', '', '', 'piger');

    include 'server/Utils.php';
    $total_users = Utils::getTotalUserCount($mysqli);

    $is_limited = true;
?>
<!DOCTYPE html>
<html encoding="UTF-8">
<head>
	<title>Suche - Piger</title>

	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran:600,700" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="css/core.css">
	<link rel="stylesheet" type="text/css" href="css/pige.css">
    <link rel="stylesheet" type="text/css" href="css/piges.css">
    <link rel="stylesheet" type="text/css" href="css/search.css">

	<script src="https://cdn.jsdelivr.net/npm/vue"></script>
</head>

<body>
    <?php include Utils::isLoggedIn() ? 'navbar.php' : 'navbar_guest.php' ?>
    <div id="app">
	    <div id="welcome">
            <svg id="backBtn" version="1.1" id="backBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 309.143 309.143" xml:space="preserve" onclick="Utils.goBack()">
		        <path d="M112.855,154.571L240.481,26.946c2.929-2.929,2.929-7.678,0-10.606L226.339,2.197 C224.933,0.79,223.025,0,221.036,0c-1.989,0-3.897,0.79-5.303,2.197L68.661,149.268c-2.929,2.929-2.929,7.678,0,10.606 l147.071,147.071c1.406,1.407,3.314,2.197,5.303,2.197c1.989,0,3.897-0.79,5.303-2.197l14.142-14.143 c2.929-2.929,2.929-7.678,0-10.606L112.855,154.571z"/>
	        </svg>
            <div>
                <h3>Suchen</h3>
                <div id="userNameTtl">Username</div>
                <input id="searchInput" type="text" maxlength="16" v-model="search">
                <button :class="{ pending: isSearching }" @click="startSearch" style="margin-bottom: 14px">Suchen</button>
                <div style="color: var(--light); font-size: 12px">Suche aus insgesamt <?php echo $total_users ?> Nutzer<?php if($total_users > 1) echo 'n'?>.</div>
                <p class="error" :class="{ visible: this.foundUsers.length === 0 && this.hasSearchedOnce }">Es konnten keine Ergebnisse gefunden werden.</p>
            </div>
        </div>
	    <div id="piges">
            <div class="pigeList" v-for="user in foundUsers">
                <user-profile :user="user"></user-profile>
            </div>
    
            <div class="skeletonPigeWrapper" v-if="isSearching">
                <div class="pigeList" v-for="index in 8">
                    <div class="skeletonPige pige pending">
	    	        	<div class="pigeMain">
	    	        		<div class="profile-picture"></div>
	    	        		<div class="text"></div>
	    	        	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="js/UserComponent.js"></script>
<script>
    var searchApp = new Vue({
        el: "#app",
        data: {
            search: "",
            isSearching: false,
            foundUsers: [],
            searchIndex: 0,
            allResults: true,
            hasSearchedOnce: false
        },
        methods: {
            startSearch: function(){
                this.isSearching = true;
                this.searchIndex = 0;
                this.allResults = false;

                Network.search(this.search, this.searchIndex)
                .then(result => {
                    if(!this.hasSearchedOnce)
                        this.hasSearchedOnce = true;

                    this.foundUsers = result === false ? [] : result; // fallback when the server returned an error
                    this.isSearching = false;
                });
            }
        }
    });

    document.addEventListener("keyup", ev => {
        if(ev.key.toLowerCase() === "enter" && document.activeElement.id === "searchInput")
            searchApp.startSearch();
    });

    document.body.onscroll = () => {
        if(searchApp.allResults)
            return;
        
		if(window.scrollY + window.innerHeight * 1.5 > document.documentElement.offsetHeight){
            searchApp.searchIndex += 32;

            Network.search(searchApp.search, searchApp.searchIndex)
            .then(result => {
                if(result.length === 0){
                    searchApp.allResults = true;
                    return;
                }

                searchApp.foundUsers = searchApp.foundUsers.concat(result);
            });
		}
	}
</script>
</html>