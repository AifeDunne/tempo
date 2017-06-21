<?php
include_once 'db_connect.php';

$declineID = $_GET['declineID'];
$declineQuery = "UPDATE active_jobs SET assigned = 0, employeeID = 0 WHERE jobsID = ".$declineID;
if($declineJobs = $mysqli->query($declineQuery)) {
header('Location: /pages/jobAssign.php');
}
?>