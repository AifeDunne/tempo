<?php
include_once 'db_connect.php';

$Assign = $_SESSION['assign'];
$JobAdd = $_SESSION['jobArray'];
$PrimaryAdd = $_SESSION['makeprimary'];
$countList = 0;
foreach ($JobAdd as $jobSeek) {
if($jobSeek != 0) {
if($PrimaryAdd[$countList] === 'yes') {
if ($changeData = $mysqli->query("SELECT appointmentTime, jobLocation, jobType, customerID FROM active_jobs WHERE jobsID=".$Assign[$countList])) {
$getArray = $changeData->fetch_array();
$gTime = $getArray['appointmentTime'];
$gLoc = $getArray['jobLocation'];
$gType = $getArray['jobType'];
$gCustomer = $getArray['customerID'];
$updateDB = $mysqli->query("INSERT INTO accepted_jobs VALUES (".$Assign[$countList].", '".$gTime."', '".$gLoc."', '".$gType."', ".$gCustomer.", ".$jobSeek.", 1)");
$acceptDB = $mysqli->query("UPDATE members SET busy = 1 WHERE id = ".$jobSeek);
$removeRow = $mysqli->query("DELETE FROM active_jobs WHERE jobsID = ".$Assign[$countList]." LIMIT 1");
	}
} else {$addJobs = $mysqli->query("UPDATE active_jobs SET assigned = 1, employeeID = ".$jobSeek." WHERE jobsID = ".$Assign[$countList]);}
	}
$countList++;
}
?>