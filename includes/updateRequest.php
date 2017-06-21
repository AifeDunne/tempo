<?php
include_once 'db_connect.php';

if (!empty($_POST['dataRequest'])) {
$queryCount = 1;
$queryArray = array();
$dataRequest = $_POST['dataRequest'];
	function ProcessData($qCount, $qArray) {
	global $mysqli;
	for ($q = 0; $q <= $qCount; $q++) {
		$processData = $mysqli->query($qArray[$q]);
		}
	}
if ($dataRequest == 'AddGroup') {
	$groupID = $_POST['groupID'];
	$groupTitle = $_POST['groupTitle'];
	$groupLabel = $_POST['groupLabel'];
	$groupSuper = $_POST['groupSuper'];
	$labelDesc =  $_POST['labelDesc'];
	$newGCount = $groupID + 1;
	$insertGroup = "INSERT INTO usergroups VALUES (".$newGCount.", '".$groupTitle."', '".$groupLabel."', '".$groupSuper."', '".$labelDesc."')";
	$queryArray[] = $insertGroup;
	$updateGCount = "UPDATE sysvar SET varValue = ".$newGCount." WHERE varName = 'groupCount'";
	$queryArray[] = $updateGCount;
	$queryCount++;
	$labelAR = $_POST['labelAR'];
	$superAR = $_POST['superAR'];
	if ($labelAR === 1 || $labelAR === '1') { 
	$queryCount++; 
	$takeLabelString = $_POST['acceptLabel']; 
	$takeLabelString.= ",".$groupLabel; 
	$appendLabel = "UPDATE sysvar SET varValue = '".$takeLabelString."' WHERE varName = 'groupLabels'"; 
	$queryArray[] = $appendLabel; }
	if ($superAR === 1 || $superAR === '1') {
	$queryCount++; 
	$promoteUser = "UPDATE employees SET groupID = ".$newGCount.", userLevel = 2 WHERE employeeName = '".$groupSuper."'"; 
	$queryArray[] = $promoteUser; }
	}
if ($dataRequest == 'AddRole') {
	$roleID = $_POST['roleID'];
	$roleName = $_POST['roleName'];
	$roleType = $_POST['roleCat'];
	$roleColor = $_POST['roleBG'];
	$roleDescipt = $_POST['roleDesc'];
	$roleCount = $roleID + 1;
	$insertRole = "INSERT INTO userroles VALUES (".$roleCount.", '".$roleName."', '".$roleType."', '".$roleColor."', '".$roleDescipt."')";
	$queryArray[] = $insertRole;
	$updateRCount = "UPDATE sysvar SET varValue = ".$roleCount." WHERE varName = 'roleCount'";
	$queryArray[] = $updateRCount;
	$queryCount++; 
	}
	$queryCount = $queryCount - 1;
	ProcessData($queryCount, $queryArray);
	}
?>