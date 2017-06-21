<?php
include_once 'db_connect.php';
if (!empty($_POST['editentry'])) {
		$EditID = $_GET['Edit_ID'];
		$updateTime = $_POST['editTime'];
		$updateTime.= ":00";
		$updateAdd = $_POST['editLoc'];
		$updateDesc = $_POST['editType'];
		$updateID = $_POST['editCustomer'];
		if ($updateQuery = $mysqli->prepare("UPDATE active_jobs SET appointmentTime = '".$updateTime."', jobLocation = '".$updateAdd."', jobType = '".$updateDesc."', customerID = ".$updateID." WHERE jobsID = ".$EditID."") ) {
		$updateQuery->execute();
		$updateQuery->close();
		header('Location: /pages/jobAssign.php');
		} else { printf ($mysqli->error);
		}
		}
?>