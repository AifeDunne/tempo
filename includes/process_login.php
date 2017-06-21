<?php
error_reporting(E_ALL);
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start();
if (isset($_POST['username'], $_POST['p'])) {
    $password = $_POST['p'];
	$username = $_POST['username'];
	echo $username."<br>";
	echo $password."<br>";
	$isTrue = login($username, $password, $mysqli);
	echo $isTrue;
    if ($isTrue === true) {
		echo "SUCCESS";
		$privGroup = $mysqli->query("SELECT userLevel FROM members WHERE username = '".$username."'");
		echo $privGroup
		$_SESSION['userPriv'] = $privGroup;
		header('Location: ../index.php');
    } else { echo "Incorrect Password"; }
} else {
    echo 'Invalid Request';
}