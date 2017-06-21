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
			<div id="MenuContents" style="display:none; opacity:0;">
			<div id="Link1" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="start_safety.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Start Safety Check</a></div>
			<div id="Link2" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="part_add.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Add Parts</a></div>
			<div id="Link3" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="create_voice.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Create Invoice</a></div>
			<div id="Link4" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="paymentProcess.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Process Payment</a></div>
			<div id="Link5" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="vw_safety.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">View Safety Check</a></div>
			<div id="Link6" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="vw_invoice.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">View Invoice</a></div>
			<div id="Link7" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="/protected_page.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Home</a></div>
			<div id="Link8" style="float:left; width:100%; height:11%; background:#000; color:#FFF;"><a href="/includes/logout.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Log Out</a></div></div>
			</div>
		</div>
	<div style="float:left; width:70%; margin-left:9vw;">
    <?php
	$itemArray = array('Springs', 'Hinges', 'Struts', 'Torsion Tube', 'Drums', 'Set of Rollers', 'Track', 'Cables', 'Pulley', 'Panels', 'End Bearing', 'Center Bearing', 'Center Bearing Plate', 'Top Fixture', 'Bottom Fixture', 'Door Hardware Kit', 'Slide Lock', 'T Lock', 'Vault Release', 'Windows', 'Rail', 'Trolley', 'Drive Gears', 'Force Adjustment', 'Limit Switch', 'Capacitor', 'Safety Eyes', 'Circuit Board', 'External Receiver', 'Wireless Keypad', 'Remote', 'Wall Button');
	$queryCParts = "SELECT orderID, garageModel, partOrder, usedList, repairOption, partsUsed FROM parts_order WHERE jobsID = '".$_SESSION['primary_job']."'";
	$findCurrentP = $mysqli->query($queryCParts);
	$getCParts = $findCurrentP->fetch_array();
	$HasParts = $getCParts['orderID'];
	if (empty($HasParts)) {
	echo "You have not created a part order yet.";
	} else {
	$OParts = $getCParts['partOrder'];
	$PParts = $getCParts['usedList'];
	$cPartsArray = $getCParts['repairOption'];
	$pPartsArray = $getCParts['partsUsed'];
	$garageCModel = $getCParts['garageModel'];
	$partsList = '<form name="removeparts" method="POST">';
	if (!empty($OParts)) {
	if (!empty($PParts)) {	$popThis = "SELECT partType FROM parts_catalog WHERE partID in (".$OParts.",".$PParts.")"; }
	else { $popThis = "SELECT partType FROM parts_catalog WHERE partID in (".$OParts.")"; }
	$typeQuery = $mysqli->prepare($popThis);
	$typeQuery->execute();
	$typeQuery->bind_result($oArray);
	while($typeQuery->fetch()) {
	$partsList.= "<div style='float:left; height:3vh; width:25vw; padding:1vh; margin-top:1vh; border:solid 1px black; clear:left;'>".$garageCModel." - ".$oArray."<button style='height:3vh; float:right;' type='submit' name='deletepart'/>Delete Part(Coming Soon)</button></div>"; }
		$typeQuery->close();
	}
	for ($p = 0; $p <= 32; $p++) {
	if ($cPartsArray[$p] != 0) {
	$partsList.= "<div style='float:left; height:3vh; width:25vw; padding:1vh; margin-top:1vh; border:solid 1px black; clear:left;'>".$garageCModel." - ".$itemArray[$p]."<button style='height:3vh; float:right;' type='submit' name='deletepart'/>Delete Part(Coming Soon)</button></div>"; }
	if ($pPartsArray[$p] != 0) {
	$partsList.= "<div style='float:left; height:3vh; width:25vw; padding:1vh; margin-top:1vh; border:solid 1px black; clear:left;'>".$garageCModel." - ".$itemArray[$p]."<button style='height:3vh; float:right;' type='submit' name='deletepart'/>Delete Part(Coming Soon)</button></div>"; }
	}
	echo "<div style='float:left; margin-top:4vh;'><b>Parts Currently Set As Ordered</b><br><br>";
	echo $partsList."</form></div>";
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