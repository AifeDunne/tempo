<?php
include_once '../includes/system_function.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tempo - Workflow</title>
		<link href='http://fonts.googleapis.com/css?family=Quicksand|Marvel' rel='stylesheet' type='text/css'>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="css/workflow.css" />
		<script type="text/JavaScript" src="../js/sha512.js"></script> 
	</head>
    <body style="background:url(../background/BG1.jpg); background-size: cover  !important; background-repeat: no-repeat  !important; background-attachment: fixed  !important; background-position: center !important;">
	<div style="position:absolute; top:0; left:0; width:100%; height:100%;">
    <?php if (login_check($mysqli) == true) : ?>
	<?php
	$retrieveAll = RetriveGMen();
	$employees = $retrieveAll['allemployees'];
	$currentTaskCount = RetrieveVar('taskCount');
	$currentTaskCount = explode("-",$currentTaskCount);
	$currentTaskCount = $currentTaskCount[1];
	$taskCountVar = "var taskCount = ".$currentTaskCount.";";
	
	$userArray = array();
	foreach ($employees as $role) {
	$thisRole = $role['userRole'];
	$userID = $role['id'];
	if ($thisRole !== 'None' && $thisRole !== 'Super') {
	$userArray[$thisRole][$userID] = $role['name'];
		}
	}
	
	$nameArray = '';
	foreach ($employees as $names) {
	if ($names['userlevel'] !== '2' || $names['userlevel'] !== 2) {	$nameArray.= '"'.$names['name'].'",'; }
	}
	$nameArray = substr($nameArray, 0, -1);
	$nameArray = "[".$nameArray."]";
	?>
	<div id="navBar">
	<div id="triggerMenu" class="navM" style="position:absolute; top:0; left:0; height:7vh; width:7vw; z-index:20; cursor:pointer;"></div>
	<div id="PageNavigation" class="navM">Menu</div>
	<div class="quickLinks" id="s1">Current Tasks</div>
	<div class="quickLinks" id="s2">Incomplete Tasks</div>
	<div class="quickLinks" id="s3">Task History</div>
	<div class="quickLinks" id="s4">Unassign</div>
	<div id="navMenu" class="navM"><div style="height:2vh; float:left; clear:both;"></div><a class="navLinks navM" href="../index.php">Home</a><a class="navLinks navM" href="../staff-manage/">Staff Manager</a><a class="navLinks navM" href="#">MyDesk</a><a class="navLinks navM" href="#">Finance</a></div>
	<script>
	var crntTab, scheduleDetail;
	crntTab = "uTab1";
	scheduleDetail = 0;
	
	function TabStart() {
		var iTab = $(this).attr("id");
		if (iTab != crntTab) {
		$(".userBox").fadeOut(200);
		iTab = iTab.substring(4);
		$("#"+crntTab).removeClass("activeTab").addClass("inactiveTab");
		$("#uTab"+iTab).removeClass("inactiveTab").addClass("activeTab");
		crntTab = "uTab"+iTab;
		setTimeout(function() {
		$("#box"+iTab).fadeIn(200);
		}, 200);
		} else { return false; }
		}
	function fixTab() {
	var findTab = $(".activeTab").attr("id");
	$("#"+findTab).removeClass("activeTab").addClass("inactiveTab");
	}
	
	$("#triggerMenu").mouseenter(function() {
	$("#navMenu").stop().animate({"height":"24vh"},350);
	$("div").not(".navM").mouseover(function(e) {
	e.stopImmediatePropagation();
	if (!$(e.target).is(".navM")) { $("#navMenu").stop().animate({"height":"0"}, 350); 
			}
		});
	});
	
	function executeSub(IDPage) {
	$(".addBoxTabs").unbind();
	if (IDPage == 1) { subpage1(); }
	else if (IDPage == 2) { subpage2(); }
	else if (IDPage == 3) { subpage3(); }
	else if (IDPage == 4) { subpage4(); }
	$(".addBoxTabs").on("click", TabStart);
	}
	
	var noDouble;
	noDouble = 's1';
	$(".quickLinks").on("click", function() { 
	var nextPage = $(this).attr("id");
	if (noDouble != nextPage) {
	noDouble = nextPage;
	nextPage = nextPage.substring(1);
	var newPage = "#page"+nextPage;
	$(".subpage").fadeOut(300);
	setTimeout(function() { $(newPage).fadeIn(300); executeSub(nextPage); },300);
	} else { return false; }
	});
	var dumpData1 = 0;
	var hasAddedUser = [0,0,0,0];
	
	<?php
	global $taskCountVar, $nameArray;
	echo $taskCountVar;
	echo "var nameArray = ".$nameArray.";";
	?>
	</script>
	</div>
	<div id="page1" class="subpage">
	<div id="jobList" class="splitPage">
	<div id="jobWrapper">
		<div id="JobBox">
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
		$returnJobs = "<div class='newTask'><div class='taskID'><div class='taskNumber'>#".$jobID."</div></div><div class='taskDetails'><b>Task:</b> ".$jobType."<br><b>Time:</b> ".$appoint."<br><b>Address:</b> ".$jobLoc."<br><b>Phone:</b> ".$customerP."</div><div class='assignTaskBox'><div class='WorkBox'><span style='font-weight:bold; margin-left:1vw;'>Delegate</span><span class='primSpan' style='display:none;float:right;margin-right:0.5vw;font-weight:bold;'>Primary</span><br><form id='worksubmit' method='post'><select name='select[]' class='selectEmp' form='worksubmit' style='float:left;clear:left;'><option value='0'></option>".$emplist."</select><input name='makeprimary[]' form='worksubmit' class='checkThis' type='checkbox' value='yes' style='display:none;float:right; margin-right:2vw;' /></form></div><div class='taskForm'><form method='POST' ACTION='edit-page.php?EditID=".$jobID."'><button class='taskBtn' type='submit' name='Edit'>Edit</button></form></div><div class='delTask'><form method='POST' action='page_func/delete_job.php?deleteID=".$jobID."'><button class='taskBtn' type='submit' name='Delete'>Delete</button></form></div></div></div>";
        echo $returnJobs;
		}
		echo "<script>
		function subpage1() {
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
		}
		</script>";
		$userData->close();
		}
		printf ($mysqli->error);
		}
		?>
		</div>
		</div>
		<div class="taskOverlay">
		<div id="addTask">
		<div id="taskWrapper">
		<span id="taskWrapperTitle">Work Order</span>
		<div id="taskForm">
		<div class='newTaskLine'><span class="addTaskElem">Client: </span><input type="text" id="customername" name="customername" placeholder="Jim Smith"></div>
		<div class='newTaskLine'><span class="addTaskElem">Time: </span><input id="appointedtime" type="text" name="appointedtime" placeholder="12:00"><select id="aMpM" name="aMpM"><option value="AM">AM</option><option value="PM">PM</option></select></div>
		<div class='newTaskLine'><span class="addTaskElem">Phone: </span><input type="text" id="customerphone" name="customerphone" placeholder="405-756-5309"></div>
		<div class='newTaskLine'><span class="addTaskElem">Address: </span><input type="text" id="customeraddress" name="customeraddress" placeholder="1244 Sunshine Rd, Oklahoma City, OK"></div>
		<div class='newTaskLine'><span class="addTaskElem">Description: </span><input type="text" id="jobdescription" name="jobdescription" placeholder="Clearing obstructions"></div>
		<button id='addnewjob' name='addnewjob'>Add To Queue</button>
		<script>
		$("#addnewjob").on("click", function() {
		var aTime = $("#appointedtime").val();
		var aDayTime = $("#aMpM").val();
		var cPhone = $("#customerphone").val();
		var cName = $("#customername").val();
		var cAddress = $("#customeraddress").val();
		var cDesc = $("#jobdescription").val();
			$.ajax({
				type: "POST",
				url: "page_func/add-customer.php", 
				data: { appointedtime: aTime, aMpM: aDayTime, customerphone: cPhone, customername: cName, customeraddress: cAddress, jobdescription: cDesc },
				success: function () {
				taskCount++;
				var taskTime = aTime+" "+aDayTime;
				var empList = "test test test";
				var addJobToList = "<div class='newTask'><div class='taskID'><div class='taskNumber'>#"+taskCount+"</div></div><div class='taskDetails'>><b>Task:</b> "+cDesc+"<br><b>Appointment:</b> "+taskTime+"<br><b>Address:</b> "+cAddress+"<br><b>Phone:</b> "+cPhone+"</div><div class='assignTaskBox'><div class='WorkBox'><span style='font-weight:bold; margin-left:1vw;'>Delegate</span><span class='primSpan' style='display:none;float:right;margin-right:0.5vw;font-weight:bold;'>Primary</span><br><form id='worksubmit' method='post'><select name='select[]' class='selectEmp' form='worksubmit' style='float:left;clear:left;'><option value='0'></option>"+empList+"</select><input name='makeprimary[]' form='worksubmit' class='checkThis' type='checkbox' value='yes' style='display:none;float:right; margin-right:2vw;' /></form></div><div class='taskForm'><form method='POST' ACTION='edit-page.php?EditID="+taskCount+"'><button class='taskBtn' type='submit' name='Edit'>Edit</button></form></div><div class='delTask'><form method='POST' action='page_func/delete_job.php?deleteID="+taskCount+"'><button class='taskBtn' type='submit' name='Delete'>Delete</button></form></div></div></div>";
				$("#JobBox").append(addJobToList);
				$('#aTime').val('');
				$('#aDayTime').val('');
				$('#cPhone').val('');
				$('#cName').val('');
				$('#cAddress').val('');
				$('#cDesc').val('');
				}
			});
		});
		</script>
		</div>
		</div></div>
		<div id="sideDesc">
        <div style="padding-left:5%; padding-right:5%; width:90%; margin-top:1.5vw;"></div>
					</div>
				</div>
			</div>
		</div>
			
	<div id="page2" class="subpage" style="display:none;">
		<div id="taskProg">
		<div id="CompleteBox" style="float:left; width:100%; height:100px;">
            <?php
	$completeJobs = '';
	$fullJobs = '';
	$jobIDArray = '';
	$jobsArray = array();
	$jobArr = array();
	$safeArr = array();
	$empArr = array();
	$jobCount = 0;
	$whileCount = 0;
	$getComplete = $mysqli->prepare("SELECT complete_jobs.jobsID, complete_jobs.customerID, customer_jobs.customerName, members.employeeName, invoice_list.depositCost, invoice_list.totalCost, checkList.picNumber FROM complete_jobs
	LEFT JOIN invoice_list ON complete_jobs.jobsID = invoice_list.jobsID
	LEFT JOIN customer_jobs ON complete_jobs.customerID = customer_jobs.customerID
	LEFT JOIN members ON complete_jobs.employeeID = members.id
	LEFT JOIN checklist ON complete_jobs.safetyID = checkList.checkID
	WHERE complete_jobs.finished = 0 AND complete_jobs.reassigned = 0
	ORDER BY complete_jobs.jobsID ASC");
	$getComplete->execute();
	$getComplete->bind_result($jID, $custID, $compID, $fEmployee, $compdCost, $comptCost, $checkID);
	while($getComplete->fetch()) {
	$jobArr[] = $jID;
	$jobIDArray.= $jID.",";
	$safeArr[$jID] = $checkID;
	$empArr[$jID] = "<span style='font-size:1.1vw;'><b>Tech:</b> ".$fEmployee."</span>";
	$jobsArray[$jID] = "<div style='width:100%;height:55%;border-bottom:1px solid black;'><div style='width:20%; float:left; margin-left:0.2vw;'><span style='font-size:1.1vw; font-weight:bold;'>".$jID.". </span><a href='lookup.php?customerID=".$custID."' style='font-size:1.1vw; font-weight:bold; color:#000;'>".$compID."</a></div><div style='width:13%; float:left;'><span style='font-size:1.1vw;'><b>Deposit:</b> $".$compdCost."</span></div><div style='width:12%; float:left;'><span style='font-size:1.1vw;'><b>Cost:</b> $".$comptCost."</span></div>"; }
	$jobIDArray = substr($jobIDArray, 0, -1);
	$getComplete->close();
	
	$partsArray = array();
	$orderArray = array();
	$allParts = "SELECT jobsID, listID, partsUsed, partsOrder FROM partsList WHERE jobsID in (".$jobIDArray.") ORDER BY jobsID ASC";
	$getParts = $mysqli->prepare($allParts);
	$getParts->execute();
	$getParts->bind_result($grabP, $listID, $usedP, $pOrder);
	while($getParts->fetch()) {
		if (!empty($usedP)) {
			$allUsed = '';
			$usedPA = explode(',', $usedP);
			foreach ($usedPA as $used) { $allUsed.= "'".$used."',"; }
			$allUsed = substr($allUsed, 0, -1);
			$partsArray[$grabP] = $allUsed;
		}
		if (!empty($pOrder)) {
			$allOrder = '';
			$pOrderA = explode(',', $pOrder);
			foreach ($pOrderA as $orders) { $allOrder.= "'".$orders."',"; }
			$allOrder = substr($allOrder, 0, -1); 
			$orderArray[$grabP] = $allOrder;
		} else { $orderArray[$grabP] = 'None'; }
	}
	$getParts->close();
	
	function GetParts($findpart) {
	$partList = '';
	global $mysqli;
	$listParts = "SELECT realName FROM partsCatalog WHERE partName in (".$findpart.")";
	$nameParts = $mysqli->prepare($listParts);
	$nameParts->execute();
	$nameParts->bind_result($finalName);
	while($nameParts->fetch()) { $partList.= "<span style='float:left; clear:left;'>".$finalName."</span>"; }
	$nameParts->close();
	return $partList;
	}
		$fScript = "<script>function subpage2() {";
		$fCodeP = "{";
		$fCodeO = "{";

	foreach ($jobsArray as $progJobs) {
	$retID = array_search ($progJobs, $jobsArray);
	$unpackP = GetParts($partsArray[$retID]);
	$fCodeP.= $retID.': "'.$unpackP.'",';
		if ($orderArray[$retID] !== 'None') {
		$unpackO = GetParts($orderArray[$retID]);
		$fCodeO.= $retID.': "'.$unpackO.'",';
		$orderLabel = "<span style='font-size:1.1vw; font-weight:bold;'>Order: </span><span style='font-size:1.1vw; color:blue; text-decoration:underline; cursor:pointer;' id='order".$retID."' class='hoverLink hoverO'>View</span>"; } else { $orderLabel = "<span style='font-size:1.1vw;'><b>Order:</b> None</span>"; }
		if (!empty($safeArr[$retID])) {
		$safetyLabel = "<span style='font-size:1.1vw; font-weight:bold;'>Notes: </span><a href='noteGrab.php?cJob=".$retID."&noteID=".$safeArr[$retID]."' style='font-size:1.1vw; color:blue; text-decoration:underline; cursor:pointer;'>View</a>"; } else { $safetyLabel = "<span style='font-size:1.1vw;'><b>Notes:</b> None</span>"; }
	$fullJobs.= $progJobs."<div style='width:9%; float:left;'><span style='font-size:1.1vw; font-weight:bold;'>Used: </span><span style='font-size:1.1vw; color:blue; text-decoration:underline; cursor:pointer;' id='used".$retID."' class='hoverLink hoverP'>View</span></div><div style='width:10%; float:left;'>".$orderLabel."</div><div style='width:10%; float:left;'>".$safetyLabel."</div><div style='width:19%; float:left;'>".$empArr[$retID]."</div><form id='resumejob".$retID."' name='resumeJob".$retID."' method='post' action='insertResume.php?resumeThisJob=".$retID."'><button type='submit' style='float:left; clear:both; margin-left:1vw; margin-top:5px;'>Resume Job</button></form></div>";
	$jobCount++;
	}
	$fCode = '';
	if (strlen($fCodeP) !== 1) { $fCodeP = substr($fCodeP, 0, -1); $fCodeP.= "}"; $fCode.= "var partArray = ".$fCodeP.";"; }
	if (strlen($fCodeO) !== 1) { $fCodeO = substr($fCodeO, 0, -1); $fCodeO.= "}"; $fCode.= "var orderArray = ".$fCodeO.";"; }
	if (!empty($fCode)) { $fScript.= $fCode; }
	$fScript.= "$('.hoverLink').mouseenter(function() {
		var linkPos = $(this).position(); var leftPos = linkPos.left; var topPos = linkPos.top;
		var getID = $(this).attr('class').split(' ');
		var getNum = $(this).attr('id');
		getID = getID[1];
		var divType, infoType;
		if (getID == 'hoverP') { divType = 'hoverParts'; getNum = getNum.substring(4); getNum = parseInt(getNum); infoType = partArray[getNum]; }
		if (getID == 'hoverO') { divType = 'hoverOrder'; getNum = getNum.substring(5); getNum = parseInt(getNum); infoType = orderArray[getNum]; }
		$('body').append(".'"'."<div class=".'"'."+divType+".'"'." style='position:absolute; left:".'"'."+leftPos+".'"'."px; top:".'"'."+topPos+".'"'."px; background:#FFF; border:1px solid black; cursor:pointer; padding:0.5vw;'>".'"'."+infoType+".'"'."</div>".'"'.");
		$('.'+divType).mouseleave(function() { $('.'+divType).remove(); });
		});
		}</script>";
	echo $fullJobs.$fScript."</div>";
	?>
	</div>
			</div>
			
		<div id="page3" class="subpage" style="display:none;">
		<div id="compWrapper">
		<div id="CompleteBox" style="float:left; width:100%; height:100px;">
            <?php
	$completeCJobs = '';
	$fullCJobs = '';
	$jobIDCArray = '';
	$jobsCArray = array();
	$jobCArr = array();
	$safeCArr = array();
	$empCArr = array();
	$jobCCount = 0;
	$whileCCount = 0;
	setlocale(LC_MONETARY, 'en_US');
	$getCComplete = $mysqli->prepare("SELECT complete_jobs.jobsID, complete_jobs.customerID, customer_jobs.customerName, members.employeeName, invoice_list.totalCost, invoice_list.addCosts, checkList.picNumber FROM complete_jobs
	LEFT JOIN invoice_list ON complete_jobs.jobsID = invoice_list.jobsID
	LEFT JOIN customer_jobs ON complete_jobs.customerID = customer_jobs.customerID
	LEFT JOIN members ON complete_jobs.employeeID = members.id
	LEFT JOIN checkList ON complete_jobs.safetyID = checkList.checkID
	WHERE complete_jobs.finished = 1
	ORDER BY complete_jobs.jobsID ASC;");
	$getCComplete->execute();
	$getCComplete->bind_result($jCID, $custCID, $compCID, $fEmployeeC, $comptCCost, $aCCost, $checkIDC);
	while($getCComplete->fetch()) {
	$jobCArr[] = $jCID;
	$jobIDCArray.= $jCID.",";
	$safeCArr[$jCID] = $checkIDC;
	$comptCCost = $comptCCost + $aCCost;
	$empCArr[$jCID] = "<span style='font-size:1.1vw;'><b>Tech:</b> ".$fEmployeeC."</span>";
	$jobsCArray[] = "<div style='width:100%;height:30%;border-bottom:1px solid black;'><div style='width:20%; float:left; margin-left:0.2vw;'><span style='font-size:1.1vw; font-weight:bold;'>".$jCID.". </span><a href='lookup.php?customerID=".$custCID."' style='font-size:1.1vw; font-weight:bold; color:#000;'>".$compCID."</a></div><div style='width:15%; float:left;'><span style='font-size:1.1vw;'><b>Total Cost:</b> $".$comptCCost."</span></div><div style='width:13%; float:left;'><span style='font-size:1.1vw;'><b>Added:</b> $".$aCCost."</span></div>"; }
	$jobIDCArray = substr($jobIDCArray, 0, -1);
	$getCComplete->close();
	
	$allCUsed = '';
	$partsCArray = array();
	$listIDCArr = array();
	$arrayCSet = 0;
	$allCParts = "SELECT jobsID, listID, partsUsed FROM partsList WHERE jobsID in (".$jobIDCArray.")";
	$getCParts = $mysqli->prepare($allCParts);
	$getCParts->execute();
	$getCParts->bind_result($grabPC, $listIDC, $usedPC);
	while($getCParts->fetch()) {
	$usedPC = explode(',', $usedPC);
	foreach ($usedPC as $usedC) { $allCUsed.= "'".$usedC."',"; }
	if (!empty($allCUsed)) { $allCUsed = substr($allCUsed, 0, -1); $partsCArray[$arrayCSet] = $allCUsed; $allCUsed = '';}
	$listIDCArr[$grabPC] = $listIDC;
	$whileCCount++;
	$arrayCSet++;
	}
	$getCParts->close();
	$whileCCount = $whileCCount - 1;
	
	function GetPartsC($findpartC) {
	$partCList = '';
	global $mysqli;
	$listCParts = "SELECT realName FROM partsCatalog WHERE partName in (".$findpartC.")";
	$nameCParts = $mysqli->prepare($listCParts);
	$nameCParts->execute();
	$nameCParts->bind_result($finalCName);
	while($nameCParts->fetch()) { $partCList.= "<span style='float:left; clear:left;'>".$finalCName."</span>"; }
	$nameCParts->close();
	return $partCList;
	}
	$finalCParts = array();
	for ($h = 0; $h <= $whileCCount; $h++) {
	$getCPartXX = GetPartsC($partsCArray[$h]);
	$finalCParts[] = $getCPartXX;
	}
	$fCScript = '<script>function subpage3() {';
		function AddScript($getCID, $finCList) {
		$fCCode = "$('#used".$getCID."').mouseenter(function() {
		var linkPos = $(this).position(); var leftPos = linkPos.left; var topPos = linkPos.top;
		$('body').append(".'"'."<div class='hoverParts' style='position:absolute; left:".'"'."+leftPos+".'"'."px; top:".'"'."+topPos+".'"'."px; background:#FFF; border:1px solid black; cursor:pointer; padding:0.5vw;'>".$finCList."</div>".'"'.");
		$('.hoverParts').mouseleave(function() { $('.hoverParts').remove(); });
		});"; 
		return $fCCode; }

	foreach ($jobsCArray as $progCJobs) {
	$retCID = $jobCArr[$jobCCount];
	$partCID = $listIDCArr[$retCID];
	$fCScript.= AddScript($partCID,$finalCParts[$jobCCount]);
		if (!empty($safeCArr[$retCID])) {
		$safetyLabelC = "<span style='font-size:1.1vw; font-weight:bold;'>Notes: </span><a href='noteGrab.php?cJob=".$retCID."&noteID=".$safeCArr[$retCID]."' style='font-size:1.1vw; color:blue; text-decoration:underline; cursor:pointer;'>View</a>"; } else { $safetyLabelC = "<span style='font-size:1.1vw;'><b>Notes:</b> None</span>"; }
	$fullCJobs.= $progCJobs."<div style='width:9%; float:left;'><span style='font-size:1.1vw; font-weight:bold;'>Used: </span><span style='font-size:1.1vw; color:blue; text-decoration:underline; cursor:pointer;' id='used".$partCID."'>View</span></div><div style='width:10%; float:left;'>".$safetyLabelC."</div><div style='width:19%; float:left;'>".$empCArr[$retCID]."</div></div>";
	$jobCCount++;
	}
	$fCScript.= '}</script>';
	echo $fullCJobs.$fCScript."</div>";
	?>
	</div>
		</div>
		
		<div id="page4" class="subpage" style="display:none;">
		<script>
		function subpage4() {
		$(".userBox").hide();
		fixTab();
		crntTab = "uTab10";
		$("#uTab10").removeClass("inactiveTab").addClass("activeTab");
		$("#box10").show();
		$("#colorPicker").spectrum({color: "#f00"});
				
		function requiredField2() {
		var getVertPos2 = $("#createRole").position();
		getVertPos2 = getVertPos2.top;
		getVertPos2 = getVertPos2 - 30;
		$("#box10").append("<div id='warning' style='position:absolute; top:"+getVertPos2+"px; left:0; width:100%; height:20px; font-size:1vw; color:red; text-align:center; display:none; font-family: Quicksand;'>You must complete all required information!</div>");
		$("#warning").fadeIn(200).delay(800).fadeOut(200);
		setTimeout(function() { warnOn2 = 0; $("#warning").remove(); }, 1200);
		}
		
		var warnOn2 = 0;
		var processingData2 = 0;
		$("#addRoleButton").on("click", function() {
		if (processingData2 == 0) {
		var roleTitle = $("#roleAdd").val();
			var roleType = $("#roleType").val();
			var roleColor = $("#colorPicker").spectrum("get");
			roleColor = roleColor.toHexString();
			var roleDescript = $("#roleD").val();
		var checkReqAdd2 = [roleTitle, roleType, roleColor, roleDescript];
		for (h=0; h<=4; h++) {	var thisValue2 = checkReqAdd2[h];	
		if (thisValue2 == '') { warnOn2 = 1; } }
		if (warnOn2 == 1) { requiredField2(); return false; }
		else { processingData2 = 1;
				$.ajax({
					type: "POST",
					url: "../includes/updateRequest.php", 
					data: { dataRequest: "AddRole", roleID: roleCount, roleName: roleTitle, roleCat: roleType, roleBG: roleColor, roleDesc: roleDescript},
					success: function (data) {
					$("#allRoles").html(data);
					console.log(data);
					processingData2 = 0;
					}
				});
				}
				}
			});
		}
		</script>
		<div id="allRoles"></div>
		<div class="rightBox">
			<div id="tabBox" style="position:absolute; top:0; right:0; width:95%; height:30px;"><div id="uTab10" class="addBoxTabs activeTab">Create</div><div id="uTab11" class="addBoxTabs inactiveTab">Edit</div><div id="uTab12" class="addBoxTabs inactiveTab">Settings</div></div>
		<div id="box10" class="userBox">
		<div id="createRole">
		<span id="roleTitle">Create New Role</span>
		<div style="float:left; width:100%; height:1.7vh; clear:both;"></div>
		<div class="roleForm"><span class="addSpan">Role Title:</span><input id="roleAdd" type="text" style="float:left; width:66%; height:3vh; margin-left:0.9vw;" placeholder="ex. Tour Guide, Personal Trainer, Repairman"/></div>
		<div class="roleForm"><span class="addSpan" style="line-height:3.3vh">Role Type:</span><select id="roleType" style="float:left; margin-left:0.5vw; width:40%; height:3vh;">
		</select>
		<div class="roleForm" style="margin-top:1.5vh;"><span class="addSpan" style="margin-right:0.5vw;">Assign Color:</span><input type="text" id="colorPicker"/></div>
		<div class="roleForm" style="margin-top: -0.7vh;"><textarea id="roleD" style="float:left; width:90%; margin-left: 0.8vw; height:9vh;" cols="30" rows="10" placeholder="Brief description of this role."/></textarea></div>
		<button id="addRoleButton" style="float:left; width:80%; height:5.5vh; margin-left:2vw; clear:both; background:#f6f6f6; box-shadow: 3px 3px 0 #DDD;">Add Role</button>
		</div>
		</div>
		<div id="box11" class="userBox"></div>
		<div id="box12" class="userBox"></div>
		</div>
		</div>
		<script>
		$(document).ready(function() { executeSub(1); });
		</script>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/login.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>