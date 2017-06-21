<?php
include_once 'db_connect.php';
	if (isset($_POST['safetymodel'])) {
	if (isset($_POST['quality'])) {
	if (isset($_POST['detail'])) {
	$safetyJob = $_GET['getID'];
	$safeGarage = $_POST['safetymodel'];
	$scheck = '';
	$dcheck = '';
	$rcheck = '';
		if (!empty($_POST['used'][0])) {
		$ucheck.= $_POST['used'][0];
		} else {$ucheck.= "0";}
		for ($c = 0; $c <= 32; $c++) {
		if (!empty($_POST['quality'][$c])) { $scheck.= $_POST['quality'][$c]; }
		else {$scheck.= "0"; };
		if (!empty($_POST['detail'][$c])) { $dcheck.= $_POST['detail'][$c]; }
		else {$dcheck.= "0"; }
		if (!empty($_POST['used'][$c])) { $ucheck.= $_POST['used'][$c]; }
		else {$ucheck.= "0"; }
		if (!empty($_POST['repair'][$c])) { $rcheck.= $_POST['repair'][$c]; }
		else {$rcheck.= "0"; }
		}
		$checkPartz = $mysqli->query("SELECT orderID FROM parts_order WHERE jobsID = ".$safetyJob);
		$checkOrder = $checkPartz->fetch_array();
		$oCheck = $checkOrder['orderID'];
		if (!empty($oCheck)) {
		$safetyForm = $mysqli->query("INSERT INTO check_list VALUES (NULL, ".$safetyJob.", '".$safeGarage."', '".$dcheck."', '', '".$scheck."', '".$rcheck."', '".$ucheck."')");
		$updateForm = $mysqli->query("UPDATE parts_order SET repairOption = '".$rcheck."', partsUsed = '".$ucheck."' WHERE jobsID = ".$safetyJob);
		header('Location: /protected_page.php');
		} else {
		$safetyForm2 = $mysqli->query("INSERT INTO check_list VALUES (NULL, ".$safetyJob.", '".$safeGarage."', '".$dcheck."', '', '".$scheck."', '".$rcheck."', '".$ucheck."')");
		$updateForm2 = $mysqli->query("INSERT INTO parts_order VALUES (NULL, ".$safetyJob.", '".$safeGarage."', '', '', '".$rcheck."', '".$ucheck."')");
		header('Location: /protected_page.php');
			}
		} else { echo "You have not entered all the required information"; }
	} else { echo "You have not entered all the required information"; }
}
?>