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

$is_post = true;

// check that the post belongs to the user
$stmt = $mysqli->prepare("SELECT * FROM posts WHERE posts.id = ? AND posts.userId = \"$userId\"");
$stmt->bind_param('s', $postId);
$stmt->execute();

$result = $stmt->get_result();

// post might be a reply
if(!$result || $result->num_rows === 0){

    $stmt = $mysqli->prepare("SELECT * FROM replies WHERE replies.id = ? AND replies.userId = \"$userId\"");
    $stmt->bind_param('s', $postId);
    $stmt->execute();

    $result = $stmt->get_result();

    if(!$result || $result->num_rows === 0){
        http_response_code(401);
        return;
    }

    $is_post = false;;
}

// delete all likes given to this post or replies to this post
$stmtLikes = $mysqli->prepare('DELETE FROM likes WHERE id = ? OR id IN (SELECT id FROM replies WHERE replyId = ?)');
$stmtLikes->bind_param('ss', $postId, $postId);

if(!$stmtLikes->execute()){
    http_response_code(401);
    return;
}

if($is_post){
    // delete the post and all associated replies to the post
    $stmt = $mysqli->prepare('DELETE posts, replies FROM posts LEFT JOIN replies ON posts.id = replies.replyId WHERE posts.id = ?');
}else{
    $stmt = $mysqli->prepare('DELETE FROM replies WHERE id = ?');
}
$stmt->bind_param('s', $postId);

if(!$stmt->execute()){
    http_response_code(401);
    return;
}

?>