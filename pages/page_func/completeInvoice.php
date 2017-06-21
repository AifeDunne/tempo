<?php
include_once 'db_connect.php';

	$unfinished = $_GET['unfinish'];
	if ($unfinished === 0) {
		$primaryID = $_GET['pID'];
		$empID = $_GET['eID'];
		$cID = $_GET['cID'];
		$doneID = $_GET['dID'];
		$addcost = $_GET['addcost'];
		if (empty($addcost)) { $addcost = "0.00"; }
		$comments = $_GET['comments'];
		$invoiceQ1 = "SELECT invoiceID FROM invoice_list WHERE jobsID = ".$primaryID;
		$grabF1 = $mysqli->query($invoiceQ1);
		$grabF2 = $grabF1->fetch_array();
		$hasInvoice = $grabF2['invoiceID'];
		if (empty($hasInvoice)) {
		if (!empty($_GET['tID'])) {
		$toteCost = $_GET['tID'];
		$depCost = $_GET['depID'];
		if (empty($depCost)) { $invoiceQuery = "INSERT INTO invoice_list VALUES (NULL, ".$primaryID.", '".$toteCost."', '0.00', ".$empID.", ".$cID.", ".$addcost.", ".$doneID.")"; } 
		else { $invoiceQuery = "INSERT INTO invoice_list VALUES (NULL, ".$primaryID.", '".$toteCost."', '".$depCost."', ".$empID.", ".$cID.", ".$addcost.", ".$doneID.")"; }
		$invoiceInsert = $mysqli->query($invoiceQuery);
		} else { $invoiceInsert = $mysqli->query("INSERT INTO invoice_list VALUES (NULL, ".$primaryID.", '0.00', '0.00', ".$empID.", ".$cID.", ".$addcost.", ".$doneID.")"); }
		}
		$finalQuery = "SELECT jobLocation, jobType, checkID, listID, invoiceID FROM accepted_jobs 
			LEFT JOIN invoice_list ON invoice_list.jobsID = accepted_jobs.jobsID
			LEFT JOIN checkList ON checkList.jobsID = accepted_jobs.jobsID
			LEFT JOIN partsList ON partsList.jobsID = accepted_jobs.jobsID
			WHERE accepted_jobs.jobsID = ".$primaryID;
			if ($grabFinal = $mysqli->query($finalQuery)) {
			$getFinal = $grabFinal->fetch_array();
			$fLoc = $getFinal['jobLocation'];
			$fType = $getFinal['jobType'];
			$fOrder = $getFinal['listID'];
			$fSafety = $getFinal['checkID'];
			$fInvoice = $getFinal['invoiceID']; 
			if (empty($fOrder)) {$fOrder = 0;}
			if (empty($fSafety)) {$fSafety = 0;}
			$finishJob = "INSERT INTO complete_jobs VALUES (NULL, ".$primaryID.", ".$cID.", '".$fLoc."', '".$fType."', ".$empID.", ".$fOrder.", ".$fSafety.", ".$fInvoice.", '".$comments."', ".$doneID.", 0)";
			$fInsert = $mysqli->query($finishJob);
			$removeAccept = $mysqli->query("DELETE FROM accepted_jobs WHERE jobsID = ".$primaryID." LIMIT 1");
			if ($unassignE = $mysqli->query("UPDATE members SET busy = 0 WHERE id = ".$empID)) {
			$emailReq = "SELECT customerName, customerEmail FROM customer_jobs WHERE customerID = ".$cID." LIMIT 1";
			$getClientMail = $mysqli->query($emailReq);
			$foundEmail = $getClientMail->fetch_array();
			$foundName = $foundEmail['customerName'];
			$foundEmail = $foundEmail['customerEmail'];
			if ($foundEmail === "No Email") { header('Location: /protected_page.php'); }
			else {
			$email_to = $foundEmail;
			$clientRequest = "Your Invoice";
			$headers = 'From: auto-mail@elegantgaragedoors.com\r\n Reply-To:auto-mail@elegantgaragedoors.com\r\n';
			$clientMsg = '<html>
			<body style="background:url(EmailBG.jpg); background-size: cover  !important; background-repeat: no-repeat  !important; background-attachment: fixed  !important; background-position: center !important;">
			<div style="background:rgba(255,255,255,0.6); height: 81px; width: 92%;">
			<div style="position:absolute; top:10px; right:150px; font-size:16px; font-weight:bold;">Elegant Garage Doors<br>3424 Royalwood Cir<br>Oklahoma City, OK 73115-1746<br>Phone: 405-702-9858</div>
			<div style="position:absolute; top:20px; left:20px; font-size:50px; font-weight:bold;">Invoice</div>
			<hr style="position:absolute; top:80px; background-color:black; color:black; border-color:black; left:20px; width:90%;"/></div>
			<table rules="all" style="position:absolute; left:20px; top:100px; border-color: #666; border:2px solid; background:rgba(255,255,255,0.6);" cellpadding="20">
			<tr><td><strong>Your Name:</strong> </td><td>'.$foundName.'</td></tr>
			<tr><td><strong>Your Address:</strong> </td><td>'.$fLoc.'</td></tr>
			<tr><td><strong>Deposit Paid:</strong> </td><td>$'.$depCost.'</td></tr>
			<tr><td><strong>Additional Cost:</strong> </td><td>$'.$addcost.'</td></tr>
			<tr><td><strong>Total Cost Of Service:</strong> </td><td>$'.$toteCost.'</td></tr>
			</table>
			</body>
			</html>';
			@mail($email_to, $clientRequest, $clientMsg, $headers);
			header('Location: /protected_page.php');}
			} else { echo "There's been an error"; }
			} else { echo "Please complete all fields"; }
		} else {
		$changeID = $_GET['changeID'];
		$uEmployee = $_GET['uEmployee'];
		$changeIVoice = "UPDATE invoice_list SET jobDone = 1 WHERE jobsID = ".$changeID;
		$changeCJob = "UPDATE complete_jobs SET reassigned = 0, finished = 1 WHERE jobsID = ".$changeID;
		$removeAC =  "DELETE FROM accepted_jobs WHERE jobsID = ".$changeID." LIMIT 1";
		$upMember = "UPDATE members SET busy = 0 WHERE id = ".$uEmployee;
		if ($changeJob = $mysqli->query($changeCJob)) {
		$changeInvoice = $mysqli->query($changeIVoice);
		$changeAccepts = $mysqli->query($removeAC);
		if ($changeMember = $mysqli->query($upMember)) {
		header('Location: /protected_page.php');
		}
		} else { printf ($mysqli->error); }
		}
?>