<?php
include 'Utils.php';

session_start();

// user is not logged in
if(!Utils::isLoggedIn()){
	http_response_code(401);
	return;
}

$id = $_SESSION['id'];

$displayname = isset($_POST['displayName']) ? htmlentities($_POST['displayName']) : '';
$bio = isset($_POST['bio']) ? htmlentities($_POST['bio']) : '';
$pp_url = isset($_POST['ppUrl']) ? $_POST['ppUrl'] : '';

if(Utils::checkInputsInvalid($displayname, $bio, $pp_url)){
	http_response_code(401);
	return;
}

if(checkInvalidLength()){
	http_response_code(401);
	return;
}

function checkInvalidLength(){
	global $displayname, $bio, $pp_url;

	if(strlen($displayname) > 16)
		return true;

	if(strlen($bio) > 180)
		return true;

	if(strlen($pp_url) > 256)
		return true;

	return false;
}

$mysqli = new MySQLi('127.0.0.1', '', '', 'piger');
$stmt = $mysqli->prepare("UPDATE users SET displayname = ?, bio = ?, pp_url = ? WHERE id = '$id'");
$stmt->bind_param('sss', $displayname, $bio, $pp_url);

$success = $stmt->execute();

if(!$success)
	http_response_code(401);
?>