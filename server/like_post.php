<?php
include 'Utils.php';

session_start();

// check if user is logged in and that an ID is provided
if(!Utils::isLoggedIn() || !isset($_POST['id'])){
    http_response_code(401);
    return;
}

$postId = $_POST['id'];
$userId = $_SESSION['id'];

if(Utils::checkInputsInvalid($postId)){
    http_response_code(401);
    return;
}

$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');

// check if user already likes this post
$res = $mysqli->query("SELECT * FROM likes WHERE id = \"$postId\" AND userId = \"$userId\"");
if(!$res || $res->num_rows > 0){
    http_response_code(401);
    return;
}

$stmt = $mysqli->prepare("INSERT INTO likes (id, userId) VALUES(?, \"$userId\")");
$stmt->bind_param('s', $postId);

$res = $stmt->execute();


if(!$res)
    http_response_code(401);
?>