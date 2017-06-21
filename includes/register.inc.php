<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
 
$error_msg = "";
if (isset($_POST['username'], $_POST['realname'], $_POST['p'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$realname = filter_input(INPUT_POST, 'realname', FILTER_SANITIZE_STRING); 
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) { $error_msg .= '<p class="error">Invalid password configuration.</p>'; }
 
    $prep_stmt = "SELECT id FROM members WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $error_msg .= '<p class="error">An employee with this username already exists.</p>';
                        $stmt->close();
        }
                $stmt->close();
    } else {
        $error_msg .= '<p class="error">Database error Line 39</p>';
                $stmt->close();
    }
  
    if (empty($error_msg)) {
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $password = hash('sha512', $password . $random_salt);
		if ($memberCount = $mysqli->query("SELECT varValue FROM sysvar WHERE varName = 'memberCount'")) {
		$countMembers = $memberCount->fetch_array();
		$addMember = $countMembers['varValue'];
		$addMember++;
        if ($insert_stmt = $mysqli->prepare("INSERT INTO members (id, username, employeeName, password, salt) VALUES (?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('issss', $addMember, $username, $realname, $password, $random_salt);
            if (! $insert_stmt->execute()) { echo "Failure to Inset"; } else {
			$insert_stmt->close(); 
			$updateMember = $mysqli->query("UPDATE sysvar SET varValue = ".$addMember." WHERE varName = 'memberCount' OR varName = 'employeeCount'"); 
			$employeeArr = array('id'=>$addMember,'name'=>$realname,'level'=>1,'roleName'=>'None','group'=>'None','work'=>0,'busy'=>0);
			if ($employeeInsert = $mysqli->prepare("INSERT INTO employees (employeeID, employeeName, userLevel, roleName, groupID, working, busy) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
            $employeeInsert->bind_param('isissii', $employeeArr['id'], $employeeArr['name'], $employeeArr['level'], $employeeArr['roleName'], $employeeArr['group'], $employeeArr['work'], $employeeArr['busy']);
			$employeeInsert->execute();
			$employeeInsert->close();
				} else { echo "ERROR SECOND SUBMIT:"; printf ($mysqli->error); }
			}
        } else { echo "ERROR FIRST SUBMIT:"; printf ($mysqli->error); }
		} else { echo "ERROR GET MEMBER:"; printf ($mysqli->error); }
    } else { echo $error_msg; }
}