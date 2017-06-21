<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
 
sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Protected Page</title>
     <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    	<style>
		#menutext {
		float: left;
		margin-top: 58.4vh;
		margin-left: -14vw;
		height: 12vh;
		letter-spacing: 1.5vw;
		font-size: 6vh;
		text-transform: uppercase;
		width: 30vw;
		color: #FFF;
		transform: rotate(90deg);
		-webkit-transform: rotate(90deg);
		-moz-transform: rotate(90deg);
		-o-transform: rotate(90deg);
		-ms-transform: rotate(90deg);
		cursor:pointer;
		}
		</style>
	</head>
	<?php if (login_check($mysqli) == true) : ?>
    <body>
	<script>
	$(document).ready(function() {
	var ClickCount;
	ClickCount = 0;
	$("#FullMenu").click(function() {
	if (ClickCount == 0) { ClickCount = 1;
	$("#FullMenu").stop().animate({"width":"20%"}, 500);
	$("#menutext").stop().animate({"opacity":"0"}, 350);
	setTimeout(function() {
	$("#MenuContents").stop().animate({"opacity":"1"}, 500).delay(500).css({"display":"block"});
	$("#menutext").css({"display":"none"});
	}, 500);
	} else if (ClickCount == 1) { ClickCount = 0;
	$("#MenuContents").stop().animate({"opacity":"0"}, 350);
	setTimeout(function() {	$("#FullMenu").stop().animate({"width":"5%"}, 350);
	$("#menutext").css({"display":"block"}).delay(200).stop().animate({"opacity":"1"}, 350);
	$("#MenuContents").css({"display":"none"});}, 500);
	}
		});
	});
	</script>
	<div id="FullMenu" style="position:fixed; float:left; width:5%; height:98vh; background: #000;">
			<div id="menutext">MENU</div>
			<div id="MenuContents" style="visibility:none; opacity:0;">
			<div id="Link1" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="start_safety.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Start Safety Check</a></div>
			<div id="Link2" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="part_add.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Add Parts</a></div>
			<div id="Link3" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="create_voice.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Create Invoice</a></div>
			<div id="Link4" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="paymentProcess.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Process Payment</a></div>
			<div id="Link5" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="vw_safety.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">View Safety Check</a></div>
			<div id="Link6" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="/protected_page.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Home</a></div>
			<div id="Link7" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="vw_partorder.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">View Part Order</a></div>
			<div id="Link8" style="float:left; width:100%; height:11%; background:#000; color:#FFF;"><a href="/includes/logout.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Log Out</a></div></div>
			</div>
		</div>
	<div style="float:left; width:70%; margin-left:9vw;">
	<?php
	$findVoice = $mysqli->query("SELECT invoiceID, totalCost, depositCost FROM invoice_list WHERE jobsID = '".$_SESSION['primary_job']."'");
	$getVoice = $findVoice->fetch_array();
	$hasInvoice = $getVoice['invoiceID'];
	if (empty($hasInvoice)) {
	echo "You have not submitted an invoice yet";
	} else {
	$priceG = $getVoice['totalCost'];
	$priceD = $getVoice['depositCost'];
	$safeView = array('Springs', 'Hinges', 'Struts', 'Torsion Tube', 'Drums', 'Set of Rollers', 'Track', 'Cables', 'Pulley', 'Panels', 'End Bearing', 'Center Bearing', 'Center Bearing Plate', 'Top Fixture', 'Bottom Fixture', 'Door Hardware Kit', 'Slide Lock', 'T Lock', 'Vault Release', 'Windows', 'Rail', 'Trolley', 'Drive Gears', 'Force Adjustment', 'Limit Switch', 'Capacitor', 'Safety Eyes', 'Circuit Board', 'External Receiver', 'Wireless Keypad', 'Remote', 'Wall Button');
	$queryDetail = "SELECT garageModel, partOrder, usedList, repairOption, partsUsed FROM parts_order WHERE jobsID = '".$_SESSION['primary_job']."'";
	$findDetails = $mysqli->query($queryDetail);
	$getDetail = $findDetails->fetch_array();
	$detailArray = $getDetail['repairOption'];
	$udetails = $getDetail['partsUsed'];
	$iParts = $getDetail['partOrder'];
	$upParts = $getDetail['usedList'];
	$gModel = $getDetail['garageModel'];
	$billFormS = '';
	$billT = '';
	if (!empty($iParts)) { 
	if (!empty($upParts)) {	$popParts = "SELECT partType FROM parts_catalog WHERE partID in (".$iParts.",".$upParts.")"; }
	else { $popParts = "SELECT partType FROM parts_catalog WHERE partID in (".$iParts.")"; }
	$getAllPC = $mysqli->prepare($popParts);
	$getAllPC->execute();
	$getAllPC->bind_result($ThesePC);
	while($getAllPC->fetch()) {
	$billFormS.= $gModel." - ".$ThesePC."<br>"; 
			}
		$getAllPC->close();
		}
	for ($x = 0; $x <= 32; $x++) {
	if ($detailArray[$x] != 0) {
	$billFormS.= $gModel." - ".$safeView[$x]."<br>";}
	if ($udetails[$x] != 0) {
	$billFormS.= $gModel." - ".$safeView[$x]."<br>";}
	}
	$billT.= '<div style="float:left; margin-top:2vh; clear:left;"><b>Parts Order</b><br>'.$billFormS.'<div>';
	$billT.= '<div style="float:left; margin-top:3vh; clear:left;"><b>Total Cost:</b> $'.$priceG.'</div>';
	$billT.= '<div style="float:left; margin-top:3vh; clear:left;"><b>Deposit Cost:</b> $'.$priceD.'</div>';
	echo $billT;
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