<?php
include_once 'db_connect.php';

if (isset($_GET['primID'])) {
$finishThis = $_GET['primID'];
$finalQuery = "SELECT jobLocation, jobType, accepted_jobs.customerID, accepted_jobs.employeeID, invoiceID, jobDone FROM accepted_jobs 
INNER JOIN invoice_list ON invoice_list.jobsID = accepted_jobs.jobsID
WHERE accepted_jobs.jobsID=".$finishThis;
if ($grabFinal = $mysqli->query($finalQuery)) {
$getFinal = $grabFinal->fetch_array();
$fCustomer = $getFinal['customerID'];
$fLoc = $getFinal['jobLocation'];
$fType = $getFinal['jobType'];
$fEmployee = $getFinal['employeeID'];
$fInvoice = $getFinal['invoiceID'];
$finishValue = $getFinal['jobDone'];

$grabFOrder = $mysqli->query("SELECT listID FROM partsList WHERE jobsID = ".$finishThis);
$grabOData = $grabFOrder->fetch_array();
$fOrder = $grabOData['listID'];
if (empty($fOrder)) {$fOrder = 0;}
$grabFSafety = $mysqli->query("SELECT checkID FROM checkList WHERE jobsID = ".$finishThis);
$grabSData = $grabFSafety->fetch_array();
$fSafety = $grabSData['checkID'];
if (empty($fSafety)) {$fSafety = 0;}

$finishJob = "INSERT INTO complete_jobs VALUES (NULL, ".$finishThis.", ".$fCustomer.", '".$fLoc."', '".$fType."', ".$fEmployee.", ".$fOrder.", ".$fSafety.", ".$fInvoice.", ".$finishValue.")";
if ($fInsert = $mysqli->query($finishJob)) {
$rAccept = "DELETE FROM accepted_jobs WHERE jobsID = ".$finishThis." LIMIT 1";
if($removeAccept = $mysqli->query($rAccept)) {
$unQuery = "UPDATE members SET busy = 0 WHERE id = ".$fEmployee;
$unassignE = $mysqli->query($unQuery);
header('Location: /protected_page.php'); } else { echo "ERROR1<br>"; printf ($mysqli->error); }
		} else { echo "ERROR2<br>"; printf ($mysqli->error); }
	} else { echo "ERROR3<br>"; printf ($mysqli->error); }
}
?>