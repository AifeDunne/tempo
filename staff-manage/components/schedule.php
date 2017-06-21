<?php
		date_default_timezone_set('America/Denver');
		$gettoday = getdate();
		$nowYear = $gettoday['year'];
		$nowDay = $gettoday['mday'];
		$nowMonth = $gettoday['mon'];
		$nowWDay = $gettoday['wday'];
		$textMonth = $gettoday['month'];
		$weekdayArr = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
		$currentDate = $nowYear."-".$nowMonth."-".$nowDay;
		$determineWeekday = $weekdayArr[$nowWDay];
		$determineRestOfWeek1 = 7 - $nowWDay;
		$determineRestOfWeek2 = 7 - $determineRestOfWeek1;
		$startWeek = array();
		for ($d = $nowWDay; $d < 7; $d++) { $startWeek[] = $weekdayArr[$d];	}
		if ($nowWDay !== '0' && $nowWDay !== 0) {
		for ($e = 0; $e < $determineRestOfWeek2; $e++) { $startWeek[] = $weekdayArr[$e]; }
		}
		$getDaysInMonth = date("t");
		$calculateDays = $nowDay + 6;
		$tagDate = array();
		$dayCount = 0;
		if ($calculateDays > $getDaysInMonth) {
		$nextMonthDate = 0;
		$thisYear = '';
		$changeYear = 0;
		$prevYear = $nowYear;
		if ($nowMonth == '12' || $nowMonth == 12) {
		$nextMonthDigit = 1;
		$thisYear = $nowYear++;
		} else {
		$nextMonthDigit = $nowMonth+1;
		$thisYear = $nowYear;
		$changeYear = 1;
		}
		$thisMonthDate = $nowDay;
		$byHowMany = $calculateDays - $getDaysInMonth;
		$thisMonthDays = 7 - $byHowMany;
		$nextMonthDays = 7 - $thisMonthDays;
		$calcYear = '';
		$calcMonth = date("F", mktime(0, 0, 0, $nextMonthDigit, 1, $thisYear));
		$scheduleArray = array();
		for ($f = 0; $f < $thisMonthDays; $f++) {
		$grepDate = '';
		if ($nextMonthDigit === 1) { $calcYear = $prevYear; }
		else { $calcYear = $thisYear; }
		$tagDate[] = $textMonth." ".$thisMonthDate.", ".$calcYear;
		$grepDate = $calcYear."-".$nowMonth."-".$thisMonthDate;
		$tempDataHold = GetSchedule($grepDate);
		if (!empty($tempDataHold)) {
		$scheduleArray[$dayCount] = $tempDataHold;
		}
		$tempDataHold = '';
		$thisMonthDate++;
		$dayCount++;
			}
		for ($g = 0; $g < $nextMonthDays; $g++) {
		$grepDate = '';
		$nextMonthDate++;
		$tagDate[] = $calcMonth." ".$nextMonthDate.", ".$thisYear;
		$grepDate = $thisYear."-".$nextMonthDigit."-".$nextMonthDate;
		$tempDataHold = GetSchedule($grepDate);
		if (!empty($tempDataHold)) {
		$scheduleArray[$dayCount] = $tempDataHold;
		}
		$tempDataHold = '';
		$dayCount++;
			}
		} else {
		$grepDate = '';
		$calcDay = $nowDay;
		$changeNum = $nowDay;
		for ($h = 1; $h <= 7; $h++) {
		$tagDate[] = $textMonth." ".$calcDay.", ".$nowYear;
		$grepDate = $nowYear."-".$nowMonth."-".$calcDay;
		$tempDataHold = GetSchedule($grepDate);
		if (!empty($tempDataHold)) { $scheduleArray[$dayCount] = $tempDataHold; }
		$tempDataHold = '';
		$calcDay++;
		$dayCount++;
			}
		}
		echo "<div id='scheduleHolder'>";
		$removeArr = array(" ", ",");
		for ($c = 0; $c <= 6; $c++) {
		$fixDate = $tagDate[$c];
		$fixDate = str_replace($removeArr, "", $fixDate);
		$startString = "<div id='".$fixDate."' class='scheduleElement'><div style='font-size:1.5vw; letter-spacing: -2px; margin-bottom:0.5vh;'><b>".$tagDate[$c]."</b> - ".$startWeek[$c]."</div>";
		if (!empty($scheduleArray[$c])) {
		foreach ($scheduleArray[$c] as $scheduleEntry) {
		$startString.= "<div class='scheduleDetail'><b>".$scheduleEntry['employeeName']."</b> - ".$scheduleEntry['shiftStart']." to ".$scheduleEntry['shiftEnd']."</div>";
				}
			} else { $startString.= '<div class="scheduleDetail">None</div>'; }
		$startString.= "</div>";
		echo $startString;
		}
		echo "</div>";
		?>