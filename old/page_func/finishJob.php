<?php
include_once 'db_connect.php';

$changeID = $_GET['lastID'];
$uEmployee = $_GET['empID'];
$changeIVoice = "UPDATE invoice_list SET jobDone = 1 WHERE jobsID = ".$changeID;
$changeCJob = "UPDATE complete_jobs SET reassigned = 0, finished = 1 WHERE jobsID = ".$changeID;
$removeAC =  "DELETE FROM accepted_jobs WHERE jobsID = ".$changeID." LIMIT 1";
$upMember = "UPDATE members SET busy = 0 WHERE id = ".$uEmployee;
if ($changeJob = $mysqli->query($changeCJob)) {
$changeInvoice = $mysqli->query($changeIVoice);
$changeAccepts = $mysqli->query($removeAC);
if ($changeMember = $mysqli->query($upMember)) {
header('Location: /protected_page.php');
	}
} else { printf ($mysqli->error); }
?>