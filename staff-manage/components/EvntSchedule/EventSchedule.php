<?php
include_once '../../../includes/db_connect.php';
		date_default_timezone_set('America/Denver');
		$StartMonth = date('m');
		$StartDay = date('d');
		$StartYear = date('Y');
		$CrntDay = 0;
		$TblRows = '';
		if (isset($_GET['month'])) {
			list($LinkMonth, $LinkYear) = explode('-', $_GET['month']);
			$LinkMonth = (int)$LinkMonth;
			$LinkYear = (int)$LinkYear;
		} else {
			$LinkMonth = date('n');
			$LinkYear = date('Y');
		}
		$LastYear = $LinkYear;
		$NextYear = $LinkYear;
		$PrevMonth = $LinkMonth-1;
		$NextMonth = $LinkMonth+1;
		if ($PrevMonth <= 0) {
			$PrevMonth = 12;
			$LastYear = $LastYear-1;
		} else if ($NextMonth > 12) {
			$NextMonth = 1;
			$NextYear = $NextYear+1;
		}
		$ThisDate = mktime(0, 0, 0, $LinkMonth, $StartDay, $LinkYear);
		list($MonthName, $DaysInMonth) = explode('-', date('F-t', $ThisDate));
		$FirstDay = (int)date('w', mktime(0, 0, 0, $LinkMonth, 1, $LinkYear));
		if ($MonthName == "August" || $DaysInMonth <= 30) {
		$PrevMonthDays = 31;
		} else { $PrevMonthDays = 30; }
		$PrevDays = $PrevMonthDays - $FirstDay;
		if ($FirstDay + $DaysInMonth > 35) { $rowAmnt = 6;
		} else {$rowAmnt = 5;}
			$evntDate = $LinkYear.'-'.$LinkMonth.'-'.$CrntDay;
			$countRow = 0;
			$tArray = array();
			$datevar = '';
			$evntStart = $LinkYear.'-'.$LinkMonth.'-1';
			$evntEnd = $LinkYear.'-'.$LinkMonth.'-'.$DaysInMonth;
			$taskQuery = "SELECT evntdate, title, start, end FROM schedule WHERE evntdate >= '".$evntStart."' AND evntdate <= '".$evntEnd."'";
			$evntQuery = $mysqli->prepare($taskQuery);
			$evntQuery->execute();
			$evntQuery->bind_result($aDate, $scheduleT, $startT, $endT);
			while ($evntQuery->fetch()) {
			$countRow++;
			$aDate = explode('-', $aDate);
			$aDate = $aDate[2];
			$datevar.= $aDate.",";
			$fixAKey = ltrim($aDate, '0');
			if (array_key_exists($fixAKey, $tArray)) { $tArray[$fixAKey].= ",".$scheduleT."|".$startT."|".$endT; }
			else { $tArray[$fixAKey] = $scheduleT."|".$startT."|".$endT; }
			}
			$evntQuery->close();
		$titlevar = ''; $startvar = ''; $endvar = ''; $checkZero = 0;
		for ($i = 0; $i < $rowAmnt; $i++) {
			$TblRows .= '<tr>';
		for ($k = 0; $k < 7; $k++) {
			if ($PrevDays < $PrevMonthDays) {
				$PrevDays++;
				$TblRows .= '<td class="Days"></td>';
			} else if ($CrntDay < $DaysInMonth) {
			$CrntDay++;
			$checkAdd = 0;
			if (array_key_exists($CrntDay, $tArray)) {
			$LnkDate = $LinkYear.'-'.$LinkMonth.'-'.$CrntDay;
			$getStrong = explode(",", $tArray[$CrntDay]);
			foreach ($getStrong as $thisString) {
			$checkAdd++;
			$checkZero++;
			$allContent = explode('|',$thisString);
			$titlevar.= "'".$allContent[0]."',";
			$startvar.= "'".$allContent[1]."',";
			$endvar.= "'".$allContent[2]."',"; }
			if ($checkAdd > 1) { $vocabCheck = "Schedules"; } else { $vocabCheck = "Schedule"; }
			$TblRows .= '<td class="Days Caltd"><div class="CalNum">'.$CrntDay.'</div><div id="trigger'.$CrntDay.'" class="triggerLink" style="float:left;">+'.$checkAdd.' '.$vocabCheck.'</div></td>';
					} else { $TblRows .= '<td class="Caltd"><div class="CalNum">'.$CrntDay.'</div></td>'; }
			} else { $TblRows .= '<td></td>'; }
			}
		$TblRows .= '</tr>';
		}
		if ($checkZero > 0) {
		$titlevar = substr($titlevar, 0, -1);
		$startvar = substr($startvar, 0, -1);
		$endvar = substr($endvar, 0, -1);
		$datevar = substr($datevar, 0, -1);
		if ($checkZero > 1) { $titlevar = '['.$titlevar.']'; $startvar = '['.$startvar.']'; $endvar = '['.$endvar.']'; $datevar = '['.$datevar.']'; }
		$titlevar = "var titleArr = ".$titlevar.";";
		$startvar = "var startArr = ".$startvar.";";
		$endvar = "var endArr = ".$endvar.";";
		$datevar = "var dateArr = ".$datevar.";";
		}
		$completeVar = '<script>
		var totalNum = '.$checkZero.';
		'.$titlevar.'
		'.$startvar.'
		'.$endvar.'
		'.$datevar.'
		var crntSchedule;
		crntSchedule = "";
		function showSchedule() {
		var getDate = $(this).attr("id");
		getDate = getDate.substring(7);
		if (scheduleDetail == 0) {
		$(".userBox").fadeOut(200);
		console.log(scheduleDetail);
		$("#uTab4").removeClass("activeTab").addClass("inactiveTab");
		crntTab = "uTab5";
		$("#uTab5").removeClass("inactiveTab").addClass("activeTab");
		var buildSchedule = "";
		crntSchedule = getDate;
		buildSchedule = buildSchedule + "<div id='."'".'schedule-"+getDate+"'."'".' style='."'".'float:left;clear:both;height:3vh;width:90%;padding-left:1%;padding-right:1%;padding-top:1vh;padding-bottom:1vh;'."'".'>";
		var arrID, titleID, startID, endID;
		for (j = 0; j < totalNum; j++) {
		if (totalNum <= 1) { arrID = dateArr; titleID = titleArr; startID = startArr; endID = endArr; } 
		else { arrID = dateArr[j]; titleID = titleArr[j]; startID = startArr[j]; endID = endArr[j]; }
		if (arrID == getDate) {
		buildSchedule = buildSchedule + "<b>Employee:</b> "+titleID+"<b style='."'".'margin-left:1vw;'."'".'>Start:</b> "+startID+"<b style='."'".'margin-left:1vw;'."'".'>End:</b> "+endID+"<br><hr>"; }
			}
		console.log(arrID);
		scheduleDetail = 1;
		buildSchedule = buildSchedule + "</div>";
		$("#taskList").append(buildSchedule);
		setTimeout(function() { $("#box5").fadeIn(200); }, 200);
		} else {
		var myElem = document.getElementById("schedule-"+getDate);
		console.log(myElem);
		if (myElem !== null) {
		console.log(getDate);
		$("#schedule-"+crntSchedule).fadeOut(200);
		setTimeout(function() { $("#schedule-"+getDate).fadeIn(200); },200);
		crntSchedule = getDate;
		} else {
		var plusSchedule = "";
		$("#taskList").fadeOut(200);
		setTimeout(function() { $("#schedule-"+crntSchedule).hide(); },200);
		plusSchedule = plusSchedule + "<div id='."'".'schedule-"+getDate+"'."'".' style='."'".'float:left; clear:both; height:3vh; width:90%; padding-left:1%; padding-right:1%; padding-top:1vh; padding-bottom:1vh;'."'".'>";
		for (k = 0; k < totalNum; k++) {
		if (totalNum <= 1) { arrID = dateArr; titleID = titleArr; startID = startArr; endID = endArr; } 
		else { arrID = dateArr[k]; titleID = titleArr[k]; startID = startArr[k]; endID = endArr[k]; }
		if (arrID == getDate) {
		plusSchedule = plusSchedule + "<b>Employee:</b> "+titleID+"<b style='."'".'margin-left:1vw;'."'".'>Start:</b> "+startID+"<b style='."'".'margin-left:1vw;'."'".'>End:</b> "+endID+"<br><hr>"; }
		}
		plusSchedule = plusSchedule + "</div>";
		setTimeout(function() { $("#taskList").append(plusSchedule).fadeIn(200); }, 200);
		crntSchedule = getDate;
			}
		}
        	}
			$(".triggerLink").on("click", showSchedule);
		</script>';
		$aKeys = array(
			'__prev_month__' => "{$PrevMonth}-{$LastYear}",
			'__next_month__' => "{$NextMonth}-{$NextYear}",
			'__cal_caption__' => $MonthName . ', ' . $LinkYear,
			'__cal_rows__' => $TblRows,
		);
		$sCalendarItself = strtr(file_get_contents('eventsch.html'), $aKeys);
		echo $sCalendarItself.$completeVar;
?>