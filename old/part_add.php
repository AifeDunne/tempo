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
		#menutext {float: left; margin-top: 58.4vh; margin-left: -14vw; height: 12vh; letter-spacing: 1.5vw; font-size: 6vh; text-transform: uppercase; width: 30vw; color: #FFF; transform: rotate(90deg); -webkit-transform: rotate(90deg); -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -ms-transform: rotate(90deg); cursor:pointer;}
		.rollBox {position:absolute; border:2px solid black; background:none; cursor:pointer;}
		.rollBox:hover {background:rgba(0,0,0,0.6);}
		.containDiv { float:left; width:100%; clear:both; display:none;color:#FFF;padding-top:5vh; }
		.labelSpan { float:left; font-size:1.5vw; margin-right:0.5vw; }
		.buttonContain { float:right; margin-right:20px; font-size:1.5vw; border:1px solid white; padding:5px; clear:right; }
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
			<div id="Link1" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="/protected_page.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Home</a></div>
			<div id="Link2" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="part_add.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Add Parts</a></div>
			<div id="Link3" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="create_voice.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Create Invoice</a></div>
			<div id="Link4" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="paymentProcess.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Process Payment</a></div>
			<div id="Link5" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="vw_safety.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">View Safety Check</a></div>
			<div id="Link6" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="vw_invoice.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">View Invoice</a></div>
			<div id="Link7" style="float:left; width:100%; height:11%; margin-bottom:2%; background:#000; color:#FFF;"><a href="vw_partorder.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">View Part Order</a></div>
			<div id="Link8" style="float:left; width:100%; height:11%; background:#000; color:#FFF;"><a href="/includes/logout.php" style="width: auto;font-size: 2vw;margin-left: 2.8vw;line-height: 5vw; color:#FFF;">Log Out</a></div></div>
		</div>
		<?php
		$checkForm = $_SESSION['primary_job'];
		$grabForm = "SELECT safetyID FROM check_list WHERE jobsID = '".$checkForm."'";
		$parseForm = $mysqli->query($grabForm);
		$parseSafeID = $parseForm->fetch_array();
		$thisSafeID = $parseSafeID['safetyID'];
		if (!empty($thisSafeID)) {
		echo "<div style='float:left;margin-left:7vw;margin-top:3vh;'>You have already submitted this form, to view or edit your selection click the View Safety Form link.</div>";
		} else {
		$nameArray = array('Springs', 'Hinges', 'Struts', 'Torsion Tube', 'Drums', 'Set of Rollers', 'Track', 'Cables', 'Pulley', 'Panels', 'End Bearing', 'Center Bearing', 'Center Bearing Plate', 'Top Fixture', 'Bottom Fixture', 'Door Hardware Kit', 'Slide Lock', 'T Lock', 'Vault Release', 'Windows', '', '', 'Trolley', 'Drive Gears', 'Force Adjustment', 'Limit Switch', 'Capacitor', 'Safety Eyes', 'Circuit Board', 'External Receiver', 'Wireless Keypad', 'Remote', 'Wall Button');
		$compileForm = '<div id="garageSystem" style="float:left; width:98vw; height:auto;"><div id="garageB" style="float:left; width:20%; margin-left:6vw;"><div id="garageBar" class="barNotes getCanvas" style="width:7vw; height:73vh; position:relative; left:5vw; top:7vh; background:url(images/EGC2.png); background-repeat: no-repeat;"></div></div><div id="garageD" style="float:left; width:49%;"><div id="garageDoor" class="garageNotes getCanvas" style="width:47vw; height:99vh; float:left; margin-top: -4vh; background:url(images/EGC1X.png); background-repeat: no-repeat;"></div></div><div id="garageE" style="float:left; width:18%; margin-left:6vw;"><div id="garageEngine" class="engineNotes getCanvas" style="width:17vw; height:36vh; float:left; background:url(images/EGC3.png); background-repeat: no-repeat;"></div></div>
		<button name="addParts" id="addParts" style="position: absolute; right: 4vw; bottom: 7vh; width: 16vw; height: 4vw;">Next Page</button>
		</div>';
		echo $compileForm;
		}
		?>
		<div id="bar" class="cv1" style="width:0; height:0; position:absolute; top:10%; left:21%; overflow:hidden;"><canvas id="barNotes" class="cv1" width="1000%" height="650%" style="border:2px solid #000000; background:#FFF;"></canvas></div>
		<div id="garage" class="cv1" style="width:0; height:0; position:absolute; top:10%; left:21%; overflow:hidden;"><canvas id="garageNotes" class="cv1" width="1000%" height="650%" style="border:2px solid #000000; background:#FFF;"></canvas></div>
		<div id="engine" class="cv1" style="width:0; height:0; position:absolute; top:10%; left:21%; overflow:hidden;"><canvas id="engineNotes" class="cv1" width="1000%" height="650%" style="border:2px solid #000000; background:#FFF;"></canvas></div>
		<script>
		var canvasOn, firstClick, nextClick;
		canvasOn = 0;
		firstClick = 0;
		nextClick = 0;
		
		$(document).ready(function() {
		var canvas, context, tool;
		function init (thisDiv) {
		canvas = document.getElementById(thisDiv);
		if (!canvas) { alert('Error: I cannot find the canvas element!'); return; }
		if (!canvas.getContext) { alert('Error: no canvas.getContext!'); return; }
    context = canvas.getContext('2d');
    if (!context) { alert('Error: failed to getContext!'); return; }
    tool = new tool_pencil();
    canvas.addEventListener('mousedown', ev_canvas, false);
    canvas.addEventListener('mousemove', ev_canvas, false);
    canvas.addEventListener('mouseup',   ev_canvas, false);
	}

	  function tool_pencil () {
	  var tool = this; 
	  this.started = false;
	this.mousedown = function (ev) { context.beginPath(); context.moveTo(ev._x, ev._y); tool.started = true; };
    this.mousemove = function (ev) { if (tool.started) { context.lineTo(ev._x, ev._y); context.stroke(); } };
    this.mouseup = function (ev) { if (tool.started) { tool.mousemove(ev); tool.started = false; } };
	}

  function ev_canvas (ev) {
  if (ev.layerX || ev.layerX == 0) { ev._x = ev.layerX; ev._y = ev.layerY; } 
  else if (ev.offsetX || ev.offsetX == 0) { ev._x = ev.offsetX; ev._y = ev.offsetY; }
    var func = tool[ev.type];
    if (func) { func(ev); }
  }
  
  function removeListen() {
  canvas.removeEventListener('mousedown', ev_canvas, false);
  canvas.removeEventListener('mousemove', ev_canvas, false);
  canvas.removeEventListener('mouseup',   ev_canvas, false);
  }
  
  var barOn, garageOn, engineOn;
  barOn = 0;
  garageOn = 0;
  engineOn = 0;
  
  function AllCanvas() {
  $(".getCanvas").on("click", function() {
  if (firstClick == 0) {
  var canvasID = $(this).attr("class").split(" ");
  canvasID = canvasID[0];
  var holdDivID = canvasID.slice(0,-5);
  init(canvasID);
  $("#"+holdDivID).css({"width":"auto","height":"auto"});
  if (holdDivID == "bar") { barOn = 1; }
  if (holdDivID == "garage") { garageOn = 1; }
  if (holdDivID == "engine") { engineOn = 1; }
  canvasOn = 1;
  setTimeout(function() {
  $("#"+canvasID).mouseout(function() {
  $("div").not(".cv1").mousedown(function() {
  removeListen();
  $("#"+holdDivID).css({"width":"0","height":"0"});
  canvasOn = 0;
			});
		});
	}, 75);
	} else {
	return false }
	});
  }
  AllCanvas();
	
	function ReClick() {
	$("#prevScreen").unbind().remove();
	$("#submitOrder").remove();
		$("#garageSystem").append("<button id='addParts' name='addParts' style='position: absolute; right: 4vw; bottom: 7vh; width: 16vw; height: 4vw;'>Next Page</button>");
		$(".boxPop").unbind().remove();
		$(".rollBox").unbind().remove();
		AllCanvas();
		firstClick = 0;
		$("#addParts").mousedown(NextPage);
	}
	
	function GetPic() {
	var divArray = ['barNotes','garageNotes','engineNotes'];
	var picArray = [];
	var fArray = [barOn, garageOn, engineOn];
	for (p = 0; p < 3; p++) {
	if (fArray[p] != 0) {
	var thisCanvas = document.getElementById(divArray[p]);
	var thisPic = thisCanvas.toDataURL();
	picArray.push(thisPic);	} 
    else { picArray.push('0'); }
	}
	$.ajax({
  type: "POST",
  url: "page_func/savePic.php",
  data: { pictures: picArray, jobID: <?php echo $_SESSION['primary_job']; ?> },
  success:function(result) { console.log(result); }
	  });
	}
	
	var boxCount;
	function NextPage() {
	if (canvasOn == 1) { $(".cv1").css({"width":"0","height":"0"});	}
	$("#addParts").unbind().remove();
	$("#garageSystem").append("<button id='submitOrder' name='submitOrder' style='position: absolute; right: 4vw; bottom: 17vh; width: 16vw; height: 4vw;'>Submit Notes & Parts</button><button id='prevScreen' name='prevScreen' style='position: absolute; right: 4vw; bottom: 7vh; width: 16vw; height: 4vw;'>Previous Page</button>");
	firstClick = 1;
	boxCount = 0;
	var wHeight = $(window).height();
	$("#submitOrder").mousedown(GetPic);
	$("#garageBar").unbind("click");
	$("#garageDoor").unbind("click");
	$("#garageEngine").unbind("click");
	var barLoc = $("#garageB").position();
		var barLocLeft, barLocTop;
		barLocLeft = parseInt(barLoc.left);
		barLocTop = parseInt(barLoc.top);
		var doorLoc = $("#garageD").position();
		var doorLocLeft, doorLocTop;
		doorLocLeft = parseInt(doorLoc.left);
		doorLocTop = parseInt(doorLoc.top);
		var engineLoc = $("#garageEngine").position();
		var engineLocLeft, engineLocTop;
		engineLocLeft = parseInt(engineLoc.left);
		engineLocTop = parseInt(engineLoc.top);
		var leftArray, topArray, barW, barH, formName, optionList;
		formName = ['End Bearing Plates','Torsion Spring','Drums'];
		leftArray = [-2,1,1];
		topArray = [-1,8,32];
		barW = [5,5,5];
		barH = [5,20,5];
		var leftGarage, topGarage, garageW, garageH, formName2, optionList2;
		formName2 = ['Jamb Bracket','Rollers','Hinges','Flag Brackets','Horizontal Tracks','Center Bearing','Bottom Brackets', 'Vertical Tracks', 'Struts', 'Safety Eyes'];
		leftGarage = [44,2,26.5,44,13.7,12.7,1,0,34,41];
		topGarage = [26,20,24,10,3,10,40,14.5,12,43];
		garageW = [4,3.5,2,3,8,3,4,6,6,4];
		garageH = [4,4,5,6,4,4,4,4,4,4];
		for (a = 0; a < 3; a++) { boxCount++;
		optionList = ['<div class="containDiv boxPop"><span class="labelSpan boxPop">L. End Bearing Plate: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="LBPlateU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="LBPlateO"/></div><br><br><span class="labelSpan boxPop">R. End Bearing Plate: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][0]" name="f['+boxCount+'][2][0]" style="float:left;" value="RBPlateU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][1]" name="f['+boxCount+'][2][1]" style="float:left; margin-left:2vw;" value="RBPlateO"/></div></div>'
		,'<div class="boxPop" style="float:left; width:100%; clear:both; display:none;color:#FFF;padding-top:5vh;"><span class="labelSpan boxPop">Left Red: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="LeftRedU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][0]" style="float:left; margin-left:2vw;" value="LeftRedO"/></div><br><br><div class="buttonContain boxPop"><label for="b1">207inch</label><input class="boxPop" type="radio" id="b1" name="f['+boxCount+'][1][2]" style="float:left;" value="207"/><label for="b2">218inch</label><input class="boxPop" type="radio" id="b2" name="f['+boxCount+'][1][2]" style="float:left;" value="218"/><label for="b3">225inch</label><input class="boxPop" type="radio" id="b3" name="f['+boxCount+'][1][2]" style="float:left;" value="225"/><label for="b4">234inch</label><input class="boxPop" type="radio" id="b4" name="f['+boxCount+'][1][2]" style="float:left;" value="234"/><label for="b5">243inch</label><input class="boxPop" type="radio" id="b5" name="f['+boxCount+'][1][2]" style="float:left;" value="243"/><label for="b6">250inch</label><input class="boxPop" type="radio" id="b6" name="f['+boxCount+'][1][2]" style="float:left;" value="250"/></div><br><br><br><br><span class="labelSpan boxPop">Right Black: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][0]" name="f['+boxCount+'][3][0]" style="float:left;" value="RightBlackU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][1]" name="f['+boxCount+'][3][1]" style="float:left; margin-left:2vw;" value="RightBlackO"/></div><br><br><div class="buttonContain boxPop"><label for="b7">207inch</label><input class="boxPop" type="radio" id="b7" name="f['+boxCount+'][3][2]" style="float:left;" value="207"/><label for="b8">218inch</label><input class="boxPop" type="radio" id="b8" name="f['+boxCount+'][3][2]" style="float:left;" value="218"/><label for="b9">225inch</label><input class="boxPop" type="radio" id="b9" name="f['+boxCount+'][3][2]" style="float:left;" value="225"/><label for="b10">234inch</label><input class="boxPop" type="radio" id="b10" name="f['+boxCount+'][3][2]" style="float:left;" value="234"/><label for="b11">243inch</label><input class="boxPop" type="radio" id="b11" name="f['+boxCount+'][3][2]" style="float:left;" value="243"/><label for="b12">250inch</label><input class="boxPop" type="radio" id="b12" name="f['+boxCount+'][3][2]" style="float:left;" value="250"/></div></div>'
		,'<div class="containDiv boxPop"><span class="labelSpan boxPop">Left Drum: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="LDrumU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="LDrumO"/></div><br><br><span class="labelSpan boxPop">Right Drum: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][0]" name="f['+boxCount+'][2][0]" style="float:left;" value="RDrumU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][1]" name="f['+boxCount+'][2][1]" style="float:left; margin-left:2vw;" value="RDrumO"/></div></div>'];
		$("#garageBar").append("<div id='r"+boxCount+"' class='rollBox' style='left:"+barLocLeft+"px; top:"+barLocTop+"px; margin-left:"+leftArray[a]+"vw; margin-top:"+topArray[a]+"vw; width:"+barW[a]+"vw; height:"+barH[a]+"vw;'></div>");
		var menuPosP = $("#r"+boxCount).position();
		var menuPosW = $("#r"+boxCount).outerWidth(true);
		var menuPosH = $("#r"+boxCount).outerHeight(true);
		var menuAPW = $("#r"+boxCount).width();
		var menuAPH = $("#r"+boxCount).height();
		menuPosW = menuPosW - menuAPW;
		menuPosH = menuPosH - menuAPH;
		var menuPosL = menuPosW + menuPosP.left;
		var menuPosT = menuPosH + menuPosP.top;
		var checkHeight = menuPosT + 400;
		if (checkHeight >= wHeight) {
		var newHeight = checkHeight - wHeight;
		menuPosT = menuPosT - newHeight;
		}
		$("#garageBar").append("<div id='menu"+boxCount+"' class='boxPop' style='position:absolute; left:"+menuPosL+"px; top:"+menuPosT+"px; width:0; height:325px; background:black; z-index:999;'><div style='float:left; width:100%; text-align:center; font-size:2vw; clear:both; color:#FFF; display:none;'>"+formName[a]+"</div>"+optionList[a]+"</div>");
		}
		for (g = 0; g < 10; g++) { boxCount++;
		optionList2 = ['<div class="containDiv boxPop"><span class="labelSpan boxPop">Jamb Bracket: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="JBracketU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="JBracketO"/></div></div>'
		,'<div class="containDiv boxPop"><span class="labelSpan boxPop">Short Stem: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="RSStemU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="RSStemO"/></div><br><br><span class="labelSpan boxPop">Set of 10: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][0]" name="f['+boxCount+'][2][0]" style="float:left;" value="SSStemU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][1]" name="f['+boxCount+'][2][1]" style="float:left; margin-left:2vw;" value="SSStemO"/></div><br><br><span class="labelSpan boxPop">Long Stem: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][0]" name="f['+boxCount+'][3][0]" style="float:left;" value="RLStemU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][1]" name="f['+boxCount+'][3][1]" style="float:left; margin-left:2vw;" value="RLStemO"/></div><br><br><span class="labelSpan boxPop">Set of 10: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][4][0]" name="f['+boxCount+'][4][0]" style="float:left;" value="SLStemU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][4][1]" name="f['+boxCount+'][4][1]" style="float:left; margin-left:2vw;" value="SLStemO"/></div></div>'
		,'<div class="boxPop" style="float:left; width:100%; clear:both; display:none;color:#FFF;padding-top:5vh;"><span class="labelSpan boxPop">Hinge #1: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="Hinge1U"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="Hinge1O"/></div><br><br><span class="labelSpan boxPop">Hinge #2: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][0]" name="f['+boxCount+'][2][0]" style="float:left;" value="Hinge2U"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][1]" name="f['+boxCount+'][2][1]" style="float:left; margin-left:2vw;" value="Hinge2O"/></div><br><br><span class="labelSpan boxPop">Hinge #3: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][0]" name="f['+boxCount+'][3][0]" style="float:left;" value="Hinge3U"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][1]" name="f['+boxCount+'][3][1]" style="float:left; margin-left:2vw;" value="Hinge3O"/></div><br><br><span class="labelSpan boxPop">Hinge #4: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][4][0]" name="f['+boxCount+'][4][0]" style="float:left;" value="Hinge4U"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][4][1]" name="f['+boxCount+'][4][1]" style="float:left; margin-left:2vw;" value="Hinge4O"/></div></div>'
		,'<div class="boxPop" style="float:left; width:100%; clear:both; display:none;color:#FFF;padding-top:5vh;"><span class="labelSpan boxPop">L. Flag Bracket: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="LFlagBU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="LFlagBO"/></div><br><br><span class="labelSpan boxPop">R. Flag Bracket: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][0]" name="f['+boxCount+'][2][0]" style="float:left;" value="RFlagBU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][1]" name="f['+boxCount+'][2][1]" style="float:left; margin-left:2vw;" value="RFlagBO"/></div><br><br><span class="labelSpan boxPop">Flag Bracket Set: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][0]" name="f['+boxCount+'][3][0]" style="float:left;" value="FlagBSU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][1]" name="f['+boxCount+'][3][1]" style="float:left; margin-left:2vw;" value="FlagBSO"/></div></div>'
		,'<div class="containDiv boxPop"><span class="labelSpan boxPop">L. Horizontal Tracks: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="lHTracksU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="lHTracksO"/></div><br><br><span class="labelSpan boxPop">R. Horizontal Tracks: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][0]" name="f['+boxCount+'][2][0]" style="float:left;" value="rHTracksU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][1]" name="f['+boxCount+'][2][1]" style="float:left; margin-left:2vw;" value="rHTracksO"/></div><br><br><span class="labelSpan boxPop">Horizontal Tracks Set: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][0]" name="f['+boxCount+'][3][0]" style="float:left;" value="hTracksSU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][1]" name="f['+boxCount+'][3][1]" style="float:left; margin-left:2vw;" value="hTracksSO"/></div></div>'
		,'<div class="containDiv boxPop"><span class="labelSpan boxPop">Center Bearing: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="cBearingU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="cBearingO"/></div><br><br><span class="labelSpan boxPop">C. Bearing Plate: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][0]" name="f['+boxCount+'][2][0]" style="float:left;" value="CBPlateU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][1]" name="f['+boxCount+'][2][1]" style="float:left; margin-left:2vw;" value="CBPlateO"/></div></div>'
		,'<div class="boxPop" style="float:left; width:100%; clear:both; display:none;color:#FFF;padding-top:5vh;"><span class="labelSpan boxPop">L. Bottom Bracket: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="LBottomBU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="LBottomBO"/></div><br><br><span class="labelSpan boxPop">R. Bottom Bracket: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][0]" name="f['+boxCount+'][2][0]" style="float:left;" value="RBottomBU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][1]" name="f['+boxCount+'][2][1]" style="float:left; margin-left:2vw;" value="RBottomBO"/></div><br><br><span class="labelSpan boxPop">Bottom Bracket Set: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][0]" name="f['+boxCount+'][3][0]" style="float:left;" value="bottomBSU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][3][1]" name="f['+boxCount+'][3][1]" style="float:left; margin-left:2vw;" value="bottomBSO"/></div></div>'
		,'<div class="containDiv boxPop"><span class="labelSpan boxPop">L. Vertical Tracks: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="lVtracksU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="lVtracksO"/></div><br><br><span class="labelSpan boxPop">R. Vertical Tracks: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][0]" name="f['+boxCount+'][2][0]" style="float:left;" value="rVtracksU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][1]" name="f['+boxCount+'][2][1]" style="float:left; margin-left:2vw;" value="rVtracksO"/></div></div>'
		,'<div class="containDiv boxPop"><span class="labelSpan boxPop">7ft: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="strut7U"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="strut7O"/></div><br><br><span class="labelSpan boxPop">8ft: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][0]" name="f['+boxCount+'][2][0]" style="float:left;" value="strut8U"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][2][1]" name="f['+boxCount+'][2][1]" style="float:left; margin-left:2vw;" value="strut8O"/></div></div>'
		,'<div class="containDiv boxPop"><span class="labelSpan boxPop">Safety Eyes: </span><div class="buttonContain boxPop"><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][0]" name="f['+boxCount+'][1][0]" style="float:left;" value="sEyesU"/><input class="boxPop" type="checkbox" id="f['+boxCount+'][1][1]" name="f['+boxCount+'][1][1]" style="float:left; margin-left:2vw;" value="sEyesO"/></div></div>'];
		$("#garageDoor").append("<div id='r"+boxCount+"' class='rollBox' style='left:"+doorLocLeft+"px; top:"+doorLocTop+"px; margin-left:"+leftGarage[g]+"vw; margin-top:"+topGarage[g]+"vw; width:"+garageW[g]+"vw; height:"+garageH[g]+"vw;'></div>");
		var menuPosP = $("#r"+boxCount).position();
		var menuPosW = $("#r"+boxCount).outerWidth(true);
		var menuPosH = $("#r"+boxCount).outerHeight(true);
		var menuAPW = $("#r"+boxCount).width();
		var menuAPH = $("#r"+boxCount).height();
		menuPosW = menuPosW - menuAPW;
		menuPosH = menuPosH - menuAPH;
		var menuPosL = menuPosW + menuPosP.left;
		var menuPosT = menuPosH + menuPosP.top;
		var checkHeight = menuPosT + 400;
		if (checkHeight >= wHeight) {
		var newHeight = checkHeight - wHeight;
		menuPosT = menuPosT - newHeight;
		}
		$("#garageDoor").append("<div id='menu"+boxCount+"' class='boxPop' style='position:absolute; left:"+menuPosL+"px; top:"+menuPosT+"px; width:0; height:325px; background:black; z-index:999; overflow:hidden;'><div style='float:left; width:100%; text-align:center; font-size:2vw; clear:both; color:#FFF; display:none;'>"+formName2[g]+"</div>"+optionList2[g]+"</div>");
		}
		boxCount++;
		$("#garageEngine").append("<div id='r"+boxCount+"' class='rollBox' style='position:absolute; right:115px; top:140px; width:9vw; height:9vw;'></div>");
		$("#garageEngine").append("<div id='menu"+boxCount+"' class='boxPop' style='position:absolute; right:165px; top:142px; width:0; height:400px; background:black; z-index:999;'><div style='float:left; width:100%; text-align:center; font-size:2vw; clear:both; color:#FFF; display:none;'>Garage Opener</div></div>");
		
		$(document).on("click", ".rollBox", function() {
		var getPDIV = $(this).attr("id");
		var getMenu = getPDIV.substr(1);
		$("#menu"+getMenu).css({"width":"22vw"});
		$("#menu"+getMenu).children().show();
		setTimeout(function() {
		$("div").on("click", function(e) {
		if (!$(e.target).is('.boxPop')) {
		$("#menu"+getMenu).css({"width":"0"});
		$("#menu"+getMenu).children().hide();
		$(this).unbind(e); }
			});
			},100);
		});
		$("#prevScreen").mousedown(ReClick);
		}
	
	$("#addParts").mousedown(NextPage);
	});
		</script>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>