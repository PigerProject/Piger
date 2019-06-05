<?php
class Utils {

	public static function checkInputsInvalid(...$inputs){
		foreach($inputs as $input){

			// check if input is an array (possibly because of "Parameter Tampering" attempt)
			if(!is_string($input))
				return true;
		}

		return false;
	}

	public static function getTotalUserCount($mysqli){
		$query = $mysqli->query('SELECT * FROM users');
		$userCount = $query->num_rows;

		return $userCount;
	}

	public static function getTotalPostsCount($mysqli){
		$query = $mysqli->query('SELECT * FROM posts');
		$postCount = $query->num_rows;

		return $postCount;
	}

	public static function getTotalRepliesCount($mysqli){
		$query = $mysqli->query('SELECT * FROM replies');
		$repliesCount = $query->num_rows;

		return $repliesCount;
	}

	public static function getTotalLikesCount($mysqli){
		$query = $mysqli->query('SELECT * FROM likes');
		$likesCount = $query->num_rows;

		return $likesCount;
	}

	public static function generatePassword($password){
		$options = ['cost' => 9];

		return password_hash($password, PASSWORD_DEFAULT, $options);
	}

	public static function isLoggedIn(){
		return isset($_SESSION['id']);
	}

	public static function getTotalPostsCountOfUser($mysqli, $id){
		return $mysqli->query("SELECT * FROM posts WHERE userId = \"$id\"")->num_rows;
	}

	public static function getTotalRepliesCountOfUser($mysqli, $id){
		$res = $mysqli->query("SELECT * FROM replies WHERE userId = \"$id\"");
		if(!$res)
			return 0;
		return $res->num_rows;
	}

	public static function getTotalLikesCountOfUser($mysqli, $id){
		$stmt = $mysqli->prepare('SELECT * FROM likes WHERE id IN (SELECT id FROM posts WHERE userId = ?) OR id IN (SELECT id FROM replies WHERE userId = ?)');
		$stmt->bind_param('ss', $id, $id);
		$stmt->execute();

		$res = $stmt->get_result();
		if(!$res)
			return 0;
		return $res->num_rows;
	}

	public static function monthToGerman($month){
		$months = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
		return $months[$month - 1];
	}

	public static function getPostDataForUser($mysqli, $id){
		$userResult = $mysqli->query("SELECT username, displayname, pp_url FROM users WHERE id = \"$id\"");
		$userRow = $userResult->fetch_assoc();

		return ['user' => ['id' => $id, 'displayName' => $userRow['displayname'], 'userName' => $userRow['username'], 'ppUrl' => $userRow['pp_url']]];
	}

	public static function createPostList($posts){
		return [$posts];
	}

	public static function createPostData($postId, $message, $likes, $liked, $user, $date, $imgUrl){
		$post = ['id' => $postId, 'message' => $message,'likes' => $likes, 'liked' => $liked, 'date' => $date, 'imgUrl' => $imgUrl];

		// add the user data to the $post array
		$post = array_merge($post, $user);

		return $post;
	}

	public static function getRepliesByPostId($mysqli, $postId){
		$replies = [];

		$res = $mysqli->query("SELECT * FROM replies WHERE replyId = \"$postId\"");

		while($row = $res->fetch_assoc()){
			$user = Utils::getPostDataForUser($mysqli, $row['userId']);

			$replyId = $row['id'];
			$likes = $mysqli->query("SELECT * FROM likes WHERE id = \"$replyId\"");
			$likes = $likes ? $likes->num_rows : 0;

			$liked = false;

			if(Utils::isLoggedIn()){
				$clientId = $_SESSION['id'];

				$liked = $mysqli->query("SELECT * FROM likes WHERE id = \"$replyId\" AND userId = \"$clientId\"");
				$liked = $liked && $liked->num_rows > 0;
			}

			$reply = Utils::createPostData($replyId, $row['message'], $likes, $liked, $user, $row['date'], '');

			$replies[] = $reply;
		}

		return $replies;
	}
}
?>