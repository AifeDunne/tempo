<?php
include_once 'db_connect.php';

if (!empty($_POST['f'])) {
$itemU = '';
$itemO = '';
$lengthCount = 0;
$lengthType = '';
$thisLabel = '';
$thisJob = $_GET['getJob'];
foreach($_POST['f'] as $eachBox) {
foreach ($eachBox as $eachRow) {
foreach ($eachRow as $eachItem) {
if ($lengthCount === 1) {
if ($lengthType === 'U') { $itemU.= $thisLabel.$eachItem.','; }
if ($lengthType === 'O') { $itemO.= $thisLabel.$eachItem.','; }
$lengthCount = 0; $thisLabel = ''; $lengthType = '';
} else {
$getType = substr($eachItem, -1);
$eachItem = substr($eachItem, 0, -1);
if ($eachItem === 'LeftRed' || $eachItem === 'RightBlack') { $lengthCount = 1; $thisLabel = $eachItem; $lengthType = $getType; }
if ($lengthCount === 0) {
if ($getType === 'U') { $itemU.= $eachItem.','; }
if ($getType === 'O') { $itemO.= $eachItem.','; }
				}
			}
		}
	}
}
if (!empty($itemU)) {
$itemU = substr($itemU, 0, -1);
}
if (!empty($itemO)) {
$itemO = substr($itemO, 0, -1);
	}
$partQuery = "INSERT INTO partsList VALUES (NULL, ".$thisJob.", '".$itemU."', '".$itemO."')";
if ($insertParts = $mysqli->query($partQuery)) {
		header('Location: /pages/create_voice.php');
		} else { printf ($mysqli->error); }
} else { echo "You must enter at least one part"; };
?>