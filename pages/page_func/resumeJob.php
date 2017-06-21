<?php
include_once 'db_connect.php';

if (isset($_POST['appointedtimeNew'])) {
$newAppnt = $_POST['appointedtimeNew'];
$getTType = $_POST['aMpMNew'];
$insertBack = $_GET['resumeJob'];
if ($getTType === "PM") {
$gHour = substr($newAppnt, 0, -3);
$gHour = intval($gHour);
$newAppnt = '';
$newAppnt = $gHour + 12;
$newAppnt.= ":00";}
$newAppnt.= ":00";
$switchQ = "UPDATE complete_jobs SET reassigned = 1 WHERE jobsID = ".$insertBack;
if ($switchOff = $mysqli->query($switchQ)) {
$allFQ = "SELECT customerID, jobLocation, jobType FROM complete_jobs WHERE jobsID = ".$insertBack;
$allFR = $mysqli->query($allFQ);
$allFacts = $allFR->fetch_array();
$customer = $allFacts['customerID'];
$jobLoc = $allFacts['jobLocation'];
$jobType = $allFacts['jobType'];
$updateJobs = "INSERT INTO active_jobs VALUES (".$insertBack.",'".$newAppnt."','".$jobLoc."','".$jobType."',".$customer.",0,0)";
if($getJobDeets = $mysqli->query($updateJobs)) {
header('Location: /protected_page2.php'); }
else { echo "ERROR2: "; printf ($mysqli->error); }
} else { echo "ERROR1: "; printf ($mysqli->error); }
} else { echo "You must enter a time"; }
?>