<?php 
		$prepareQ = "SELECT partsOrder, depositCost FROM partsList 
		INNER JOIN invoice_list ON invoice_list.jobsID = partsList.jobsID
		WHERE partsList.jobsID = ".$_SESSION['primary_job'];
		if ($grabOldOrder = $mysqli->query($prepareQ)) {
		$getOldParts = $grabOldOrder->fetch_array();
		$allOldP = $getOldParts['partsOrder'];
		$allOldP = explode(',',$allOldP);
		if (is_array($allOldP) === true) {
		$partString = '';
		foreach ($allOldP as $oldP) { $partString.='"'.$oldP.'",'; }
		$partString = substr($partString,0,-1);
		$allOldP = $partString;
		} else { $allOldP = '"'.$allOldP.'"'; }
		$remainPay = $getOldParts['depositCost'];
		$costRemain = $getOldParts['depositCost'];
		$decDep = $costRemain;
		$decDep = floatval($decDep);
		$decDep = $decDep * 2;
		$costRemain = '<br><hr><div style="float:left; margin-top:0.5vh; clear:left;"><b>Remaining Cost:</b> $'.$costRemain.'</div>';
		$billnParts = "<b>Parts Ordered and Installed:</b><br><hr>";
		$NpriceQuery = "SELECT partCost, realName FROM partsCatalog WHERE partName in (".$allOldP.")";
		$nPrice = $mysqli->prepare($NpriceQuery);
		$nPrice->execute();
		$nPrice->bind_result($currentPrice, $currentName);
		while ($nPrice->fetch()) { $billnParts.= $currentName." - $".$currentPrice."<br>"; }
		$nPrice->close();
		echo $billnParts.$costRemain."<br><form id='finishJob' name='finishJob' method='post' action='page_func/addinvoice.php?lastID=".$_SESSION['primary_job']."&empID=".$_SESSION['employeeID']."&remainPay=".$remainPay."&unfinish=1'><button form='finishJob' style='height:4vh; float:left; margin-top:2vh; clear:left;' name='submitvoice'/>Submit Invoice</button></div>";
		} else { printf ($mysqli->error); }
?>