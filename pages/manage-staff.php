<?php
include_once '../includes/system_function.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tempo - Staff Manager</title>
		<link href='http://fonts.googleapis.com/css?family=Quicksand|Marvel' rel='stylesheet' type='text/css'>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<link rel="stylesheet" href="css/staffmanage.css" />
		<script type="text/JavaScript" src="../js/sha512.js"></script> 
	</head>
    <body style="background:url(../background/BG1.jpg); background-size: cover  !important; background-repeat: no-repeat  !important; background-attachment: fixed  !important; background-position: center !important;">
	<div style="position:absolute; top:0; left:0; width:100%; height:100%;">
    <?php if (login_check($mysqli) == true) : ?>
	<?php 
	$retrieveAll = RetriveGMen();
	$employees = $retrieveAll['allemployees'];
	$supervisors = $retrieveAll['supervisor'];
	$inGroup = $retrieveAll['inGroup'];
	$outGroup = $retrieveAll['outGroup'];
	$userArray = array();
	foreach ($employees as $role) {
	$thisRole = $role['userRole'];
	$userID = $role['id'];
	if ($thisRole !== 'None' || $thisRole !== 'Super') {
	$userArray[$thisRole][$userID] = $role['name'];
		}
	}
	?>
	<div id="navBar">
	<div id="triggerMenu" class="navM" style="position:absolute; top:0; left:0; height:7vh; width:7vw; z-index:20; cursor:pointer;"></div>
	<div id="PageNavigation" class="navM">Menu</div>
	<div class="quickLinks" id="s1">Current Staff</div>
	<div class="quickLinks" id="s2">Schedule</div>
	<div class="quickLinks" id="s3">Groups</div>
	<div class="quickLinks" id="s4">Roles</div>
	<div id="navMenu" class="navM"><div style="height:2vh; float:left; clear:both;"></div><a class="navLinks navM" href="../protected_page2.php">Home</a><a class="navLinks navM" href="#">Workflow</a><a class="navLinks navM" href="#">MyDesk</a><a class="navLinks navM" href="#">Finance</a></div>
	<script>
	var crntTab, scheduleDetail;
	crntTab = "uTab1";
	scheduleDetail = 0;
	
	function TabStart() {
		var iTab = $(this).attr("id");
		if (iTab != crntTab) {
		$(".userBox").fadeOut(200);
		iTab = iTab.substring(4);
		$("#"+crntTab).removeClass("activeTab").addClass("inactiveTab");
		$("#uTab"+iTab).removeClass("inactiveTab").addClass("activeTab");
		crntTab = "uTab"+iTab;
		setTimeout(function() {
		$("#box"+iTab).fadeIn(200);
		}, 200);
		} else { return false; }
		}
	function fixTab() {
	var findTab = $(".activeTab").attr("id");
	$("#"+findTab).removeClass("activeTab").addClass("inactiveTab");
	}
	
	$("#triggerMenu").mouseenter(function() {
	$("#navMenu").stop().animate({"height":"24vh"},350);
	$("div").not(".navM").mouseover(function(e) {
	e.stopImmediatePropagation();
	if (!$(e.target).is(".navM")) { $("#navMenu").stop().animate({"height":"0"}, 350); 
			}
		});
	});
	
	function executeSub(IDPage) {
	$(".addBoxTabs").unbind();
	if (IDPage == 1) { subpage1(); }
	else if (IDPage == 2) { subpage2(); }
	else if (IDPage == 3) { subpage3(); }
	else if (IDPage == 4) { subpage4(); }
	$(".addBoxTabs").on("click", TabStart);
	}
	
	var noDouble;
	noDouble = 's1';
	$(".quickLinks").on("click", function() { 
	var nextPage = $(this).attr("id");
	if (noDouble != nextPage) {
	noDouble = nextPage;
	nextPage = nextPage.substring(1);
	var newPage = "#page"+nextPage;
	$(".subpage").fadeOut(300);
	setTimeout(function() { $(newPage).fadeIn(300); executeSub(nextPage); },300);
	} else { return false; }
	});
	</script>
	</div>
	<div id="page1" class="subpage">
	<div id="employeeList">
	<div style="position:absolute; top:0; left:0; width: 100%; height:4%; margin-top:1%; font-size:1.5vw; font-family:Quicksand;"><span style="float:left; margin-left:3%; margin-right:3%; cursor:pointer;">List</span><span style="float:left; cursor:pointer;">Grid</span></div>
	<div id="HolderBox"></div>
	</div>
	<div class="rightBox">
	<div id="tabBox" style="position:absolute; top:0; right:0; width:95%; height:30px;"><div id="uTab1" class="addBoxTabs activeTab">Overview</div><div id="uTab2" class="addBoxTabs inactiveTab">Add Staff</div><div id="uTab3" class="addBoxTabs inactiveTab">Edit</div></div>
	<script>
	function subpage1() {
	$(".userBox").hide();
	fixTab();
	crntTab = "uTab1";
	$("#uTab1").removeClass("inactiveTab").addClass("activeTab");
	$("#box1").show();
		<?php
		$employeeList = '';
		$empCount = 0;
		global $employees;
		if (!empty($employees)) {
		foreach ($employees as $employee) {
		$levelCheck = $employee['userlevel'];
		if ($levelCheck === 1) {
		$empCount++;
		$employeeList.= "'".'<div class="numberBox"><div class="numberListing"><span class="numberList">'.$empCount.'. </span></div><div id="'.$employee['id'].'" class="employeeEntry">'.$employee['name'].'</div></div>'."', "; }
			}
		$employeeList = substr($employeeList, 0, -2);
		$employeeList = '['.$employeeList.']';
		$employeeList = "var employees = ".$employeeList.";
		var empCount = ".$empCount.";";
		} else { $employeeList = "'".'<div style="float:left; width:100%; height:5vh; padding-top:2vh; border-bottom:1px solid grey; clear:both;"><span id="NoEmployee" style="float:left;">No Employees</span></div>'."'"; }
		echo $employeeList;
		?>
		
		jQuery.each(employees, function( i, val ) {
		$("#HolderBox").append(val);
		});
			
		function regformhash(form, username, realname, password, conf) {
		if (username.value == '' || realname.value == '' || password.value == '' || conf.value == '') { alert('You must provide all the requested details. Please try again'); return false; }
			re = /^\w+$/; 
			if(!re.test(form.username.value)) { alert("Username must contain only letters, numbers and underscores. Please try again"); form.username.focus(); return false; }
			if (password.value.length < 6) { alert('Passwords must be at least 6 characters long.  Please try again'); form.password.focus(); return false; }
			var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
			if (!re.test(password.value)) { alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again'); return false; }
			if (password.value != conf.value) { alert('Your password and confirmation do not match. Please try again'); form.password.focus(); return false; }
			var p = document.createElement("input");
			form.appendChild(p);
			p.name = "p";
			p.type = "hidden";
			p.value = hex_sha512(password.value);
			password.value = "";
			conf.value = "";
			$.ajax({
					type: "POST",
					url: "../includes/register.inc.php", 
					data: { username: username.value, realname: realname.value, p: p.value},
					success: function (data) {
					console.log(data);
					empCount++;
					var newEmployee = "<div style='float:left; width:100%; height:5vh; padding-top:2vh; border-bottom:1px solid grey; clear:both;'><span id='"+empCount+"' style='float:left;'>"+empCount+". "+realname.value+"</span></div>";
					employees.push(newEmployee);
					$("#HolderBox").append(newEmployee);
					$('#username').val('');
					$('#realname').val('');
					$('#password').val('');
					$('#confirmpwd').val('');
					}
			});
		}
		
		$("#addEmployee").on("click", function() {
		var regform = document.getElementById("registration_form");
		regformhash(regform, regform.username, regform.realname, regform.password, regform.confirmpwd);
		});
		}
	</script>
	<div id="box1" class="userBox" style="right: 9px;">
	<div style="float:left; clear:both; margin-bottom:1vh; width:100%; font-family:Quicksand; font-size:2vw; font-weight:bold; text-align:center;">Staff Overview</div>
	</div>
		<div id="box2" class="userBox" style="display:none;">
	 <?php if (!empty($error_msg)) { echo $error_msg; } ?>
        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post" id="registration_form" name="registration_form">
			<span style="float:left; font-family:Quicksand; font-size:1.2vw; font-weight:bold; clear:both;">Employee Avatar</span>
			<div style="float:left; clear:both; margin-bottom:1vh; border:1px solid black; width:10vw;"><img src="images/defaultAvatar.jpg" style="width:100%; height:100%;" /></div>
			<div style="float:left; clear:both; margin-bottom:1vh;"><input name="imageInput" id="imageInput" type="file" /></div>
            <div style="float:left; clear:both; margin-bottom:1vh; width:100%;"><span style="font-family:Quicksand; font-size:1.2vw; font-weight:bold;">Account Name:</span> <input type='text' name='username' id='username' style="height: 2.8vh; width: 56%;"/></div>
			<div style="float:left; clear:both; margin-bottom:1vh; width:100%;"><span style="font-family:Quicksand; font-size:1.2vw; font-weight:bold;">Employee Name:</span> <input type='text' name='realname' id='realname' style="height: 2.8vh; width: 52%;"/></div>
            <div style="float:left; clear:both; margin-bottom:1vh; width:100%;"><span style="font-family:Quicksand; font-size:1.2vw; font-weight:bold;">Password:</span> <input type="password"name="password" id="password" style="height: 2.8vh;"/></div>
            <div style="float:left; clear:both; width:100%;"><span style="font-family:Quicksand; font-size:1.2vw; font-weight:bold;">Confirm password:</span> <input type="password" name="confirmpwd" id="confirmpwd" style="height: 2.8vh;" /></div>
			<div id="addinstruct" style="float: left; width: 23vw; margin-top: 3vh; margin-left: 0; clear: both; margin-top: 1vh;"><span style="font-size:0.8vw;"><b>Note: </b>Usernames may contain only digits, upper and lower case letters and underscores. Passwords must be at least 6 characters long and contain one uppercase letter, one lower case letter, one number.</span></div>
        </form>
	    <button id="addEmployee" name="addEmployee" style="float:left; width:10vw; height:6vh; margin-top:1vh;">Add User</button>
		</div>
		<div id="box3" class="userBox" style="display:none;">
		<div style="float:left; clear:both; margin-bottom:1vh; width:100%; font-family:Quicksand; font-size:2vw; font-weight:bold; text-align:center;">Edit Staff</div>
		<div style="float:none; clear:both; width:90%; margin-left:auto; margin-right:auto; height:80%; border:1px solid black;">
		<?php
		global $employeeList;
		echo $employeeList;
		?>
						</div>
					</div>
				</div>
			</div>
			
	<div id="page2" class="subpage" style="display:none;">
		<div id="EventCalendar"></div>
		<script>
		function subpage2() {		
		$.ajax({
        	type: "GET",
        	url: "components/EvntSchedule/EventSchedule.php", 
			contentType: "text/html; charset=utf-8",
        	dataType: "html",
        	success: function (data) {
          	$("#EventCalendar").html(data);
			}
		});
				
		$(".userBox").hide();
		fixTab();
		crntTab = "uTab4";
		$("#uTab4").removeClass("inactiveTab").addClass("activeTab");
		$("#box4").show();
		
		function UpdateList() {
		var AddDate = $('#AddDate').val();
		var AddTitle = $('#AddTitle').val();
		var TimeStart = $('#TimeStart').val();
		var TimeEnd = $('#TimeEnd').val();
		var AddText = $('#AddText').val();
        $.ajax({
            type: "POST",
            url: "components/EvntSchedule/add.php?AddDate="+AddDate+"&AddTitle="+AddTitle+"&TimeStart="+TimeStart+"&TimeEnd="+TimeEnd+"&AddText="+AddText,
            success: function(){alert('success');}
			});
		}
				};
		</script>
		<div id="SubmitBox"></div>
		<div class="rightBox">
			<div id="tabBox" style="position:absolute; top:0; right:0; width:95%; height:30px;"><div id="uTab4" class="addBoxTabs activeTab">New</div><div id="uTab5" class="addBoxTabs inactiveTab">View</div><div id="uTab6" class="addBoxTabs inactiveTab">Edit</div></div>
		<div id="box4" class="userBox">
		<form id="add-schedule" style="font-size:14px !important;">
			<div style="font-family: PT Sans Narrow !important; color:#000 !important; text-transform:none !important;">
			<div style="clear:both; margin-top:5px;"><span style="font-weight:bold; font-size:20px; margin-right:10px;">Employee:</span><input type="text" style="width:66%;" id="AddTitle" name="AddTitle" placeholder="Full Name" autocomplete="off" required></div>
			<div style="clear:both; margin-bottom:5px;"><span style="font-weight:bold; font-size:20px; margin-right:10px;">Date:</span><input type="date" name="AddDate" id="AddDate" required/></div>
			<div style="float:left; margin-right:10px; font-weight:bold; font-size:20px;">Start:</div><input type="time" style="float:left;" name="TimeStart" id="TimeStart" /><div style="float:left; margin-left:15px; margin-right:10px; font-weight:bold; font-size:20px;">End:</div><input type="time" style="float:left;" name="TimeEnd" id="TimeEnd" />
			<input type="submit" style="background:#FFF; padding:5px 10px; border:1px solid #DDD; width:100%; margin-top:5px; box-shadow:3px 3px 0 #DDD;" onclick="UpdateList()">
							</div>
						</form>
					</div>
					<div id="box5" class="userBox"><div id="taskList"></div></div>
				</div>
			</div>
			
		<div id="page3" class="subpage" style="display:none;">
		<script>
		function subpage3() {
		<?php
		$countGroups = RetrieveVar('groupCount');
		$unpackGroups = explode("-",$countGroups);
		$groupCount = $unpackGroups[1];
		$acceptLabel = RetrieveVar('groupLabels');
		echo "var groupCount = ".$groupCount.";";
		?>
		$(".userBox").hide();
		fixTab();
		crntTab = "uTab7";
		$("#uTab7").removeClass("inactiveTab").addClass("activeTab");
		$("#box7").show();
		
		var warnOn = 0;
		var processingData = 0;
		function requiredField() {
		var getVertPos = $("#addGroup").position();
		getVertPos = getVertPos.top;
		console.log(getVertPos);
		getVertPos = getVertPos - 30;
		$("#box7").append("<div id='warning' style='position:absolute; top:"+getVertPos+"px; left:0; width:100%; height:20px; font-size:1vw; color:red; text-align:center; display:none; font-family: Quicksand;'>You must complete all required information!</div>");
		$("#warning").fadeIn(200).delay(800).fadeOut(200);
		setTimeout(function() { warnOn = 0; $("#warning").remove(); }, 1200);
		}
		
		$("#addGroupButton").on("click", function() {
		if (processingData == 0) {
		var gTitle = $("#groupTitle").val();
			var gLabel = $("#currentLabels").val();
			var gAltLabel = $("#groupLabel").val();
			var newLabel = ''; var labelAdd = 0;
		if (gAltLabel !== '') { newLabel = gAltLabel; labelAdd = 1; } else { newLabel = gLabel; }
			var addLeader = $("#newLeaders").val();
			var expLeader = $("#currentLeaders").val();
			var newLeader = ''; var leaderAdd = 0;
		if (expLeader !== '') { newLeader = expLeader; } else { newLeader = addLeader; leaderAdd = 1; }
		var gDesc = $("#groupD").val();
		var checkReqAdd = [gTitle, newLabel, newLeader, gDesc];
		for (g=0; g<=4; g++) {	var thisValue = checkReqAdd[g];	if (thisValue == '') { warnOn = 1; } }
		if (warnOn == 1) { requiredField(); return false; }
		else { processingData = 1;
				$.ajax({
					type: "POST",
					url: "../includes/updateRequest.php", 
					data: { dataRequest: "AddGroup", groupID: groupCount, groupTitle: gTitle, groupLabel: newLabel, labelAR: labelAdd, groupSuper: newLeader, superAR: leaderAdd, labelDesc: gDesc, acceptLabel: '<?php global $acceptLabel; echo $acceptLabel; ?>' },
					success: function (data) {
					$("#compGroups").html(data);
					console.log(data);
					processingData = 0;
					}
				});
				}
				}
			});
		}
		</script>
		<div id="compGroups">
		<?php
		global $groupCount;
		if ($groupCount !== '0') {
		echo "YES"; }
		else { echo "NO"; }
		?>
		</div>
		<div class="rightBox">
			<div id="tabBox" style="position:absolute; top:0; right:0; width:95%; height:30px;"><div id="uTab7" class="addBoxTabs activeTab">Groups</div><div id="uTab8" class="addBoxTabs inactiveTab">Assign</div><div id="uTab9" class="addBoxTabs inactiveTab">Edit</div></div>
		<div id="box7" class="userBox">
		<div id="addGroup">
		<span id="addTitle">Create New Group</span>
		<div style="float:left; width:100%; height:1.7vh; clear:both;"></div>
		<div class="groupAdd"><span class="addSpan">Group Title:</span><input id="groupTitle" type="text" style="float:right; width:60%; height:3vh;" placeholder="Sales Team"/></div>
		<div id="groupLabel" class="groupAdd"><span class="addSpan" style="line-height:3.3vh">Group Label:</span><select id="currentLabels" style="float:left; margin-left:1.4vw; width:40%; height:3vh;">
		<?php
		$buildMenu = '<option id="None" name="None" value="None">None</option>';
		global $acceptLabel;
		$unpackLabel = explode("-",$acceptLabel);
		$dataKey = $unpackLabel[0];
		$dataSerial = $unpackLabel[1];
		if ($dataKey > 0) {
		$dataSerial = explode(",", $dataSerial);
		for ($k = 0; $k <= $dataKey; $k++) { $buildMenu.= "<option id='".$dataSerial[$k]."' name='".$dataSerial[$k]."' value='".$dataSerial[$k]."'>".$dataSerial[$k]."</option>"; }
		} else { $buildMenu.= "<option id='".$dataSerial."' name='".$dataSerial."' value='".$dataSerial."'>".$dataSerial."</option>"; }
		echo $buildMenu;
		?>
		</select><br>
		<span class="addSpan" style="font-size:0.9vw; font-weight:bold; margin-left:3vw; clear:left;" >Add New:</span><input id="groupLabel" type="text" style="float:right; width:50%; margin-right: 2.2vw; height:3vh;" placeholder="Office"/></div>
		<div id="groupLeader" class="groupAdd"><span class="addSpan" style="line-height:3.3vh">Assign Leader:</span><select id="newLeaders" style="float:left; margin-left:0.5vw; width:40%; height:3vh;">
		<?php
		$addLeader = '<option id="noSet1" name="noSet1" value="">None</option>';
		global $outGroup;
		foreach ($outGroup as $open) {
		$addLeader.= "<option id='employee".$open['id']."' name='employee".$open['id']."' value='".$open['id']."'>".$open['name']."</option>"; }
		echo $addLeader;
		?>
		</select><br>
		<span class="addSpan" style="font-size:0.9vw; font-weight:bold; margin-left:2.6vw; clear:left;">Add Leader:</span><select id="currentLeaders" style="float:left; margin-left:0.45vw; width:40%; height:3vh;">
		<script>
		<?php
		$appendLeader = '"'."<option id='noSet2' name='noSet2' value=''>None</option>".'"';
		global $supervisors;
		if (!empty($supervisors)) {
		foreach ($supervisors as $supervisor) {
		$appendLeader.= ",".'"'."<option id='employee".$supervisor['id']."' name='employee".$supervisor['id']."' value='".$supervisor['id']."'>".$supervisor['name']."</option>".'"'; }
		$appendLeader = '['.$appendLeader.']';
		}
		$appendLeader = "var supervisors = ".$appendLeader.";";
		echo $appendLeader;
		?>
		jQuery.each(supervisors, function( i, val ) {
		$("#currentLeaders").append(val);
		});
		</script>
		</select></div>
		<div id="groupDesc" class="groupAdd" style="margin-top: -0.7vh;"><textarea id="groupD" style="float:left; width:90%; margin-left: 0.8vw; height:9vh;" cols="30" rows="10" placeholder="Brief description of this group."/></textarea></div>
		<button id="addGroupButton" style="float:left; width:80%; height:5.5vh; margin-left:2vw; clear:both; background:#f6f6f6; box-shadow: 3px 3px 0 #DDD;">Add Group</button>
		</div>
		</div>
		<div id="box8" class="userBox"></div>
		<div id="box9" class="userBox"></div>
		</div>
		</div>
		
		<div id="page4" class="subpage" style="display:none;">
		<script src='components/spectrum/spectrum.js'></script>
		<link rel='stylesheet' href='components/spectrum/spectrum.css' />
		<script>
		function subpage4() {
		$(".userBox").hide();
		fixTab();
		crntTab = "uTab10";
		$("#uTab10").removeClass("inactiveTab").addClass("activeTab");
		$("#box10").show();
		$("#colorPicker").spectrum({color: "#f00"});
		
		<?php
		$countRoles = RetrieveVar('roleCount');
		$unpackRoles = explode("-",$countRoles);
		$roleCount = $unpackRoles[1];
		echo "var roleCount = ".$roleCount.";";
		?>
		
		function requiredField2() {
		var getVertPos2 = $("#createRole").position();
		getVertPos2 = getVertPos2.top;
		getVertPos2 = getVertPos2 - 30;
		$("#box10").append("<div id='warning' style='position:absolute; top:"+getVertPos2+"px; left:0; width:100%; height:20px; font-size:1vw; color:red; text-align:center; display:none; font-family: Quicksand;'>You must complete all required information!</div>");
		$("#warning").fadeIn(200).delay(800).fadeOut(200);
		setTimeout(function() { warnOn2 = 0; $("#warning").remove(); }, 1200);
		}
		
		var warnOn2 = 0;
		var processingData2 = 0;
		$("#addRoleButton").on("click", function() {
		if (processingData2 == 0) {
		var roleTitle = $("#roleAdd").val();
			var roleType = $("#roleType").val();
			var roleColor = $("#colorPicker").spectrum("get");
			roleColor = roleColor.toHexString();
			var roleDescript = $("#roleD").val();
		var checkReqAdd2 = [roleTitle, roleType, roleColor, roleDescript];
		for (h=0; h<=4; h++) {	var thisValue2 = checkReqAdd2[h];	
		if (thisValue2 == '') { warnOn2 = 1; } }
		if (warnOn2 == 1) { requiredField2(); return false; }
		else { processingData2 = 1;
				$.ajax({
					type: "POST",
					url: "../includes/updateRequest.php", 
					data: { dataRequest: "AddRole", roleID: roleCount, roleName: roleTitle, roleCat: roleType, roleBG: roleColor, roleDesc: roleDescript},
					success: function (data) {
					$("#allRoles").html(data);
					console.log(data);
					processingData2 = 0;
					}
				});
				}
				}
			});
		}
		</script>
		<div id="allRoles"></div>
		<div class="rightBox">
			<div id="tabBox" style="position:absolute; top:0; right:0; width:95%; height:30px;"><div id="uTab10" class="addBoxTabs activeTab">Create</div><div id="uTab11" class="addBoxTabs inactiveTab">Edit</div><div id="uTab12" class="addBoxTabs inactiveTab">Settings</div></div>
		<div id="box10" class="userBox">
		<div id="createRole">
		<span id="roleTitle">Create New Role</span>
		<div style="float:left; width:100%; height:1.7vh; clear:both;"></div>
		<div class="roleForm"><span class="addSpan">Role Title:</span><input id="roleAdd" type="text" style="float:left; width:66%; height:3vh; margin-left:0.9vw;" placeholder="ex. Tour Guide, Personal Trainer, Repairman"/></div>
		<div class="roleForm"><span class="addSpan" style="line-height:3.3vh">Role Type:</span><select id="roleType" style="float:left; margin-left:0.5vw; width:40%; height:3vh;">
		<?php
		$addRole = '<option id="noSet3" name="noSet3" value="">None</option>';
		$checkArray = array('enableClerk','enableDelivery','enableReservation','enableSalesmen');
		$optionTitle = array('Clerk', 'Delivery', 'Reservation', 'Appointment');
		$returnPreference = CheckPreference($checkArray);
		$preferenceCount = 0;
		foreach ($returnPreference as $module) {
		if ($module !== 0 || $module !== '0') {
		$addRole.= "<option id='".$optionTitle[$preferenceCount]."' name='".$optionTitle[$preferenceCount]."' value='".$optionTitle[$preferenceCount]."'>".$optionTitle[$preferenceCount]."</option>"; 
		$preferenceCount++;}
		}
		echo $addRole;
		?>
		</select>
		<div class="roleForm" style="margin-top:1.5vh;"><span class="addSpan" style="margin-right:0.5vw;">Assign Color:</span><input type="text" id="colorPicker"/></div>
		<div class="roleForm" style="margin-top: -0.7vh;"><textarea id="roleD" style="float:left; width:90%; margin-left: 0.8vw; height:9vh;" cols="30" rows="10" placeholder="Brief description of this role."/></textarea></div>
		<button id="addRoleButton" style="float:left; width:80%; height:5.5vh; margin-left:2vw; clear:both; background:#f6f6f6; box-shadow: 3px 3px 0 #DDD;">Add Role</button>
		</div>
		</div>
		<div id="box11" class="userBox"></div>
		<div id="box12" class="userBox"></div>
		</div>
		</div>
		<script>
		$(document).ready(function() { executeSub(1); });
		</script>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>