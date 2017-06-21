<?php
include_once 'db_connect.php';
	if (isset($_POST['order']) || isset($_POST['used'])) {
	$partJob = $_GET['getJob'];
	$repairM = $_GET['garageMod'];
	$queryParts = "SELECT repairOption, partsUsed FROM check_list WHERE jobsID = '".$partJob."'";
	$repairValue = $mysqli->query($queryParts);
	$getRepairStr = $repairValue->fetch_array();
	$repairS = $getRepairStr['repairOption'];
	$pUsed = $getRepairStr['partsUsed'];
	$orderList = '';
	if (isset($_POST['order'])) {
	foreach ($_POST['order'] as $orders => $order) {
	$orderList.= $order.",";}
	}
	if (isset($_POST['used'])) {
	foreach ($_POST['used'] as $uParts => $uPart) {
	$usedList.= $uPart.",";	}
	}
	$orderList =  substr($orderList, 0, -1);
	$usedList =  substr($usedList, 0, -1);
	$checkPartz = $mysqli->query("SELECT orderID, partOrder, usedList FROM parts_order WHERE jobsID = ".$partJob);
	$IsID = $checkPartz->fetch_array();
	$IsOrder = $IsID['orderID'];
	if (!empty($IsOrder)) {
	$PartsOrd = $IsID['partOrder'];
	$PartsUsed = $IsID['usedList'];
	if (empty($PartsOrd)) {$PartsOrd = $orderList;}
	else {$PartsOrd = $PartsOrd.",".$orderList;}
	if (empty($PartsUsed)) {$PartsUsed = $usedList;}
	else {$PartsUsed = $PartsOrd.",".$usedList;}
	if (!empty($PartsOrd)) {
	$partUpdate = "UPDATE parts_order SET partOrder = '".$PartsOrd."' WHERE jobsID = ".$partJob;
	if ($insertOrders = $mysqli->query($partUpdate)) {
		header('Location: /protected_page.php');
		} else { printf ($mysqli->error); }
	}
	if (!empty($PartsUsed)) {
	$partUpdate = "UPDATE parts_order SET usedList = '".$PartsUsed."' WHERE jobsID = ".$partJob;
	if ($insertOrders = $mysqli->query($partUpdate)) {
		header('Location: /protected_page.php');
		} else { printf ($mysqli->error); }
	}
	} else {
	$partUpdate = "INSERT INTO parts_order VALUES (NULL, ".$partJob.", '".$repairM."', '".$orderList."', '".$usedList."', '".$repairS."', '".$pUsed."')";
	if ($insertOrders = $mysqli->query($partUpdate)) {
		header('Location: /protected_page.php');
		} else { printf ($mysqli->error); }
	}
}
?>