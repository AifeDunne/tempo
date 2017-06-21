<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
 
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
		<div id="Link2" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/tech_set.php" class="FPageLinks">Set Technician</a></div>
		<div id="Link3" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobAssign.php" class="FPageLinks">Set Jobs</a></div>
		<div id="Link4" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/job_add.php" class="FPageLinks">Unassign Jobs</a></div>
		<div id="Link5" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/protected_page2.php" class="FPageLinks">Home</a></div>
		<div id="Link6" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobComplete.php" class="FPageLinks">Completed Jobs</a></div>
		<div id="Link7" class="menuBar" style="height:11%; border-top:2px solid white; border-right:10px solid white;"><a href="/includes/logout.php" class="FPageLinks">Log Out</a></div>
	</div>
	<div style='width: 79.5%;font-size: 1.5vw;line-height: 5vh;margin-left:0;font-weight: bold;background: #FFF;float: left;'>Jobs On-Hold</div>
	<div style="float:left; height:61vh; width:79.3%; overflow-y:scroll; margin-left:0; border:1px solid black; background:#FFF;">
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
	LEFT JOIN checkList ON complete_jobs.safetyID = checkList.checkID
	WHERE complete_jobs.finished = 0 AND complete_jobs.reassigned = 0
	ORDER BY complete_jobs.jobsID ASC;");
	$getComplete->execute();
	$getComplete->bind_result($jID, $custID, $compID, $fEmployee, $compdCost, $comptCost, $checkID);
	while($getComplete->fetch()) {
	$jobArr[] = $jID;
	$jobIDArray.= $jID.",";
	$safeArr[$jID] = $checkID;
	$empArr[$jID] = "<span style='font-size:1.1vw;'><b>Tech:</b> ".$fEmployee."</span>";
	$jobsArray[$jID] = "<div style='width:100%;height:55%;border-bottom:1px solid black;'><div style='width:20%; float:left; margin-left:0.2vw;'><span style='font-size:1.1vw; font-weight:bold;'>".$jID.". </span><a href='lookup.php?customerID=".$custID."' style='font-size:1.1vw; font-weight:bold;'>".$compID."</a></div><div style='width:13%; float:left;'><span style='font-size:1.1vw;'><b>Deposit:</b> $".$compdCost."</span></div><div style='width:12%; float:left;'><span style='font-size:1.1vw;'><b>Cost:</b> $".$comptCost."</span></div>"; }
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
		$fScript = "<script>";
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
		</script>";
	echo $fullJobs.$fScript."</div>";
	?>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>