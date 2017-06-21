<?php
$groupUpdate = $_POST['userGroupArray'];
$groupElementC = $_POST['ggCountArr'];
	if ($groupElementC !== 1 && $groupElementC !== '1') {
	$groupUpdate = explode("+|+", $groupUpdate);
	print_r($groupUpdate);
	} else { echo $groupUpdate; }
?>