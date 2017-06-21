<?php
include_once 'db_connect.php';

$declineID = $_GET['declineID'];
$resortQ = "SELECT appointmentTime, jobLocation, jobType, customerID FROM accepted_jobs WHERE jobsID = ".$declineID;
if ($startSort = $mysqli->query($resortQ)) {
$reSort = $startSort->fetch_array();
$appointT = $reSort['appointmentTime'];
$jobLoc = $reSort['jobLocation'];
$jobT = $reSort['jobType'];
$cID = $reSort['customerID'];
$reInsertQ = "INSERT INTO active_jobs VALUES (".$declineID.", '".$appointT."', '".$jobLoc."', '".$jobT."', ".$cID.", 0, 0)";
if ($reInsertJob = $mysqli->query($reInsertQ)) {
$delActive = $mysqli->query("DELETE FROM accepted_jobs WHERE jobsID = ".$declineID." LIMIT 1");
header('Location: /pages/jobAssign.php');
} else { echo "ERROR 2:"; printf ($mysqli->error); }
} else { echo "ERROR 1:"; printf ($mysqli->error); }
?>
