<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
require_once 'Google/autoload.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Elegant Garage Doors</title>
		<link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
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
		<div id="Link2" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/protected_page2.php" class="FPageLinks">Home</a></div>
		<div id="Link3" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobAssign.php" class="FPageLinks">Set Jobs</a></div>
		<div id="Link4" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/job_add.php" class="FPageLinks">Unassign Jobs</a></div>
		<div id="Link5" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/progJobs.php" class="FPageLinks">On-Hold Jobs</a></div>
		<div id="Link6" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobComplete.php" class="FPageLinks">Completed Jobs</a></div>
		<div id="Link7" class="menuBar" style="height:11%; border-top:2px solid white; border-right:10px solid white;"><a href="/includes/logout.php" class="FPageLinks">Log Out</a></div>
	</div>
		<div id="TechBox" style="float:left; width:50%; height:100px; margin-left:2vw; margin-top:2vw;">
		<iframe src="https://www.google.com/calendar/embed?src=1t23q1fn9mg1qm0s7vb9thbp8c%40group.calendar.google.com&mode=AGENDA&ctz=America/Los_Angeles" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
		</div>
		<?php
		function MakeBox() {
		global $mysqli;
		$employeeBox = "<div style='float: right; width: 22%; height: auto; border: 1px black solid; padding: 1vw; margin-right: 1.4vw; margin-top: 2vw; background:#FFF;'><span style='float:none; margin-left:auto; margin-right:auto; font-size:2vw; line-height: 4vw;'>Available Technicians: </span><br>";
		$empCounter = 0;
		$findEmp = "SELECT employeeName FROM members WHERE working = 1";
		if ($showEmployees = $mysqli->prepare($findEmp)) {
		$showEmployees->execute();
		$showEmployees->bind_result($employeeName);
		while ($showEmployees->fetch()) { $empCounter++; $employeeBox.= "<span style='float:left; clear:left; line-height:2vw;'>".$empCounter.". ".$employeeName."</span>"; }
		}
		$showEmployees->close();
		$employeeBox.= "<form method='POST' ACTION='page_func/refresh.php'><button style='float: left; width: 7vw; height: 2vw; margin-top: 1vw; margin-bottom: 0.5vw; font-size: 1vw; clear: left;'>Update</button></form></div>";
		echo $employeeBox;
		}
		
		if (isset($_SESSION['grabSchedule'])) {
		MakeBox();
		} else {
		$url = "https://www.google.com/calendar/feeds/1t23q1fn9mg1qm0s7vb9thbp8c%40group.calendar.google.com/public/basic";
		$data = file_get_contents($url);
		$xmlD = simplexml_load_string($data);
		$json = json_encode($xmlD);
		$JArray = json_decode($json,TRUE);
		$safeArray = array(); $addTech = '';
		$cMonth = date('M');
		$cDay = date('j');
		$cYear = date('Y');
		$cDate = $cMonth." ".$cDay.", ".$cYear;
		
		foreach ($JArray["entry"] as $listing) {
		$fData = $listing["summary"];
		$techDate = strpos($fData, $cDate);
		if ($techDate != false) { $safeArray[] = $listing["title"];	$addTech.= "'".$listing["title"]."',"; }
		}
		
		if (!empty($addTech)) {
		$activeArray = array();
		$removeArray = array();
		$employeeCount = 0; 
		$removeEntry = '';
		$addTech = substr($addTech, 0, -1);
		$empQuery = "UPDATE members SET working = 1 WHERE employeeName IN (".$addTech.") AND working = 0";
			if($updateEmployee = $mysqli->query($empQuery)) {
			$getE = "SELECT employeeName FROM members WHERE working = 1;";
				if ($allEmployees = $mysqli->prepare($getE)) {
				$allEmployees->execute();
				$allEmployees->bind_result($activeEmployee);
				while ($allEmployees->fetch()) { $employeeCount++; $activeArray[] = $activeEmployee; }
				$allEmployees->close();
				for ($e = 0; $e <= $employeeCount; $e++) { if (!in_array($safeArray[$e], $activeArray)) { $removeEntry.= "'".$activeArray[$e]."',"; } }
					if (!empty($removeEntry)) {
					$removeEntry = substr($removeEntry, 0, -1);
					$remEmp = "UPDATE members SET working = 0 WHERE employeeName IN (".$removeEntry.")";
					$removeTechnician = $mysqli->query($remEmp);
						}
					} else { echo "LEVEL2 | "; printf ($mysqli->error); }
				echo $_SESSION['grabSchedule'];
				} else { echo "LEVEL1 | "; printf ($mysqli->error); }
			$_SESSION['grabSchedule'] = true;
			MakeBox();
			}
		}
		?>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>