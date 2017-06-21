<?php
include_once 'db_connect.php';

$deleteID = $_GET['deleteID'];
$removeJ = "DELETE FROM active_jobs WHERE jobsID = ".$deleteID." LIMIT 1";
if($removeJob = $mysqli->query($removeJ)) {
header('Location: /pages/jobAssign.php');
}
?>