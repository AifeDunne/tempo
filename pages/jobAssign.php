<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
 
sec_session_start();
?>
<!DOCTYPE html>
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
		#jobsubmit {
		float: left;
		margin-top: 24.4vh;
		margin-left: -11vw;
		height: 12vh;
		font-size: 5vh;
		text-transform: uppercase;
		letter-spacing: 1vw;
		width: 30vw;
		transform: rotate(90deg);
		-webkit-transform: rotate(90deg);
		-moz-transform: rotate(90deg);
		-o-transform: rotate(90deg);
		-ms-transform: rotate(90deg);
		}
		</style>
    </head>
    <body style="background:url(/background/BG4L.jpg); background-size: cover  !important; background-repeat: no-repeat  !important; background-attachment: fixed  !important; background-position: center !important;">
        <?php if (login_check($mysqli) == true) : ?>
	<div style="float: left; width: 21%; height: 87vh; margin-top:0; margin-left: -0.5vw; background:rgba(255,255,255,0.7);">
		<div id="Link1" class="menuBar" style="margin-bottom:4.85%; margin-top:4.85%; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/user_add.php" class="FPageLinks">Add User</a></div>
		<div id="Link2" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/tech_set.php" class="FPageLinks">Set Technician</a></div>
		<div id="Link3" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/protected_page2.php" class="FPageLinks">Home</a></div>
		<div id="Link4" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/job_add.php" class="FPageLinks">Unassign Jobs</a></div>
		<div id="Link5" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/progJobs.php" class="FPageLinks">On-Hold Jobs</a></div>
		<div id="Link6" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobComplete.php" class="FPageLinks">Completed Jobs</a></div>
		<div id="Link7" class="menuBar" style="height:11%; border-top:2px solid white; border-right:10px solid white;"><a href="/includes/logout.php" class="FPageLinks">Log Out</a></div>
	</div>
	<div style="float:left; height:61vh; width:67%; overflow-y:scroll; margin-left:2vw; border:1px solid black; background:rgba(255,255,255,0.9);">
		<div id="JobBox" style="float:left; width:98%; height:100px; background:rgba(255,255,255,0.9);">
		<?php
		function AssignJob($var1, $var2, $var3, $var4) {
		$_SESSION['assign'] = $var1;
		$_SESSION['jobArray'] = $var2;
		$_SESSION['count'] = $var3;
		$_SESSION['makeprimary'] = $var4;
		if(include_once 'page_func/assignwerk.php') {
		unset($_SESSION['assign']);
		unset($_SESSION['jobArray']);
		unset($_SESSION['count']);
		unset($_SESSION['makeprimary']);
			}
		}
		if (!empty($_POST['Edit'])) { header('Location: /edit-page.php'); }
		if (!empty($_POST['select'])) {
		$addMore = 0;
		$jobQuery = $mysqli->query("SELECT jobsID FROM active_jobs WHERE assigned = 0 ORDER BY appointmentTime ASC");
		while($idArray = $jobQuery->fetch_array()) { $Assign[] = $idArray['jobsID']; }
		$jobQuery->close();
		foreach ($_POST['select'] as $selects => $select) {
        if (!empty($select)) { $jobArray[] = $select; }
		else { $jobArray[] = 0; }
		if (!empty($_POST['makeprimary'][$addMore])) { $primarySet[] = $_POST['makeprimary'][$addMore]; }
		else { $primarySet[] = 0; }
		$addMore++;
		}
		AssignJob($Assign, $jobArray, $addMore, $primarySet);
		}
		$emplist = "";
		$empCount = 0;
		$available = "SELECT id, employeeName FROM members WHERE working = 1 AND busy = 0";
		if ($getemployees = $mysqli->prepare($available)) {
		$getemployees->execute();
		$getemployees->bind_result($empID, $empN);
		while ($getemployees->fetch()) {
		$emplist.= '<option value="'.$empID.'">'.$empN.'</option>';
		}
		$getemployees->close();
		$jobQuery = "SELECT jobsID, appointmentTime, jobLocation, jobType, customerPhone FROM active_jobs 
		INNER JOIN customer_jobs ON active_jobs.customerID = customer_jobs.customerID
		WHERE active_jobs.assigned = 0 ORDER BY active_jobs.appointmentTime ASC";
		if ($userData = $mysqli->prepare($jobQuery)) {
		$userData->execute();
		$userData->bind_result($jobID, $aTime, $jobLoc, $jobType, $customerP);
		while ($userData->fetch()) {
		$empCount++;
		$dTime = substr($aTime, 0, 3);
		$mTime = substr($aTime, 3, -3);
		$dTime = intval($dTime);
		if ($dTime > 12) {
		$dTime = $dTime - 12;
		$appoint = $dTime.":".$mTime." PM";
		} else {
		$appoint = $dTime.":".$mTime." AM";
		}
		$returnJobs = "<div style='width:97%; margin-left:2%; border:solid 1px black; margin-top:1vw; height:100px; background:#FFF;'>
		<div style='float:left; width:8%; height:100px; border-right:solid 1px black;'><b>ID:</b> ".$jobID."</div><div style='float:left; width:60%; height:100px; margin-left:1%;'><b >Appointment:</b> ".$appoint."<br><b>Address:</b> ".$jobLoc."<br><b>Job Type:</b> ".$jobType."<br><b>Phone:</b> ".$customerP."</div><div style='height:100%;'><div class='WorkBox' style='float:right; width:20%; height:45%; margin-right:2%;'><b>Assign Work</b><span class='primSpan' style='display:none;float:right;margin-right:0.5vw;font-weight:bold;'>Primary</span><br><form id='worksubmit' method='post'><select name='select[]' class='selectEmp' form='worksubmit' style='float:left;clear:left;'><option value='0'></option>".$emplist."</select><input name='makeprimary[]' form='worksubmit' class='checkThis' type='checkbox' value='yes' style='display:none;float:right; margin-right:2vw;' /></form></div><div style='float:right;width:20%;height:25%;margin-right:1vw;'><form method='POST' ACTION='edit-page.php?EditID=".$jobID."'><button type='submit' name='Edit'/>Edit</button></form></div><div style='float:right; width:20%;height:35%;margin-right: 1vw;'><form method='POST' action='page_func/delete_job.php?deleteID=".$jobID."'><button type='submit' name='Delete'/>Delete</button></form></div></div></div>
		";
        echo $returnJobs;
		}
		echo "<script>
		$('.selectEmp').change(function(e){
		e.stopImmediatePropagation;
		var selectID = $(this).attr('id');
		var selectForm = $(this).val();
		$(this).parent().parent().find('.primSpan').show();
		$(this).parent().find('input').addClass('checkForm'+selectForm).show();
		});
		$('.checkThis').change(function() {
		var getData = $(this).attr('class').split(' ');
		var getLength = $('.'+getData[1]).length;
		if (getLength == 2) {
		$('.'+getData[1]).not(this).prop('checked', false);
		}
		})
		</script>";
		$userData->close();
		}
		printf ($mysqli->error);
		}
		?>
		</div>
		</div>
		<div style="float:right; width:8%; height:61vh; margin-right:2vw; border:1px solid black; background:rgba(255,255,255,0.9);"><input id="jobsubmit" form="worksubmit" type="submit" name="jobsubmit"/></div></div>
		<div style="float:left; width:40%; margin-left:2vw; height:31.7vh; margin-top:2vh; background:rgba(255,255,255,0.9);">
		<div style="float:left; width:95%; margin-left:5%; margin-top: 0.5vw;">
		<span style="font-size:1.5vw;">Add New Job</span>
		<form id="addjob" name="addjob" method="post" action="page_func/add-customer.php">
		<div style="float:left; width:100%; height:23.5vh;">
		<span style="float:left; line-height:4.5vh; font-weight:bold;">Appointment Time: </span><input type="text" name="appointedtime" placeholder="12:00" style="float:right;width:10%;height:3vh;margin-right:65%;"><select name="aMpM" style="float:right;width:10%;height:3vh;margin-right:53%;margin-top:-3.5vh;"><option value="AM">AM</option><option value="PM">PM</option></select><br>
		<span style="float:left; line-height:4.5vh; font-weight:bold; clear:both;">Phone: </span><input type="text" name="customerphone" placeholder="405-756-5309" style="float:left; width:30%; height:3vh; margin-left:6.05vw;">
		<button type='submit' name='addnewjob' style="float: right; width: 9.3vw;height: 4.4vh; margin-right: 2vw; margin-top: -0.3vw;"/>Add Job</button>
		<br>
		<span style="float:left;line-height:4.5vh; font-weight:bold; clear:both;">Customer Name: </span><input type="text" name="customername" placeholder="Jim Smith" style="float:right; width:70%; height:3vh; margin-right: 2vw"><br>
		<span style="float:left;line-height:4.5vh; font-weight:bold; clear:both;">Address: </span><input type="text" name="customeraddress" placeholder="1244 Sunshine Rd, Oklahoma City, OK" style="float:right; width:70%; height:3vh; margin-right: 2vw"><br>
		<span style="float:left;line-height:4.5vh; font-weight:bold; clear:both;">Email (Optional): </span><input type="text" name="customerEmail" placeholder="customer@elegantgarage.com" style="float:right; width:70%; height:3vh; margin-right: 2vw"><br>
		<span style="float:left; line-height:4.5vh; font-weight:bold; clear:both;">Job Type: </span><input type="text" name="jobdescription" placeholder="Clearing obstructions" style="float:right; width:70%; height:3vh; margin-right: 2vw"><br>
		</div>
		</form>
		</div></div>
		<div style="float: right;width: 33%;margin-right: 3vw;margin-top: 2vh; background:rgba(255,255,255,0.9); padding-bottom: 3.4vw;">
        <div style="padding-left:5%; padding-right:5%; width:90%; margin-top:1.5vw;">Choose a technician to submit a job to from the current job list and hit the submit button on the right after you have made your selections. To add a new job use the form to the left to make an addition.</div>
		</div>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>