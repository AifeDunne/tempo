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
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	</head>
	<?php if (login_check($mysqli) == true) : ?>
    <body>
	<div style="float:left;width:70%;margin-top:3vh;margin-left:7vw;">
    <?php
	if (!empty($_GET['reassign'])) {
	require_once 'page_func/continueVoice.php';
	} else {
	$findYN = $mysqli->query("SELECT invoiceID FROM invoice_list WHERE jobsID = ".$_SESSION['primary_job']);
	$getYN = $findYN->fetch_array();
	$isThisYN = $getYN['invoiceID'];
	if (!empty($isThisYN)) {
	echo "You have already completed the invoice click the View Invoice link to see it.";
	} else {
	$safeArray = array('Springs', 'Hinges', 'Struts', 'Torsion Tube', 'Drums', 'Set of Rollers', 'Track', 'Cables', 'Pulley', 'Panels', 'End Bearing', 'Center Bearing', 'Center Bearing Plate', 'Top Fixture', 'Bottom Fixture', 'Door Hardware Kit', 'Slide Lock', 'T Lock', 'Vault Release', 'Windows', 'Rail', 'Trolley', 'Drive Gears', 'Force Adjustment', 'Limit Switch', 'Capacitor', 'Safety Eyes', 'Circuit Board', 'External Receiver', 'Wireless Keypad', 'Remote', 'Wall Button');
	$queryRepair = "SELECT customerID, listID, partsOrder, partsUsed FROM partsList
	INNER JOIN accepted_jobs ON partsList.jobsID = accepted_jobs.jobsID
	WHERE partsList.jobsID = ".$_SESSION['primary_job'];
	$findRepair = $mysqli->query($queryRepair);
	$getRepair = $findRepair->fetch_array();
	$uParts = $getRepair['partsUsed'];
	$oParts = $getRepair['partsOrder'];
	if (!empty($uParts)) {	$uParts = explode(',', $uParts); }
	if (!empty($oParts)) { $oParts = explode(',', $oParts); }
	$custID = $getRepair['customerID'];
	$listID = $getRepair['listID'];
	$repairParts = '';
	$billForm = '';
	$billParts = '';
	$TotalPAmount = 0;
	
	if (!empty($uParts)) {
	$billParts.= "<b>Parts Used</b><br><hr>";
	$priceUQuery = "SELECT partCost, realName FROM partsCatalog WHERE partName in (";
	foreach ($uParts as $uPart) { $priceUQuery.= "'".$uPart."',"; }
	$priceUQuery = substr($priceUQuery, 0, -1);
	$priceUQuery.= ")";
	$getUPrice = $mysqli->prepare($priceUQuery);
	$getUPrice->execute();
	$getUPrice->bind_result($pPrice, $pName);
	while ($getUPrice->fetch()) {
	$TotalPAmount = $TotalPAmount + $pPrice;
	$billParts.= $pName." - $".$pPrice."<br>";}
	$billParts.="<br>";
	$getUPrice->close();
	}
	
	if (!empty($oParts)) {
	$billParts.= "<b>Parts Ordered</b><br><hr>";
	$priceQuery = "SELECT partCost, realName FROM partsCatalog WHERE partName in (";
	foreach ($oParts as $oPart) { $priceQuery.= "'".$oPart."',"; }
	$priceQuery = substr($priceQuery, 0, -1);
	$priceQuery.= ")";
	$getPrice = $mysqli->prepare($priceQuery);
	$getPrice->execute();
	$getPrice->bind_result($oPrice, $oName);
	while ($getPrice->fetch()) {
	$TotalPAmount = $TotalPAmount + $oPrice;
	$billParts.= $oName." - $".$oPrice."<br>";}
	$getPrice->close();
	} else {$bswitch = 1;}
	
	if ($TotalPAmount != 0) {
	$billForm.= '<div style="float:left; margin-top:2vh; clear:left;">'.$billParts.'<div>';
	$billForm.= '<div style="float:left; margin-top:3vh; clear:left;"><b>Cost of Parts:</b> $'.$TotalPAmount.'</div>';
	$SalesTax = $TotalPAmount * 0.045;
	$SalesTax = round($SalesTax, 2);
	$billForm.= '<div style="float:left; margin-top:0.5vh; clear:left;"><b>Sales Tax:</b> $'.$SalesTax.'</div>';
	$TotalCost = $TotalPAmount + $SalesTax;
	$TotalCost = round($TotalCost, 2);
	$billForm.= '<div style="float:left; margin-top:0.5vh; clear:left;"><b>Total Cost:</b> $'.$TotalCost.'</div>';
	if ($bswitch != 1) {
	$DepositCost = $TotalCost/2;
	$DepositCost = round($DepositCost, 2);
	$billForm.= '<div style="float:left; margin-top:3vh; clear:left;"><b>Deposit Due:</b> $'.$DepositCost.'</div>';}
	}
	if ($bswitch === 1) { $jobIsComplete = 'completeJob=1';	$subDep = '';}
	else { $jobIsComplete = 'completeJob=0'; $subDep = "&depositID=".$DepositCost; }
	$billForm.= "<div style='margin-top:2vh; width: 18vw; float:left; clear:left;'><form name='addinvoice' id='addinvoice' method='post' action='page_func/addinvoice.php?primaryID=".$_SESSION['primary_job']."&costID=".$TotalCost.$subDep."&empID=".$_SESSION['employeeID']."&customerID=".$custID."&".$jobIsComplete."&unfinish=0'><span style='float:left; line-height:4.5vh; font-weight:bold; clear:left;'>Additional Costs: $</span><input type='text' name='addcost' style='float:left; width:20%; height:3vh;'><br><span style='float:left; line-height:4.5vh; font-weight:bold; clear:left;'>Comments: </span><input type='textarea' name='comments' style='float:left;margin-top: 1vh;margin-left: 0.2vw;height: 6vh;'></div></form><button form='addinvoice' name='submitvoice' style='height:4vh; float:left; margin-top:2vh; clear:left;' type='submit' />Submit Invoice</button>";
	echo $billForm;
		}
	}
	?>
	</div>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>