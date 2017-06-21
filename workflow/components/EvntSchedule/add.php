<?php

if(isset($_GET['AddDate'])) {
if(isset($_GET['AddTitle'])) {
	$NewDate = $_GET['AddDate'];
	list($Yr, $Mnth, $Dy) = explode('-', $NewDate);
	$Mnth = ltrim($Mnth, '0');
	$Dy = ltrim($Dy, '0');
	$NewDate = $Yr.'-'.$Mnth.'-'.$Dy;
	
	$NewTitle = $_GET['AddTitle'];
	$SQLthis = "'$NewDate', '$NewTitle'";
	
	if(isset($_GET['TimeStart'])) {
	$NewStart = $_GET['TimeStart'];
	$SQLthis .= ", '$NewStart'";
	} else {
	$SQLthis .= ", ''";
	}
	if(isset($_GET['TimeEnd'])) {
	$NewEnd = $_GET['TimeEnd'];
	$SQLthis .= ", '$NewEnd'";
	} else {
	$SQLthis .= ", ''";
	}
	if(isset($_GET['AddText'])) {
	$NewText = $_GET['AddText'];
	$SQLthis .= ", '$NewText'";
	} else {
	$SQLthis .= ", ''";
	}
	
	$plusQuery = $mysqli->prepare("
		INSERT INTO schedule (evntdate, title, start, end, task)
		VALUES (".$SQLthis.")
	");
	$plusQuery->execute();
	print_r("$SQLthis");
	}
}