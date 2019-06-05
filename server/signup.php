<?php
header('Content-Type: application/json');

include 'Utils.php';

// username error codes:
// 1 = username taken
// 2 = too long
// 3 = invalid
// 4 = none provided

// password error codes:
// 1 = too short
// 2 = too long
// 3 = invalid
// 4 = none provided

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if(Utils::checkInputsInvalid($username, $password)){
	http_response_code(401);
	return;
}

// username or password contain invalid line breaks
if(strpos($username, PHP_EOL) !== false || strpos($password, PHP_EOL) !== false){
	http_response_code(401);
	return;
}

$username_error_code = 0;
$password_error_code = 0;

$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');

checkUsername();
checkPassword();

// username and password are valid, user can be added to the database
if($username_error_code === 0 && $password_error_code === 0){

	$id = uniqid();

	$passwordHash = Utils::generatePassword($password);
	$date = date('Y-m-d');

	$prep = $mysqli->prepare('INSERT INTO users (id, username, displayname, password, registerdate) VALUES (?, ?, ?, ?, ?)');


	$prep->bind_param('sssss', $id, $username, $username, $passwordHash, $date);
	$success = $prep->execute();

	// check if the query was successful, if not, then respond with status code 500
	if(!$success){
		http_response_code(500);
	}else{
		session_start();
		$_SESSION['id'] = $id;
	}
}

// respond

$response = ['username_error_code' => $username_error_code, 'password_error_code' => $password_error_code];
echo json_encode($response);

function checkUsername(){
	global $username, $username_error_code, $mysqli;

	// check if not set or empty
	if(strlen($username) === 0){
		$username_error_code = 4;
		return;
	}

	// check if too long
	if(strlen($username) > 16){
		$username_error_code = 2;
		return;
	}

	if(!ctype_alnum($username)){
		$username_error_code = 3;
		return;
	}

	$prep = $mysqli->prepare('SELECT * FROM users WHERE username = ?');
	echo $mysqli->error;
	$prep->bind_param('s', $username);
	$success = $prep->execute();

	if(!$success){
		http_response_code(500);
		return;
	}

	$res = $prep->get_result();

	// check if table has more than 0 rows having the requested username as their username column
	if($res->num_rows > 0){
		$username_error_code = 1;
		return;
	}
}

function checkPassword(){
	global $password, $password_error_code;

	// check if not set or empty
	if(strlen($password) === 0) {
		$password_error_code = 4;
		return;
	}

	// check if too short
	if(strlen($password) < 4){
		$password_error_code = 1;
		return;
	}

	// check if too long
	if(strlen($password) > 32){
		$password_error_code = 2;
		return;
	}

	if(!mb_detect_encoding($password, 'UTF-8', true)){
		$password_error_code = 3;
		return;
	}
}

?>