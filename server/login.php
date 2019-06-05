<?php
include 'Utils.php';

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if(Utils::checkInputsInvalid($username, $password)){
	http_response_code(401);
	return;
}

$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');
$stmt = $mysqli->prepare('SELECT password, id FROM users WHERE username = ?');
$stmt->bind_param('s', $username);

$success = $stmt->execute();

// query was not successful
if(!$success){
	http_response_code(401);
	return;
}

$res = $stmt->get_result();
$row = $res->fetch_assoc();

if(password_verify($password, $row['password'])){
	session_start();
	$_SESSION['id'] = $row['id'];
	http_response_code(200);
}else{
	http_response_code(401);
}
?>