<?php
include_once '../includes/register.inc.php';
include_once '../includes/functions.php';
 
sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Elegant Garage Doors</title>
		<link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
		<style>
		.menuBar {
		float:left; 
		width:97%; 
		height:11%;
		background: -moz-linear-gradient(-45deg,  rgba(37,143,235,0.9) 0%, rgba(22,127,226,0.91) 50%, rgba(16,110,210,0.91) 51%, rgba(23,80,171,0.91) 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,rgba(37,143,235,0.9)), color-stop(50%,rgba(22,127,226,0.91)), color-stop(51%,rgba(16,110,210,0.91)), color-stop(100%,rgba(23,80,171,0.91))); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(-45deg,  rgba(37,143,235,0.9) 0%,rgba(22,127,226,0.91) 50%,rgba(16,110,210,0.91) 51%,rgba(23,80,171,0.91) 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(-45deg,  rgba(37,143,235,0.9) 0%,rgba(22,127,226,0.91) 50%,rgba(16,110,210,0.91) 51%,rgba(23,80,171,0.91) 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(-45deg,  rgba(37,143,235,0.9) 0%,rgba(22,127,226,0.91) 50%,rgba(16,110,210,0.91) 51%,rgba(23,80,171,0.91) 100%); /* IE10+ */
		background: linear-gradient(135deg,  rgba(37,143,235,0.9) 0%,rgba(22,127,226,0.91) 50%,rgba(16,110,210,0.91) 51%,rgba(23,80,171,0.91) 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e6258feb', endColorstr='#e81750ab',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
		color:#FFF;
		font-family: 'Muli', sans-serif;
		}
		.FPageLinks { width: auto; font-size: 2vw; margin-left: 2.8vw; line-height: 5vw; color:#FFF; text-decoration:none;}
		.FPageLinks:hover {text-decoration:underline; }
		</style>
    </head>
    <body style="background:url(/background/BG4L.jpg); background-size: cover  !important; background-repeat: no-repeat  !important; background-attachment: fixed  !important; background-position: center !important;">
        <?php if (login_check($mysqli) == true) : ?>
	<div style="float: left; width: 21%; height: 87vh; margin-top:0; margin-left: -0.5vw; background:rgba(255,255,255,0.7);">
		<div id="Link1" class="menuBar" style="margin-bottom:4.85%; margin-top:4.85%; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/user_add.php" class="FPageLinks">Add User</a></div>
		<div id="Link2" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/tech_set.php" class="FPageLinks">Set Technician</a></div>
		<div id="Link3" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobAssign.php" class="FPageLinks">Set Jobs</a></div>
		<div id="Link4" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/protected_page2.php" class="FPageLinks">Unassign Jobs</a></div>
		<div id="Link5" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/progJobs.php" class="FPageLinks">On-Hold Jobs</a></div>
		<div id="Link6" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobComplete.php" class="FPageLinks">Completed Jobs</a></div>
		<div id="Link7" class="menuBar" style="height:11%; border-top:2px solid white; border-right:10px solid white;"><a href="/includes/logout.php" class="FPageLinks">Log Out</a></div>
	</div>
		<?php
		function FixTime($time) {
		$fixTime = substr($time, 0, 3);
		$newTime = substr($time, 3, -3);
		$fixTime = intval($fixTime);
		if ($fixTime > 12) {
		$fixTime = $fixTime - 12;
		$finalTime = $fixTime.":".$newTime." PM";}
		else { $finalTime = $fixTime.":".$newTime." AM"; }
		return $finalTime; }
		$returnJobs = '<div style="float:left;width:70%; margin-left:2%; text-align:center; background:rgba(255,255,255,0.9);"><span style="font-size:3vh;font-weight:bold;">Jobs Assigned To Technicians</span><br></div><div style="float:left;height:39vh;width:70%;overflow-y:scroll;margin-left:2vw;padding-top:1vh;border:1px solid black; background:rgba(255,255,255,0.9);"><div style="float:left;width:13%;padding-top:1vh;padding-left:2%;">Name</div><div style="float:left;width:8.3%;padding-top:1vh;padding-left:0.5vw;">Time</div><div style="float:left;width:33.5%;padding-top:1vh;padding-left:2%;">Address</div><div style="float:left;width:18%;padding-top:1vh;">Details</div><div style="float:left;width:18%;padding-top:1vh;padding-left:2%;">Unassign Job</div><div id="AssignBox" style="float:left; width:98%; margin-left: 0.8%;">';
		$assignQuery = "SELECT jobsID, appointmentTime, jobLocation, jobType, employeeName
		FROM active_jobs
		INNER JOIN members ON active_jobs.employeeID = members.id
		WHERE active_jobs.assigned = 1
		ORDER BY employeeName ASC";
		$assignData = $mysqli->prepare($assignQuery);
		$assignData->execute();
		$assignData->bind_result($jobID, $aTime, $jobLoc, $jobType, $employeeName);
		while ($assignData->fetch()) {
		$appoint = FixTime($aTime);
		$returnJobs.= "<div style='width:100%; border:solid 1px black; margin-top:0.5vw; height:5vh;'><div style='float:left;width:13%;padding-left:2%;height:5vh;border-right:solid 1px black;line-height:4vh;'>".$employeeName."</div><div style='float:left;width:8.3%;height:5vh;border-right: solid 1px black;line-height:4vh;padding-left:0.5vw;'>".$appoint."</div><div style='float:left;width:33.5%;height:5vh;border-right: solid 1px black;line-height:4vh;padding-left:1vw;'>".$jobLoc."</div><div style='float:left;width:18%;padding-left:2%;height:5vh;border-right:solid 1px black;line-height: 4vh;'>".$jobType."</div><div style='float:left;width:18%;padding-left:2%;height:5vh;'><form method='POST' ACTION='page_func/decline_job2.php?declineID=".$jobID."'><button style='height:4vh;' type='submit' name='unassign'/>Unassign</button></form></div></div>";
		}
		$returnJobs.='</div></div>';
		echo $returnJobs;
		$assignData->close();
		$acceptedJerb = "SELECT jobsID, appointmentTime, jobLocation, jobType, employeeName
		FROM accepted_jobs
		INNER JOIN members ON accepted_jobs.employeeID = members.id
		ORDER BY employeeName ASC";
		$acceptedJobs = $mysqli->prepare($acceptedJerb);
		$acceptedJobs->execute();
		$acceptedJobs->bind_result($AccID, $AccTime, $AccLoc, $AccType, $AccName);
		$listaccept = '<div style="float:left;width:70%; margin-left:2%; text-align:center; margin-top:4vh; background:rgba(255,255,255,0.9);"><span style="font-size:3vh;font-weight:bold;">Jobs Accepted By Technicians</span><br></div><div style="float:left;height:39vh;width:70%;overflow-y:scroll;margin-left:2vw;padding-top:1vh;border:1px solid black; background:rgba(255,255,255,0.9);"><div style="float:left;width:13%;padding-left:2%;padding-top:1vh;">Name</div><div style="float:left;width:8.3%;padding-left:0.5vw;padding-top:1vh;">Time</div><div style="float:left;width:33.5%;padding-left:2%;padding-top:1vh;">Address</div><div style="float:left;width:18%;padding-top:1vh;">Details</div><div id="AcceptBox" style="float:left;width:98%; margin-left:0.8%;padding-top:1vh;">';
		while ($acceptedJobs->fetch()) {
		$FTime = FixTime($AccTime);
		$listaccept.= "<div style='width:100%; border:solid 1px black; margin-top:0.5vw; height:5vh;'>
		<div style='float:left;width:13%;padding-left:2%;height:5vh;border-right:solid 1px black;line-height:4vh;'>".$AccName."</div><div style='float:left;width:8.3%;height:5vh;border-right: solid 1px black;line-height:4vh;padding-left:0.5vw;'>".$FTime."</div><div style='float:left;width:33.5%;height:5vh;border-right: solid 1px black;line-height:4vh;padding-left:1vw;'>".$AccLoc."</div><div style='float:left;width:18%;padding-left:2%;height:5vh;border-right:solid 1px black;line-height: 4vh;'>".$AccType."</div><div style='float:left;width:18%;padding-left:2%;height:5vh;'><form method='POST' ACTION='page_func/decline_job.php?declineID=".$jobID."'><button style='height:4vh; margin-top: 4px;' type='submit' name='unaccept'/>Unassign</button></form></div></div>"; }
		$listaccept.= '</div></div>';
		echo $listaccept;
		$acceptedJobs->close();
		?>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>