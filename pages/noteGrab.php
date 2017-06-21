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
		.formTitle {
		float:left;
		font-size:2vw;
		line-height:9vh;
		margin-top:2vh;
		clear:left;
		}
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
		<div id="Link7" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/progJobs.php" class="FPageLinks">On-Hold Jobs</a></div>
		<div id="Link8" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobComplete.php" class="FPageLinks">Completed Jobs</a></div>
		<div id="Link9" class="menuBar" style="height:11%; border-top:2px solid white; border-right:10px solid white;"><a href="/includes/logout.php" class="FPageLinks">Log Out</a></div>
	</div>
		<?php
		if (isset($_GET['cJob'])) {	$viewJob = $_GET['cJob']; 
		if (isset($_GET['noteID'])) { $viewNote = $_GET['noteID'];
		$addMenu = '<div style="float:left; width:98%; margin-left:2%; height:5vh; clear:both; border-bottom:1px solid black;">';
		$addBlocks = '';
		$addScript = '<script>
		$(document).on("click", ".noteLink", function() {
		$(".noteElement").hide();
		var getPDIV = $(this).attr("id");
		var getMenu = getPDIV.substr(4);
		$("#noteContent"+getMenu).show();
		});
		</script>';
		$eachCount = 0;
		$nQuery = "SELECT jobComment FROM complete_jobs WHERE jobsID = ".$viewJob;
		$getNotes = $mysqli->query($nQuery);
		$takeNotes = $getNotes->fetch_array();
		$takeNotes = $takeNotes['jobComment'];
		$fullAddr = "http://res.cloudinary.com/system-overflow/image/upload/v1429476228/job".$viewJob."/";
		$getPicID = explode(',',$viewNote);
		$styleArray = array(1 => 'style="float: left; margin-left: 19vw; width: auto; height: auto; background: url(http://workflow.systemoverflow.com/pages/images/EGC2.png) no-repeat; background-position-y: 7vh; background-position-x: 5vw; display:none;"',
		2 => 'style="float:left; width:47vw; height:93vh; margin-left:2vw; background: url(/pages/images/EGC1X.png); background-repeat: no-repeat; display:none; background-position-y: -7vh;"',
		3 => 'style="width:17vw; height:36vh; float:left; margin-left: 18vw; margin-top: 12vh; background:url(/pages/images/EGC3.png); background-repeat: no-repeat; display:none;"');
		foreach ($getPicID as $picID) {
		$eachCount++;
		if ($picID === '1') { $linkTitle = "Torsion Bar"; }
		if ($picID === '2') { $linkTitle = "Garage Door"; }
		if ($picID === '3') { $linkTitle = "Garage Motor"; }
		$addMenu.= "<span id='note".$eachCount."' class='noteLink' style='float:left; width:auto; margin-right:2vw; font-weight:bold; text-decoration:underline; color:blue; font-size:1.5vw; cursor:pointer;'>".$linkTitle."</span>";
		$noteNum = "notePic".$picID.".png";
		$fAddress = $fullAddr.$noteNum;
		$addBlocks.= '<div id="noteContent'.$eachCount.'" class="noteElement" '.$styleArray[$eachCount].'><img class="noteImg" src="'.$fAddress.'"></div>';
		}
		$addMenu.= "</div>";
		$buildNotes = "<div style='float:left; margin-left:1vw; height: 72vh; width: 20%; margin-top:-1vh; padding:1vw; background: #FFF; font-size:1.5vw;'><span style='font-size:2vw; font-weight:bold;'>Additional Notes</span><br><hr>".$takeNotes."</div>";
		echo '<div id="NoteBox" style="float: left; width: 54%; height: 98vh; margin-top:-1vh; border: 1px solid black; margin-left: 1.5vw; font-size: 1.5vw; background: #FFF;">'.$addMenu.$addBlocks.$addScript.'</div>'.$buildNotes;
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