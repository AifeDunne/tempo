<?php
$grabUser = $_SESSION['username'];
$userQuery = "SELECT members.id, employees.employeeName, employees.groupID FROM members
LEFT JOIN employees ON members.id = employees.employeeID
WHERE username = '".$grabUser."'";
$grabUserData = $mysqli->query($userQuery);
$userData = $grabUserData->fetch_array();
$userID = $userData['id'];
$userName = $userData['employeeName'];
$userGroup = $userData['groupID'];
$_SESSION['userID'] = $userID;
$homePage = '<div id="centralBox">';
$homePage.= '<div id="welcomeBox"><span id="welcomePhrase">Welcome, </span><span id="userName">'.$userName.'</span></div>';
$homePage.= '<div id="staffManageBox"><span id="staffManageLink">Staff Manager</span><div id="currentSchedule"><span style="font-size:1.5vw; float:left; clear:both;">Scheduled Today</span><br><br>';
	date_default_timezone_set('America/Denver');
	$gettoday = getdate();
	$nowYear = $gettoday['year'];
	$nowDay = $gettoday['mday'];
	$nowMonth = $gettoday['mon'];
	$nowDate = $nowYear."-".$nowMonth."-".$nowDay;
	$scheduleString = '';
	$getSchedule2 = "SELECT title, start, end FROM schedule WHERE evntdate = '".$nowDate."'";
	$retrieveSchedule2 = $GLOBALS['mysqli']->prepare($getSchedule2);
	$retrieveSchedule2->execute();
	$retrieveSchedule2->bind_result($employeeRN, $shiftStart, $shiftEnd);
	while ($retrieveSchedule2->fetch()) {
	$scheduleString.= "<span style='float:left; clear:both;'><b>".$employeeRN."</b> - ".$shiftStart." to ".$shiftEnd."</span>";
	}
	$retrieveSchedule2->close();
$homePage.= $scheduleString.'</div></div>';
$thisGroup = '';
if ($userGroup === '0' || $userGroup === 0) { $thisGroup = 'Admin'; }
$homePage.='<div id="workFlowBox"><span class="coverText">Workflow</span></div>';
$homePage.= "<script>
$('#staffManageLink').on('click', function() {
window.location.replace('staff-manage/');
});
$('#workFlowBox').on('click', function() {
window.location.replace('workflow/');
});
</script>";
$homePage.="<div id='clockBox'><div id='ClockWrapper'><div id='Hour' style='float:left;'></div><div id='Min' style='float:left;margin-right:5px;letter-spacing: -7px;'></div><div id='timeSide' style='float:right;'></div></div></div>";
$homePage.="<script>
function UpdateTime() {
	var CrntTime = new Date();
	var cHour = CrntTime.getHours();
	var cMin = CrntTime.getMinutes();
	if (cMin < 10 ) {cMin = '0' + cMin;}
	if (cHour >= 12){var cSide = 'PM';}
	else if (cHour < 12) {var cSide = 'AM';}
	if (cHour > 12) {cHour = cHour - 12;}
	document.getElementById('Hour').innerHTML = cHour +':';
    document.getElementById('Min').innerHTML = cMin;
	document.getElementById('timeSide').innerHTML = cSide;
 }
 $(function() {
 UpdateTime();
 });
 $(document).ready(function() {
 ClockInt = setInterval(UpdateTime,1000);
 });
 </script>";
 date_default_timezone_set('America/Denver');
	$getDate = getdate();
	$thisYear = $getDate['year'];
	$thisDay = $getDate['mday'];
	$thisWDay = $getDate['weekday'];
	$thisMonth = $getDate['month'];
	$thisDate = $thisMonth." ".$thisDay.", ".$thisYear;
$homePage.= '<div id="dateBox"><span id="todaysDate">'.$thisDate.'</span><span id="dayName">'.$thisWDay.'</span></div>';
$homePage.= '</div>';
echo $homePage;
?>