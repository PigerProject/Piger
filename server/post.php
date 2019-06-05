<?php
header('Content-Type: application/text');
session_start();

include 'Utils.php';

// check if user is logged in and that the message is not empty or not set
if(!Utils::isLoggedIn() || !isset($_POST['message'])){
    http_response_code(401);
    return;
}

$message = isset($_POST['message']) ? $_POST['message'] : '';
$replyId = isset($_POST['replyId']) ? $_POST['replyId'] : '';
$imgUrl = isset($_POST['imgUrl']) ? $_POST['imgUrl'] : '';

if(Utils::checkInputsInvalid($message, $replyId, $imgUrl)){
    http_response_code(401);
    return;
}

// check if message and image URL is not longer than the limit
if(strlen($message) > 180 || strlen($imgUrl) > 256){
    http_response_code(401);
    return;
}

// replace HTML characters
$message = htmlentities($message);
// source: https://stackoverflow.com/a/10757755
// only replace line breaks with html characters
$message = preg_replace('/\r|\n/', '<br>', $message);

$id = uniqid();
$userId = $_SESSION['id'];
$date = date('Y-m-d H:i:s');

$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');

$date = gmdate('Y-m-d H:i:s');

// the request was a request to reply to a post as it contains a non-empty replyId
if(strlen($replyId) === 0){

    $stmt = $mysqli->prepare('INSERT INTO posts (id, userId, message, date, img_url) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $id, $userId, $message, $date, $imgUrl);
}else{

    // images are only allowed for main posts
    if(strlen($imgUrl) > 0){
        http_response_code(401);
        return;
    }
    // check if a post with such a reply ID even exists and if that post is really a post and not a reply
    $stmt = $mysqli->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->bind_param('s', $replyId);
    $stmt->execute();
    $res = $stmt->get_result();

    // post could not be queried (probably could not be found)
    if($res->num_rows === 0){
        http_response_code(401);
        return;
    }else{
        // post could be found, but it is a reply as it has a reply ID
        if(strlen($res->fetch_assoc()['replyId']) > 0){
            http_response_code(401);
            return;
        }
    }

    $stmt = $mysqli->prepare('INSERT INTO replies (id, userId, message, date, replyId) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $id, $userId, $message, $date, $replyId);
}

if(!$stmt->execute()){
    http_response_code(401);
    return;
}

echo $id;
?>