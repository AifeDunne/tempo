<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Protected Page</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<style>
		#menutext {	float: left; margin-top: 58.4vh; margin-left: -14vw; height: 12vh; letter-spacing: 1.5vw; font-size: 6vh; text-transform: uppercase; width: 30vw; color: #FFF; transform: rotate(90deg); -webkit-transform: rotate(90deg); -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -ms-transform: rotate(90deg); pointer-events:none; }
		</style>
    </head>
	<?php if (login_check($mysqli) == true) : ?>
    <body>
	<script> $(document).ready(function() { $("#FullMenu").click(function() { window.location.href='pages/start_safety.php'; }); });</script>
	<?php
		function FixTime($time) {
		$fixTime = substr($time, 0, 3);
		$newTime = substr($time, 3, -3);
		$fixTime = intval($fixTime);
		if ($fixTime > 12) {
		$fixTime = $fixTime - 12;
		$finalTime = $fixTime.":".$newTime." PM";}
		else { $finalTime = $fixTime.":".$newTime." AM"; }
		return $finalTime;
		}
		$findUser = $_SESSION['username'];
		$grabID = "SELECT id FROM members WHERE username = '".$findUser."'";
		$parseID = $mysqli->query($grabID);
		$currentWork = $parseID->fetch_array();
		$thisID = $currentWork['id'];
		$_SESSION['employeeID'] = $thisID;
		$buttonY = 1;
		if (!empty($thisID)) {
		$findPrimary = "SELECT jobsID, appointmentTime, jobLocation, jobType, customerName FROM accepted_jobs
		INNER JOIN customer_jobs ON customer_jobs.customerID = accepted_jobs.customerID
		WHERE employeeID = '".$thisID."' AND primary_job = 1";
		$primValue = $mysqli->query($findPrimary);
		$getPrimary = $primValue->fetch_array();
		$primaryJobID = $getPrimary['jobsID'];
		if (!empty($primaryJobID)) {
		$_SESSION['primary_job'] = $primaryJobID;
		$buttonY = 0;
		$primaryTime = $getPrimary['appointmentTime'];
		$primeTime = FixTime($primaryTime);
		$primaryLoc = $getPrimary['jobLocation'];
		$primaryType = $getPrimary['jobType'];
		$primaryCustomer = $getPrimary['customerName'];
		if ($primInvoice = $mysqli->query("SELECT listID FROM partsList WHERE jobsID = ".$primaryJobID)) {
		$parseInvoice = $primInvoice->fetch_array();
		$pVoice = $parseInvoice['listID'];
		if ($primAssign = $mysqli->query("SELECT jobsID FROM complete_jobs WHERE jobsID = ".$primaryJobID)) {
		$isReassign = $primAssign->fetch_array();
		$rCheck = $isReassign['jobsID'];
		if ($rCheck !== 0 && !empty($rCheck)) {
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=pages/create_voice.php?reassign=true">';    
			exit;
				}
		
			}
		if ($pVoice !== 0 && !empty($pVoice)) {
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=pages/create_voice.php">';    
			exit;
			}
		}
		$logOut = '';
		$boxStyle = "float:right; width:45%; height:45vh; margin-right:2vw; margin-top:3vh; clear:right;";
		$boxStyle2 = "width:45%; height:45vh; margin-right:2vw; margin-top:0vh;";
		$spanStyle1 = "float:left;font-size:1.5vw;margin-left:14vw;";
		$spanStyle2 = "float:left;font-size:1.5vw;margin-left:14vw;";
		$fullMenu = '<div id="FullMenu" style="position:fixed; float:left; width:5%; height:98vh; background: #000; cursor:pointer;">
			<div id="menutext">START</div>
			</div>
		</div>';
		echo $fullMenu;
		$primaryBox = "<div style='float:left;width:38vw;margin-left:10vw;'>
		<div style='width:100%; font-size:2vw; background-color:#d3d3d3;'>Current Assignment</div>
		<span style='font-size:1.3vw;'>Starts At: </span><span style='font-size:1.1vw;'>".$primeTime."</span><br>
		<span style='font-size:1.3vw;'>Destination: </span><span style='font-size:1.1vw;'>".$primaryLoc."</span><br>
		<span style='font-size:1.3vw;'>Current Task: </span><span style='font-size:1.1vw;'>".$primaryType."</span><br>
		<span style='font-size:1.3vw;'>Customer's Name: </span><span style='font-size:1.1vw;'>".$primaryCustomer."</span><br>
		</div>";
		echo $primaryBox;
		} else {
		$logOut = '<div style="float:left;margin-top:4vh;margin-left:7vw;font-size:2vw;padding-left:2vw;padding-right:2vw;padding-top:1vw;padding-bottom:1vw;border:1px solid black;"><a href="/includes/logout.php" style="color:#000;">Log out</a></div>';
		$boxStyle = "width:44%; float:left; height:61vh; margin-left:7vw; margin-top:4vh; clear:left;";
		$spanStyle1 = 'float:left;font-size:1.5vw;margin-left:13vw;';
		$boxStyle2 = "width:44%; margin-right:4vw; margin-top:4vh;";
		$spanStyle2 = 'float:right;font-size:1.5vw;margin-right:13vw;';}
		}
		$acceptedJ = "SELECT jobsID, appointmentTime, jobLocation, jobType, customerID
		FROM accepted_jobs
		WHERE employeeID = ".$thisID." AND primary_job = 0
		ORDER BY appointmentTime ASC";
		$acceptJobs = $mysqli->prepare($acceptedJ);
		$acceptJobs->execute();
		$acceptJobs->bind_result($AcceptID, $AcceptTime, $AcceptLoc, $AcceptType, $AcceptCustomer);
		$currentJobs = "<div id='AcceptList' style='float:right; height:61vh; overflow-y:scroll; border:1px solid black; ".$boxStyle2."'>
		<span style='".$spanStyle2."'>Jobs You've Accepted</span><br>";
		while ($acceptJobs->fetch()) {
		if ($buttonY != 0) {
		$submitPrimary = "<form method='POST' ACTION='pages/page_func/primary_job.php?acceptThis=".$AcceptID."'><button style='height:4vh;' type='submit' name='make_primary'/>Make Primary</button></form>";
		} else {$submitPrimary = '';}
		$appointAccept = FixTime($AcceptTime);
		$currentJobs.= "<div id='AcceptBox' style='float:left; width:97%; margin-left:2%; margin-top:2%; height:100px;'>
		<div style='width:100%; border:solid 1px black; margin-top:1vw; height:100px;'>
		<div style='float:left; width:60%; height:100px; margin-left:1%;'>Appointment:".$appointAccept."<br>
		Address: ".$AcceptLoc."<br>Job Type: ".$AcceptType."<br>Customer: ".$AcceptCustomer."</div>
		<div style='float:right; width:20%; height:3vh; margin-top:1vh; margin-right: 1vw;'>".$submitPrimary."</div>
		</div></div>"; }
		$currentJobs.= "</div></div>";
		echo $currentJobs;
		$acceptJobs->close();
		$getWork = "SELECT jobsID, appointmentTime, jobLocation, jobType, customerID
		FROM active_jobs
		WHERE assigned = 1 AND employeeID = ".$thisID."
		ORDER BY appointmentTime ASC";
		if ($getJobs = $mysqli->prepare($getWork)) {
		$getJobs->execute();
		$getJobs->bind_result($jobID, $aTime, $jobLoc, $jobType, $customerID);
		$availableJobs = "<div id='JobsList' style='overflow-y:scroll; border:1px solid black; ".$boxStyle."'>
		<span style='".$spanStyle1."'>Jobs Assigned To You</span><br>";
		while ($getJobs->fetch()) {
		$appoint = FixTime($aTime);
		$availableJobs.= "<div id='JobBox' style='float:left;width:97%;margin-left:2%;height:100px;margin-top:2%;'>
		<div style='width:100%; border:solid 1px black; margin-top:1vw; height:100px;'>
		<div style='float:left; width:60%; height:100px; margin-left:1%;'>Appointment:".$appoint."<br>
		Address: ".$jobLoc."<br>Job Type: ".$jobType."<br>Customer: ".$customerID."</div>
		<div style='float:right; width:20%; height:3vh; margin-top:1vh; margin-right: 1vw;'><form method='POST' ACTION='pages/page_func/accept_job.php?acceptID=".$jobID."'><button style='height:4vh;' type='submit' name='accept'/>Accept Job</button></form></div>
		<div style='float:right; width:20%; height:3vh; margin-top:2vh; margin-right: 1vw; clear:right;'><form method='POST' ACTION='pages/page_func/decline_job.php?declineID=".$jobID."'><button style='height:4vh;' type='submit' name='decline' value='".$jobID."'/>Decline Job</button></form></div></div></div>";
		}
		$availableJobs.= "</div></div>";
		echo $availableJobs;
		$getJobs->close();
		}
		printf ($mysqli->error);
		echo $logOut;
		?>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>