<?php
include_once 'db_connect.php';

if (isset($_GET['acceptID'])) {
$thisJob = $_GET['acceptID'];
if ($changeData = $mysqli->query("SELECT appointmentTime, jobLocation, jobType, customerID, employeeID FROM active_jobs WHERE jobsID=".$thisJob)) {
$getArray = $changeData->fetch_array();
$gTime = $getArray['appointmentTime'];
$gLoc = $getArray['jobLocation'];
$gType = $getArray['jobType'];
$gCustomer = $getArray['customerID'];
$gEmployee = $getArray['employeeID'];
$acceptQuery = "INSERT INTO accepted_jobs VALUES (".$thisJob.", '".$gTime."', '".$gLoc."', '".$gType."', '".$gCustomer."', '".$gEmployee."', 0)";
if($updateDB = $mysqli->query($acceptQuery)) {
$removeRow = $mysqli->query("DELETE FROM active_jobs WHERE jobsID = ".$thisJob." LIMIT 1");
header('Location: /protected_page.php');
		} else { printf ($mysqli->error); }
	}
}
?>
