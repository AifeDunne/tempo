<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'init.php';
if(isset($_GET['linkTrgt'])) {
	$VarDate = $_GET['linkTrgt'];
	list($TitleYear, $TitleMonth, $TitleDay) = explode('-', $VarDate);
	$FrmtMonth = date('F', $TitleMonth);
	$TextDate = $FrmtMonth.' '.$TitleDay.', '.$TitleYear;
	
$schedule = '';
$scheduleQuery = $db->prepare("SELECT title, start, end, task
	FROM schedule 
	WHERE evntdate = '".$VarDate."'");
$scheduleQuery->execute();
$setconfirm = $scheduleQuery->rowCount();
	$schedule .= '<div id="ScheduleHeader">'.$TextDate.'</div>';
	$TaskCnt = 0;
	if ($setconfirm > 0) {
		while ($content = $scheduleQuery->fetch(PDO::FETCH_BOTH)) {
			$TaskCnt++;
			$schedule .= '<hr><div class="TitleTtl">'.$TaskCnt.'. '.$content[0].'</div>';
			$schedule .= '<div class="listContent">'.$content[3].'</div>';
			$schedule .= '<div class="TimeTttl">Time: '.$content[1].' to '.$content[2].'</div>';

		}
	}
echo $schedule;
}
?>