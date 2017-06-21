<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/projects/tempo/includes/db_connect.php';

if (!empty($_POST['dataRequest'])) {
$queryCount = 1;
$dataRequest = $_POST['dataRequest'];
	function ProcessData($qCount, $qArray) {
	global $mysqli;
	echo $qCount;
	if ($qCount !== 0) { for ($q = 0; $q <= $qCount; $q++) { $processData = $mysqli->query($qArray[$q]); }
		} else { $processData = $mysqli->query($qArray); }
	}
if ($dataRequest == 'AddSchedule') {
if(isset($_POST['AddDate'])) {
if(isset($_POST['AddTitle'])) {
	$queryArray = '';
	$NewDate = $_POST['AddDate'];
	list($Yr, $Mnth, $Dy) = explode('-', $NewDate);
	$Mnth = ltrim($Mnth, '0');
	$NewDate = $Yr.'-'.$Mnth.'-'.$Dy;
	$NewTitle = $_POST['AddTitle'];
	$FinalStart = $_POST['TimeStart'];
	$FinalEnd = $_POST['TimeEnd'];
	$addSchedule = "NULL, '".$NewDate."', '".$NewTitle."', '".$FinalStart."', '".$FinalEnd."'";
	$completeSchedule = "INSERT INTO schedule VALUES (".$addSchedule.")";
	$queryArray = $completeSchedule;
		}
	}
}
if ($dataRequest == 'AddGroup') {
	$queryArray = array();
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
	$queryArray = array();
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
if ($dataRequest == 'AddGroupUser') {
	$groupUpdate = $_POST['userGroupArray'];
	$groupElementC = $_POST['ggCountArr'];
	if ($groupElementC !== 1 && $groupElementC !== '1') {
	$groupUpdate = explode("+|+", $groupUpdate);
	print_r($groupUpdate);
	} else { echo $groupUpdate; }
}
	$queryCount = $queryCount - 1;
	ProcessData($queryCount, $queryArray);
	}
?>