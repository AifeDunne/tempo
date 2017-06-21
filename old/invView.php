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
		<style>
		.menuBar {
		float:left; 
		width:97%; 
		height:10%;
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
	<div style="float: left; width: 21%; height: 100vh; margin-top: -1vh; margin-left: -0.5vw; background:rgba(255,255,255,0.7);">
		<div id="Link1" class="menuBar" style="margin-bottom:1.85%; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/user_add.php" class="FPageLinks">Add User</a></div>
		<div id="Link2" class="menuBar" style="margin-bottom:1.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/tech_set.php" class="FPageLinks">Set Technician</a></div>
		<div id="Link3" class="menuBar" style="margin-bottom:1.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobAssign.php" class="FPageLinks">Set Jobs</a></div>
		<div id="Link4" class="menuBar" style="margin-bottom:1.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/protected_page2.php" class="FPageLinks">Home</a></div>
		<div id="Link5" class="menuBar" style="margin-bottom:1.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/ord_part.php" class="FPageLinks">Part Orders</a></div>
		<div id="Link6" class="menuBar" style="margin-bottom:1.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/est_labor.php" class="FPageLinks">Labor Estimate</a></div>
		<div id="Link7" class="menuBar" style="margin-bottom:1.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/progJobs.php" class="FPageLinks">On-Hold Jobs</a></div>
		<div id="Link8" class="menuBar" style="margin-bottom:1.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobComplete.php" class="FPageLinks">Completed Jobs</a></div>
		<div id="Link9" class="menuBar" style="height:9.9%; border-top:2px solid white; border-right:10px solid white;"><a href="/includes/logout.php" class="FPageLinks">Log Out</a></div>
	</div>
	<div style="float:left; height:61vh; width:75%; overflow-y:scroll; margin-left:2vw; border:1px solid black; background:#FFF;">
		<div id="JobBox" style="float:left; width:100%; height:100px;">
	<?php
	$billsX = '';
	if ($getInvoice = $mysqli->prepare("SELECT jobsID, totalCost, depositCost, employeeID, invoice_list.customerID, customerName FROM invoice_list
	INNER JOIN customer_jobs ON invoice_list.customerID = customer_jobs.customerID")) {
	$getInvoice->execute();
	$getInvoice->bind_result($iJob, $tCost, $dCost, $empID, $custID, $cName);
	while($getInvoice->fetch()) {
	$billsX.= "<div style='width:100%;height: 30%;border-bottom:1px solid black;'><div style='width:23%; margin-left:1%; float:left;'><span style='font-size:1.3vw;'>Client: <a href='lookup.php?customerID=".$custID."'>".$cName."</a></span></div><div style='width:15%; float:left;'><span style='font-size:1.3vw;'>Jobs ID: ".$iJob."</span></div><div style='width:20%; float:left;'><span style='font-size:1.3vw;'>Technician ID: #".$empID."</span></div><div style='width:20%; float:left;'><span style='font-size:1.3vw;'>Cost: $".$tCost."</span></div><div style='width:20%; float:left;'><span style='font-size:1.3vw;'>Deposit: $".$dCost."</span></div></div>";
			}
		$getInvoice->close();
		echo $billsX;
		} else { echo "No invoice orders"; }
	?>
	</div>
		</div>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>