<?php
header('Content-Type: application/json');

session_start();

include 'Utils.php';

$username = isset($_GET['username']) ? $_GET['username'] : '';
$search_index = isset($_GET['searchIndex']) ? $_GET['searchIndex'] : '';

if(Utils::checkInputsInvalid($username, $search_index)){
    http_response_code(401);
    return;
}

$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');

$username .= '%';
$stmt = $mysqli->prepare('SELECT * FROM users WHERE LOWER(username) LIKE LOWER(?) ORDER BY LENGTH(username) LIMIT ?, 32');
$stmt->bind_param('si', $username, $search_index);
if(!$stmt->execute()){
    http_response_code(401);
    return;
}

$res = $stmt->get_result();

$users = [];

while($row = $res->fetch_assoc()){
    $users[] = ['id' => $row['id'], 'userName' => $row['username'], 'displayName' => $row['displayname'], 'bio' => $row['bio'], 'ppUrl' => $row['pp_url']];
}

echo json_encode($users);
?>