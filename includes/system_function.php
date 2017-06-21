<?php
include_once 'db_connect.php';
include_once 'functions.php';

function RetrieveVar($systemVar) {
$dataPackage = '';
if (!empty($systemVar)) {
$returnPref = "SELECT varValue, serialCount FROM sysvar WHERE varName = '".$systemVar."'";
if ($returnData = $GLOBALS['mysqli']->query($returnPref)) {
$varArray = $returnData->fetch_array();
$serialStr = $varArray['varValue'];
$serialKey = $varArray['serialCount'];
$shiftKey = $serialKey - 1; 
$dataPackage = $shiftKey."-".$serialStr;
	return $dataPackage;
	} else { $errorWarn = $returnPref; return $errorWarn; }
	}
}

function CheckPreference($prefArray) {
$preferenceData = array();
foreach ($prefArray as $sysPreference) {
$formatQuery = "SELECT setPreference FROM syspref WHERE preferenceID = '".$sysPreference."'";
$returnPreference = $GLOBALS['mysqli']->query($formatQuery);
$currentPref = $returnPreference->fetch_array();
$preferenceData[] = $currentPref;
$formatQuery = '';
		}
return $preferenceData;
}

function RetriveGMen() {
$empArray = array();
$superGroup = array();
$inGroup = array();
$outGroup = array();
$groupArray = '';
$empScheduled = '';
$empBusy = '';
$getGroupMen = "SELECT employeeID, employeeName, userLevel, roleName, groupID, working, busy FROM employees";
if ($retrieveUsers = $GLOBALS['mysqli']->prepare($getGroupMen)) {
		$retrieveUsers->execute();
		$retrieveUsers->bind_result($empID, $empName, $userLvl, $roleName, $groupID, $isWorking, $isBusy);
		while ($retrieveUsers->fetch()) {
			if ($isWorking === 1 || $isWorking === '1') { $empScheduled = 'Yes'; } else { $empScheduled = 'No'; }
			if ($isBusy === 1 || $isBusy === '1') { $empBusy = 'Yes'; } else { $empBusy = 'No'; }
		$empGroup[$empID] = array('id' => $empID, 'name' => $empName, 'userlevel' => $userLvl, 'userRole' => $roleName, 'group' => $groupID, 'scheduled' => $empScheduled, 'busy' => $empBusy);
		if ($userLvl === 2 || $userLvl === '2') { $superGroup[] = array('id' => $empID, 'name' => $empName, 'group' => $groupID, 'scheduled' => $empScheduled, 'busy' => $empBusy); }
		else {
		if ($groupID !== 'None') { $inGroup[] = array('id' => $empID, 'name' => $empName, 'userRole' => $roleName, 'group' => $groupID, 'scheduled' => $empScheduled, 'busy' => $empBusy); }
		else { $outGroup[] = array('id' => $empID, 'name' => $empName, 'userRole' => $roleName, 'group' => $groupID, 'scheduled' => $empScheduled, 'busy' => $empBusy); }
		}
	}
	$retrieveUsers->close();
	$groupArray = array('allemployees' => $empGroup, 'supervisor' => $superGroup, 'inGroup' => $inGroup, 'outGroup' => $outGroup);
	return $groupArray; }
}

function GetUserGroups() {
$groupArr = array();
$getUGroups = "SELECT groupID, groupName, groupLabel, groupSuper, groupDesc FROM usergroups";
if ($retrieveGroups = $GLOBALS['mysqli']->prepare($getUGroups)) {
		$retrieveGroups->execute();
		$retrieveGroups->bind_result($groupID, $groupName, $groupLabel, $groupSuper, $groupDesc);
		while ($retrieveGroups->fetch()) {
		$groupArr[] = array('groupID' => $groupID, 'groupName' => $groupName, 'groupLabel' => $groupLabel, 'groupSuper' => $groupSuper, 'groupDesc' => $groupDesc); 
		}
	$retrieveGroups->close();
	}
	return $groupArr;
}

function GetUserRoles() {
$roleArr = array();
$getURoles = "SELECT roleID, roleName, roleType, roleColor, roleDescript FROM userroles";
if ($retrieveRoles = $GLOBALS['mysqli']->prepare($getURoles)) {
		$retrieveRoles->execute();
		$retrieveRoles->bind_result($roleID, $roleName, $roleType, $roleColor, $roleDescript);
		while ($retrieveRoles->fetch()) {
		$roleArr[] = array('roleID' => $roleID, 'roleName' => $roleName, 'roleType' => $roleType, 'roleColor' => $roleColor, 'roleDescript' => $roleDescript); 
		}
	$retrieveRoles->close();
	}
	return $roleArr;
}

function GetSchedule($grepDate2) {
		$daySchedule = array();
		$getSchedule2 = "SELECT title, start, end FROM schedule WHERE evntdate = '".$grepDate2."'";
		$retrieveSchedule2 = $GLOBALS['mysqli']->prepare($getSchedule2);
		$retrieveSchedule2->execute();
		$retrieveSchedule2->bind_result($employeeRN, $shiftStart, $shiftEnd);
		while ($retrieveSchedule2->fetch()) {
		$daySchedule[] = array('dateWork' => $grepDate2, 'employeeName' => $employeeRN, 'shiftStart' => $shiftStart, 'shiftEnd' => $shiftEnd);
		}
		$retrieveSchedule2->close();
		return $daySchedule;
		}
?>