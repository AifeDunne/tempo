<?php
include_once '../includes/db_connect.php';

$memberCount = $mysqli->query("SELECT varValue FROM sysvar WHERE varName = 'memberCount'");
		$countMembers = $memberCount->fetch_array();
		print_r($countMembers);
		$addMember = $countMembers['varValue'];
		echo $addMember;
?>