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

$stmt = $mysqli->prepare("DELETE FROM likes WHERE id = ? AND userId = \"$userId\"");
$stmt->bind_param('s', $postId);

$res = $stmt->execute();

if(!$res)
    http_response_code(401);
?>