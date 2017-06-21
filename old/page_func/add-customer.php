<?php
include_once 'db_connect.php';

if (!empty($_POST['appointedtime']) && !empty($_POST['customerphone']) && !empty($_POST['customername']) && !empty($_POST['customeraddress']) && !empty($_POST['jobdescription'])) {
$newTime = $_POST['appointedtime'];
$newPhone = $_POST['customerphone'];
$newName = $_POST['customername'];
$newAdd = $_POST['customeraddress'];
$newDescript = $_POST['jobdescription'];
$newTimeDay = $_POST['aMpM'];
if (!empty($_POST['customerEmail'])) { $newEmail = $_POST['customerEmail']; }
else { $newEmail = ''; }

if ($newTimeDay === "PM") {
$tHour = substr($newTime, 0, -3);
$tHour = intval($tHour);
$newTime = '';
$newTime = $tHour + 12;
$newTime.= ":00";}
$newTime.= ":00";

$colCount = $mysqli->query("SELECT countColumn FROM columnCount");
$colCount2 = $colCount->fetch_array();
$columnCount = $colCount2['countColumn'];
$customerQuery = "INSERT INTO customer_jobs VALUES (NULL, '".$newName."', '".$newAdd."', '".$newPhone."', '".$newEmail."')";
$addworkjob = $mysqli->query($customerQuery);
$IDQuery = "SELECT customerID FROM customer_jobs WHERE customerName = '".$newName."' AND customerAddress = '".$newAdd."'";
$custID = $mysqli->query($IDQuery);
$newCustomer = $custID->fetch_array();
$customer = $newCustomer['customerID'];
$entryQuery = "INSERT INTO active_jobs VALUES (".$columnCount.", '".$newTime."', '".$newAdd."', '".$newDescript."', ".$customer.", 0, 0)";
if($updateList = $mysqli->query($entryQuery)) {
$columnCount++;
$updateColumn = $mysqli->query("UPDATE columnCount SET countColumn = ".$columnCount);
header('Location: /pages/jobAssign.php');
} else { printf ($mysqli->error); }
} else {echo "Please enter the required information";}
?>