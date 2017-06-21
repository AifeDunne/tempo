<?php
include_once 'db_connect.php';

$unassign = $_GET['pJobID'];
$empNum = $_GET['thisEmp'];
$unassignID = $mysqli->query("UPDATE accepted_jobs SET primary_job = 0 WHERE jobsID = ".$unassign." AND employeeID = ".$empNum);
if($checkSafe = $mysqli->query("SELECT safetyID, orderID FROM check_list
INNER JOIN parts_order ON parts_order.jobsID = check_list.jobsID
WHERE check_list.jobsID = ".$unassign)) {
$haveSafe = $checkSafe->fetch_array();
$safeID = $haveSafe['safetyID'];
$orderID = $haveSafe['orderID'];
if (!empty($safeID)) { $removeSafe = $mysqli->query("DELETE FROM check_list WHERE jobsID = ".$unassign." LIMIT 1"); }
if (!empty($orderID)) { $removeOrder = $mysqli->query("DELETE FROM parts_order WHERE jobsID = ".$unassign." LIMIT 1"); }
header('Location: /protected_page.php');
} else {
printf ($mysqli->error);
}
?>