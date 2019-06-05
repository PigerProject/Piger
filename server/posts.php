<?php
header('Content-Type: application/json');

session_start();

include 'Utils.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';
$index = isset($_GET['index']) ? $_GET['index'] : '';

if(Utils::checkInputsInvalid($id, $index)){
    http_response_code(401);
    return;
}

$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');

$limitIndex = (strlen($index) === 0) ? 0 : intval($index);

// check if posts by a certain user should be downloaded only
if(strlen($id) === 0){
    // download all recent posts
    $stmt = $mysqli->prepare('SELECT * FROM posts ORDER BY date DESC LIMIT ?, 32'); // only download 32 posts at once
    $stmt->bind_param('i', $limitIndex);
}else{
    // fetches all posts which are either from that user or contain a reply from that user
    $stmt = $mysqli->prepare('SELECT * FROM posts WHERE posts.userId = ? OR posts.id IN (SELECT replyId FROM replies WHERE replies.userId = ?) ORDER BY date DESC LIMIT ?, 32'); // only download 32 posts at once
    $stmt->bind_param('ssi', $id, $id, $limitIndex);
}

$stmt->execute();
$result = $stmt->get_result();

$posts = [];

if(!$result){
    http_response_code(401);
    return;
}

while($row = $result->fetch_assoc()){
    $postId = $row['id'];
    $userId = $row['userId'];
    $message = $row['message'];
    $date = $row['date'];
    $imgUrl = $row['img_url'];

    $postList = [];

    // fetch likes of this particular post
    $res = $mysqli->query("SELECT * FROM likes WHERE id = \"$postId\"");

    if(!$res){
        http_response_code(401);
        return;
    }

    $likes = $res->num_rows;

    // adding the user data to the post array
    $user = Utils::getPostDataForUser($mysqli, $userId);

    $liked = false;
    // check if the client likes this post
    if(Utils::isLoggedIn()){
        $clientId = $_SESSION['id'];
        $likedRes = $mysqli->query("SELECT * FROM likes WHERE id = \"$postId\" AND userId = \"$clientId\"");
        $liked = $likedRes && $likedRes->num_rows > 0;
    }

    // create the post array for the user's post
    $userPost = Utils::createPostData($postId, $message, $likes, $liked, $user, $date, $imgUrl);
    $postList[] = $userPost;

    // get all replies of the post and add them to the list
    $postList = array_merge($postList, Utils::getRepliesByPostId($mysqli, $postId));

    // add the post list to the array of all posts (or post lists)
    $posts[] = $postList;
}

$posts = json_encode($posts);

// output data
echo $posts;
?>