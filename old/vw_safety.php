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
			<div id="Link5" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="/protected_page.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Home</a></div>
			<div id="Link6" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="vw_invoice.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">View Invoice</a></div>
			<div id="Link7" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="vw_partorder.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">View Part Order</a></div>
			<div id="Link8" style="float:left; width:100%; height:11%; background:#000; color:#FFF;"><a href="/includes/logout.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Log Out</a></div></div>
			</div>
		</div>
	<?php
		$queryForm = "SELECT safetyDetails, conditionValue, partsUsed, repairOption FROM check_list WHERE jobsID = '".$_SESSION['primary_job']."'";
		if ($findForm = $mysqli->query($queryForm)) {
		$getVar = $findForm->fetch_array();
		$sdetails = $getVar['safetyDetails'];
		$scondition = $getVar['conditionValue'];
		$udetails = $getVar['partsUsed'];
		$rdetails = $getVar['repairOption'];
		$showForm = '<div id="BoxLine" style="float:left;width:93%;margin-left:6vw;margin-top:3vh;padding-bottom:3vh;">
		<div style="float:left; width:20%; height: 1.3vw;">Hardware</div><div style="float:left; width:28%; height: 1.3vw;">Details</div><div style="float:left; width:28%; height: 1.3vw;"><div style="float:left; width:33%;">Satisfactory</div><div style="float:left; width:28%; margin-left:5%;">Concern</div><div style="float:left; width:33%;">Safety Hazard</div></div>';
		$nameArray = array('Springs', 'Hinges', 'Struts', 'Torsion Tube', 'Drums', 'Set of Rollers', 'Track', 'Cables', 'Pulley', 'Panels', 'End Bearing', 'Center Bearing', 'Center Bearing Plate', 'Top Fixture', 'Bottom Fixture', 'Door Hardware Kit', 'Slide Lock', 'T Lock', 'Vault Release', 'Windows', '', '', 'Trolley', 'Drive Gears', 'Force Adjustment', 'Limit Switch', 'Capacitor', 'Safety Eyes', 'Circuit Board', 'External Receiver', 'Wireless Keypad', 'Remote', 'Wall Button');
		for ($f = 0; $f <= 32; $f++) {
		if ($scondition[$f] === '1') {$fValue1 = 'checked'; $fValue2 = ''; $fValue3 = '';}
		else if ($scondition[$f] === '2') {$fValue1 = ''; $fValue2 = 'checked'; $fValue3 = '';}
		else if ($scondition[$f] === '3') {$fValue1 = ''; $fValue2 = ''; $fValue3 = 'checked';}
		else {$fValue1 = ''; $fValue2 = ''; $fValue3 = '';};
		if ($sdetails[$f] === '1') {$sValue1 = 'checked'; $sValue2 = ''; $sValue3 = ''; $sValue4 = ''; $sValue5 = '';}
		else if ($sdetails[$f] === '2') {$sValue1 = ''; $sValue2 = 'checked'; $sValue3 = ''; $sValue4 = ''; $sValue5 = '';}
		else if ($sdetails[$f] === '3') {$sValue1 = ''; $sValue2 = ''; $sValue3 = 'checked'; $sValue4 = ''; $sValue5 = '';}
		else if ($sdetails[$f] === '4') {$sValue1 = ''; $sValue2 = ''; $sValue3 = ''; $sValue4 = 'checked'; $sValue5 = '';}
		else if ($sdetails[$f] === '5') {$sValue1 = ''; $sValue2 = ''; $sValue3 = ''; $sValue4 = ''; $sValue5 = 'checked';}
		else {$sValue1 = ''; $sValue2 = ''; $sValue3 = ''; $sValue4 = ''; $sValue5 = '';};
		if ($udetails[$f] === '1') {$uValue = 'checked';}
		else {$uValue = '';};
		if ($rdetails[$f] === '1') {$rValue = 'checked';}
		else {$rValue = '';};
		$detailArray = array('<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>Tension<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>Extension','<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>1<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>2<input type="radio" name="detail['.$f.']" value="3" '.$sValue3.'>3<input type="radio" name="detail['.$f.']" value="4" '.$sValue4.'>4<input type="radio" name="detail['.$f.']" value="5" '.$sValue5.'>5','<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>8ft<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>16ft<input type="radio" name="detail['.$f.']" value="3" '.$sValue3.'>18ft', '<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>8ft<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>16ft<input type="radio" name="detail['.$f.']" value="3" '.$sValue3.'>18ft', '<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>7ft<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>10ft', '<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>Short<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>Long', '<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>Horizontal<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>Vertical', '<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>Real<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>7ft<input type="radio" name="detail['.$f.']" value="3" '.$sValue3.'>8ft<input type="radio" name="detail['.$f.']" value="4" '.$sValue4.'>Custom', '', '<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>Crack<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>Rip<input type="radio" name="detail['.$f.']" value="3" '.$sValue3.'>Dent<input type="radio" name="detail['.$f.']" value="4" '.$sValue4.'>Rust', '', '', '', '', '', '',  '', '', '', '<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>Broken<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>Cracked', '', '', '<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>Chain<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>Screw<input type="radio" name="detail['.$f.']" value="3" '.$sValue3.'>Belt', '', '', '', '', '<input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>Obstructed', '', '', '', '', '');
		if ($f === 20) {
		$showForm.= '<div style="height:3vw;border:1px black solid;width:99%;clear:left;margin-top:1vh;"><div style="float:left; width:30%; height:2vw; padding-top:1vw;">Door Obstructions</div>';
		$showForm.= '<div style="float:left;width:60%;height:3vw;margin-top:1vh;"><input type="radio" name="detail['.$f.']" value="1" '.$sValue1.'>Clear<input type="radio" name="detail['.$f.']" value="2" '.$sValue2.'>Debris<input type="radio" name="detail['.$f.']" value="3" '.$sValue3.'>Wall<input type="radio" name="detail['.$f.']" value="4" '.$sValue4.'>Personal Belongings<input type="radio" name="detail['.$f.']" value="5" '.$sValue5.'>Needs 10ft Clearance</div></div>';
		} else if ($f === 21) {
		$showForm.= '<div style="height:3vw;border:1px black solid; width:99%; clear:left; margin-top:1vh;"><div style="float:left;width:30%;height:2vw;clear:left; padding-top:1vw;">Rail</div>';
		$showForm.= '<div style="float:left;width:60%;height:2vw;padding-top:1vw;border-right:1px black solid;"><input type="radio" name="detail['.$f.']" value="1">7ft<input type="radio" name="detail['.$f.']" value="2">8ft<input type="radio" name="detail['.$f.']" value="3">9ft+<input type="radio" name="detail2['.$f.']" value="1">Chain<input type="radio" name="detail2['.$f.']" value="2">Screw<input type="radio" name="detail2['.$f.']" value="3">Belt</div>';
		$showForm.= '<div style="float:left;height:2vw;width:9%;padding-top:1vw;"><input type="checkbox" name="repair['.$f.']" value="1">Order</div></div>';
		} else {
		$showForm.= '<div id="Div'.$f.'" style="height:3vw;width:99%;border:1px black solid;clear:left;margin-top:1vh;"><div style="float:left;width:20%;height:2vw;border-right:1px black solid;clear:left;padding-top:1vw;">'.$nameArray[$f].'</div>';
		$showForm.= '<div style="float:left; width:27%; height: 2vw; border-right:1px black solid;padding-top:1vw;">'.$detailArray[$f].'</div>';
		$showForm.= '<div style="float:left; width:30%; height: 2vw; border-right:1px black solid;padding-top:1vw;"><div style="float:left; width:33%; margin-left:10%;"><input type="radio" name="quality['.$f.']" value="1" '.$fValue1.'></div><div style="float:left; width:33%;"><input type="radio" name="quality['.$f.']" value="2" '.$fValue2.'></div><div style="float:left; width:23%;"><input type="radio" name="quality['.$f.']" value="3" '.$fValue3.'></div></div>';
		$showForm.= '<div style="float:left;height:2vw;width:11%;padding-top:1vw;"><input type="radio" name="used['.$f.']" value="1" '.$uValue.'>Used</div>';
		$showForm.= '<div style="float:left;height:2vw;width:11%;padding-top:1vw;"><input type="radio" name="repair['.$f.']" value="1" '.$rValue.'>Order</div></div>';}
		}
		$showForm.= "</div>";
		echo $showForm;
		} else {
		echo "You have not submitted a safety check for this job yet.";
		}
		?>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>