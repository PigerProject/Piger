<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST')
	exit();

session_start();
if(!isset($_SESSION['id']))
	exit();

$id = $_SESSION['id'];
$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');

// statement explanation:
// first line: selects all databases where date of this user could be stored in
// second line: deletes all posts posted by this user
// third line: deletes all replies either made by this user or replies to posts made by the user
// fourth line: deletes the user from the users database

$stmt = $mysqli->prepare('DELETE users, replies, posts, likes FROM users
LEFT JOIN posts ON posts.userId = ?
LEFT JOIN replies ON replies.userId = ? OR replies.replyId = posts.id
LEFT JOIN likes ON likes.userId = ? OR likes.id = posts.id OR likes.id = replies.id
WHERE users.id = ?');

$stmt->bind_param('sss', $id, $id, $id);

if($stmt->execute()){
	http_response_code(200);
	session_destroy();
}else
	http_response_code(401);
?>
