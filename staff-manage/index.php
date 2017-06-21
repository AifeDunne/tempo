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
		<script type="text/JavaScript" src="../js/jQuery.js"></script> 
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="css/staffmanage.css" />
		<script type="text/JavaScript" src="../js/sha512.js"></script>
		<script type="text/JavaScript" src="components/color.js"></script> 
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
	$retrieveG = GetUserGroups();
	$retrieveR = GetUserRoles();
	
	$userArray = array();
	foreach ($employees as $role) {
	$thisRole = $role['userRole'];
	$userID = $role['id'];
	if ($thisRole !== 'None' || $thisRole !== 'Super') {
	$userArray[$thisRole][$userID] = $role['name'];
		}
	}
	
	$nameArray = '';
	$idArray = '';
	foreach ($employees as $names) {
	if ($names['userlevel'] !== '2' || $names['userlevel'] !== 2) {	$nameArray.= '"'.$names['name'].'",'; $idArray.= '"'.$names['id'].'",';}
	}
	$nameArray = substr($nameArray, 0, -1);
	$idArray = substr($idArray, 0, -1);
	$nameArray = "[".$nameArray."]";
	$idArray = "[".$idArray."]";
	
	$empGroup = array();
	$empNameArr = '[';
	$empIDArr = '[';
	$employeeList = '';
		$empCount = 0;
		if (!empty($employees)) {
		foreach ($employees as $employee) {
		$levelCheck = $employee['userlevel'];
		if ($levelCheck === 1) {
		$empCount++;
		$employeeList.= "'".'<div class="numberBox"><div class="numberListing"><span class="numberList">'.$empCount.'. </span></div><div id="employee'.$employee['id'].'" class="employeeEntry"><div class="employeeAvatar"><img src="images/defaultAvatar.jpg" style="width:100%; height:100%;"/></div><span class="employeeName">'.$employee['name'].'<br><span class="empID"><b>Employee ID:</b> #<b>'.$employee['id'].'</b></span></span><div class="hasGroup"></div><div class="boxDivide"></div><div class="hasRole"><span id="group'.$empCount.'" class="isAnswer"><b>Group:</b> '.$employee['group'].'</span><span id="role'.$empCount.'" class="isAnswer"><b>Role:</b> '.$employee['userRole'].'</span></div><div class="editButton"></div></div></div>'."', "; }
		if ($employee['group'] !== 'None' && $employee['group'] !== 'Super' ) {
		$empGroup[] = array('group' => $employee['group'], 'name' => $employee['name']); }
		$empIDArr.= "'".$employee['id']."',";
		$empNameArr.= "'".$employee['name']."',";
			}
		$empNameArr = substr($empNameArr, 0, -1);
		$empIDArr = substr($empIDArr, 0, -1);
		$empNameArr.= ']';
		$empIDArr.= ']';
		$employeeList = substr($employeeList, 0, -2);
		$employeeList = '['.$employeeList.']';
		$employeeList = "var employees = ".$employeeList.";
		var empCount = ".$empCount.";";
		} else { $employeeList = "'".'<div style="float:left; width:100%; height:5vh; padding-top:2vh; border-bottom:1px solid grey; clear:both;"><span id="NoEmployee" style="float:left;">No Employees</span></div>'."'"; }
	
	$superCount = 0;
	$appendLeader = '"'."<option id='noSet2' name='noSet2' value=''>None</option>".'"';
	$supervisorArr = array();
		if (!empty($supervisors)) {
		foreach ($supervisors as $supervisor) {
		$superCount++;
		$supID = $supervisor['id'];
		$appendLeader.= ",".'"'."<option id='employee".$supID."' name='employee".$supID."' value='".$supID."'>".$supervisor['name']."</option>".'"'; 
		$supervisorArr[$supID] = $supervisor['name'];}
		$appendLeader = '['.$appendLeader.']';
		}
		$appendLeader = "var supervisors = ".$appendLeader.";";
		$superCount = "var superCount = ".$superCount.";";
		
		$countGroups = RetrieveVar('groupCount');
		$unpackGroups = explode("-",$countGroups);
		$groupCount = $unpackGroups[1];
		$buildMenu = '<option id="None" name="None" value="None" selected="selected">None</option>';
		$acceptLabel = RetrieveVar('groupLabels');
		$unpackLabel = explode("-",$acceptLabel);
		$dataKey = $unpackLabel[0];
		$dataSerial = $unpackLabel[1];
		$currentGLabel = '"'.$dataSerial.'"';
		if ($dataKey > 0) {
		$dataSerial = explode(",", $dataSerial);
		for ($k = 0; $k <= $dataKey; $k++) { $buildMenu.= "<option id='".$dataSerial[$k]."' name='".$dataSerial[$k]."' value='".$dataSerial[$k]."'>".$dataSerial[$k]."</option>"; }
		} else { $buildMenu.= "<option id='".$dataSerial."' name='".$dataSerial."' value='".$dataSerial."'>".$dataSerial."</option>"; }
		$groupFind = "var groupCount = ".$groupCount.";";
				
		function determineEven($oddeven) {
		$detEven = false;
		if (is_numeric ($oddeven)) {
        if ( $oddeven % 2 == 0) $detEven = true;
		}
		return $detEven;
		}
		
		$groupList = '';
		$groupCName = '';
		$gFormatCount = 0;
		foreach ($retrieveG as $thisGroup) {
		$gFormatCount++;
		$groupFormat = '';
		if ($gFormatCount === 1) { $groupFormat = 'groupLeft'; }
		else if ($gFormatCount === 2) { $groupFormat = 'groupRight'; }
		else { $oddOrEven = '';
		$oddOrEven = determineEven($gFormatCount);
		if ($oddOrEven === false) { $groupFormat = 'breakLeft'; }
		else if ($oddOrEven === true) { $groupFormat = 'breakRight'; }
		}
		$groupList.= "<div class='".$groupFormat." allGroup' id='group".$thisGroup['groupID']."'><span class='gNameGroup'>".$thisGroup['groupName']."</span><span style='float:left; clear:both; width:100%; font-size:1vw;'>Group ID: ".$thisGroup['groupID']."</span>";
		$currentSuper = $thisGroup['groupSuper'];
		$superName = $supervisorArr[$currentSuper];
		if ($thisGroup['groupLabel'] !== 'None') {
		$groupList.="<span style='float:left; clear:both; width:100%; font-size:1.3vw;'>Label: ".$thisGroup['groupLabel']."</span><div style='width:100%; height:1vh; float:left;'></div><hr><div style='width:100%; height:0.5vh; float:left;'></div><span style='float:left; clear:both; width:100%; font-size:1.3vw;'>".$thisGroup['groupDesc']."</span><div style='width:100%; height:1.5vh; float:left;'></div><hr>"; } 
		else { $groupList.="<span style='float:left; clear:both; width:100%; font-size:1.3vw;'>".$thisGroup['groupDesc']."</span><div style='height:2vh; width:100%; clear:both;'></div>"; }
		$groupList.="<span class='superVTitle'>Supervisor: ".$superName."</span><div class='dropTarget'>+ Add User</div><div class='addGroupList'></div><div class='groupAddBtn'>Update</div></div>";
		}
		
		$countRoles = RetrieveVar('roleCount');
		$unpackRoles = explode("-",$countRoles);
		$roleCount = $unpackRoles[1];
		$roleVar = "var roleCount = ".$roleCount.";";
		
		$roleList = '';
		$roleUCount = 0;
		foreach ($retrieveR as $thisRole) {
		$roleUCount++;
		$roleList.= "<div class='currentRole' id='roleBox".$roleUCount."'></div>";
		}
	?>
	<div id="navBar">
	<div id="triggerMenu" class="navM" style="position:absolute; top:0; left:0; height:7vh; width:7vw; z-index:20; cursor:pointer;"></div>
	<div id="PageNavigation" class="navM">Menu</div>
	<div class="quickLinks" id="s1">Current Staff</div>
	<div class="quickLinks" id="s2">Schedule</div>
	<div class="quickLinks" id="s3">Groups</div>
	<div class="quickLinks" id="s4">Roles</div>
	<div id="navMenu" class="navM"><div style="height:2vh; float:left; clear:both;"></div><a class="navLinks navM" href="../index.php">Home</a><a class="navLinks navM" href="../workflow/">Workflow</a><a class="navLinks navM" href="#">MyDesk</a><a class="navLinks navM" href="#">Finance</a></div>
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
	var dumpData1 = 0;
	var dumpData2 = 0;
	var hasAddedUser = [0,0,0,0];
	
	<?php
	global $nameArray, $idArray, $currentGLabel, $employeeList, $superCount, $groupFind, $roleVar, $empNameArr, $empIDArr, $appendLeader;
	echo "var nameArray = ".$nameArray.";";
	echo "var idArray = ".$idArray.";";
	echo "var acceptArray = ".$currentGLabel.";";
	echo $employeeList;
	echo $superCount;
	echo $groupFind;
	echo $roleVar;
	echo "var empNameArray = ".$empNameArr.";";
	echo "var empIDArray = ".$empIDArr.";";
	echo $appendLeader;
	?>
	var empBuildCount = 0;
	var crntIDArr = [];
	jQuery.each( empNameArray, function( i, val ) {
	var getEmpID = empIDArray[empBuildCount];
	crntIDArr[val] = getEmpID;
	empBuildCount++;
	});
	</script>
	</div>
	<div id="page1" class="subpage">
	<div id="page1wrapper" class="pWrap">
	<div style="position:absolute; top:0; left:0; width: 100%; height:4%; margin-top:1%; font-size:1.5vw; font-family:Quicksand;"><span style="float:left; margin-left:2%; margin-right:3%; cursor:pointer;">List</span><span style="float:left; cursor:pointer;">Grid</span></div>
	<div id="HolderBox"></div>
	</div>
	<div class="rightBox">
	<div class="tabBox"><div id="uTab1" class="addBoxTabs activeTab">Overview</div><div id="uTab2" class="addBoxTabs inactiveTab">Add Staff</div><div id="uTab3" class="addBoxTabs inactiveTab">Edit</div></div>
	<script>
	function subpage1() {
	$(".userBox").hide();
	fixTab();
	crntTab = "uTab1";
	$("#uTab1").removeClass("inactiveTab").addClass("activeTab");
	$("#box1").show();
		if (dumpData1 == 0) { dumpData1 = 1; 
		jQuery.each(employees, function( i, val ) {
		$("#HolderBox").append(val);
		});
		var overEmployee = empCount + superCount; 
		$("#staffOver").append("<span class='staffElement'>Current Staff: "+overEmployee+"</span><span class='staffElement'>Current Supervisors: "+superCount+"</span><span class='staffElement'>Current Employees: "+empCount+"</span><div style='height:2vh; float:left; width:100%; clear:both'></div><span class='staffElement'>Current Groups: "+groupCount+"</span><span class='staffElement'>Current Roles: "+roleCount+"</span>"); }
				
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
					overEmployee++;
					var newEmployee = "<div style='float:left; width:100%; height:5vh; padding-top:2vh; border-bottom:1px solid grey; clear:both;'><span id='"+empCount+"' style='float:left;'>"+empCount+". "+realname.value+"</span></div>";
					employees.push(newEmployee);
					nameArray.push(realname.value);
					$("#HolderBox").append(newEmployee);
					$('#username').val('');
					$('#realname').val('');
					$('#password').val('');
					$('#confirmpwd').val('');
					hasAddedUser = [0,1,1,1];
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
	<div id="staffOver"><div id="staffTitle">Staff Overview</div></div>
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
		<div id="editStaff">Edit Staff</div>
		<div id="empListNow">
						</div>
					</div>
				</div>
			</div>
			
	<div id="page2" class="subpage" style="display:none;">
	<div id="page2wrapper" class="pWrap">
		<div id="ViewSwitch"><div class="switchLinks switchBold" id="ItinerarySwitch">Schedule</div><div class="switchLinks" id="CalendarSwitch">Calendar</div></div>
		<div id="ItineraryBox">
		<?php include 'components/schedule.php'; ?>
		</div>
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
		
		var scheduleTab = 0;
		$("#CalendarSwitch").on("click", function() {
		if (scheduleTab != 1) {
		$("#ItineraryBox").fadeOut(250);
		$("#ItinerarySwitch").removeClass("switchBold");
		$("#CalendarSwitch").addClass("switchBold");
		setTimeout(function() {
		$("#EventCalendar").fadeIn(250);
		scheduleTab = 1;
			}, 250);
		}
		});
		$("#ItinerarySwitch").on("click", function() {
		if (scheduleTab != 0) {
		$("#EventCalendar").fadeOut(250);
		$("#CalendarSwitch").removeClass("switchBold");
		$("#ItinerarySwitch").addClass("switchBold");
		setTimeout(function() {
		$("#ItineraryBox").fadeIn(250);
		scheduleTab = 0;
			}, 250);
		}
		});
		
		function UpdateList() {
		var AddNDate = $('#AddDate').val();
		var AddNTitle = $('#AddTitle').val();
		var TimeNStart = $('#TimeStart').val();
		var TimeNEnd = $('#TimeEnd').val();
		function FixTime(thisTime) {
		thisTime = thisTime.split(":");
		var GrabFTime = thisTime[0];
		if (GrabFTime > 12 ) {
		GrabFTime = GrabFTime - 12;
		if (GrabFTime == 0 || GrabFTime == '0') { GrabFTime = 12; }
		var EndStart = GrabFTime+":"+thisTime[1]+" PM";
		} else { var EndStart = thisTime[0]+":"+thisTime[1]+" AM"; }
		return EndStart;
		}
		var FinalStart = FixTime(TimeNStart);
		var FinalEnd = FixTime(TimeNEnd);
		$.ajax({
            type: "POST",
			url: "operations/updateRequest.php", 
			data: { dataRequest: "AddSchedule", AddDate: AddNDate, AddTitle: AddNTitle, TimeStart: FinalStart, TimeEnd: FinalEnd },
            success: function(data){
			console.log(data);
			var retDate = AddNDate.split("-");
			var monthArr = ['Nothing', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
			var retMonth = retDate[1];
			retMonth = retMonth.replace("0", "");
			var retDay = retDate[2];
			var retYear = retDate[0];
			var textMonth = monthArr[retMonth];
			var finalDate = textMonth+retDay+retYear;
			var checkExist = $("#"+finalDate).attr("class");
			if (checkExist == "scheduleElement") {
			var grabScheduleContent = $("#"+finalDate).find(".scheduleDetail").text();
			if (grabScheduleContent == 'None') { $("#"+finalDate).find(".scheduleDetail").remove(); }
			$("#"+finalDate).append("<div class='scheduleDetail'><b>"+AddNTitle+"</b> - "+FinalStart+" to "+FinalEnd+"</div>"); 
			}
			$('#AddDate').val('');
			$('#AddTitle').val('');
			$('#TimeStart').val('');
			$('#TimeEnd').val('');
				}
			});
		}
		$("#addSchedule").on("click", UpdateList);
				};
			</script>
		</div>
		<div class="rightBox">
			<div class="tabBox"><div id="uTab4" class="addBoxTabs activeTab">New</div><div id="uTab5" class="addBoxTabs inactiveTab">View</div><div id="uTab6" class="addBoxTabs inactiveTab">Edit</div></div>
		<div id="box4" class="userBox">
			<div style="font-family: PT Sans Narrow !important; color:#000 !important; text-transform:none !important; font-size:14px !important;">
			<div style="clear:both; margin-top:5px;"><span style="font-weight:bold; font-size:20px; margin-right:10px;">Employee:</span><input type="text" style="width:66%;" id="AddTitle" name="AddTitle" placeholder="Full Name" autocomplete="off" required></div>
			<div style="clear:both; margin-bottom:5px;"><span style="font-weight:bold; font-size:20px; margin-right:10px;">Date:</span><input type="date" name="AddDate" id="AddDate" required/></div>
			<div style="float:left; margin-right:10px; font-weight:bold; font-size:20px;">Start:</div><input type="time" style="float:left;" name="TimeStart" id="TimeStart" /><div style="float:left; margin-left:15px; margin-right:10px; font-weight:bold; font-size:20px;">End:</div><input type="time" style="float:left;" name="TimeEnd" id="TimeEnd" />
			<button id='addSchedule' name='addSchedule' class='scheduleSubmit'>Add Schedule</button>
						</div>
				</div>
			<div id="box5" class="userBox"><div id="taskList"></div></div>
			</div>
		</div>
			
		<div id="page3" class="subpage" style="display:none;">
		<div id="page3wrapper" class="pWrap">
		<script>
		function subpage3() {
		$("#newLeaders").autocomplete({source: nameArray});
		
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
			console.log(gAltLabel);
		if (gAltLabel !== '') { newLabel = gAltLabel; labelAdd = 1; } else { newLabel = gLabel; }
			var addLeader = $("#newLeaders").val();
			var expLeader = $("#currentLeaders").val();
			var newLeader = ''; var leaderAdd = 0;
		if (expLeader !== '') { newLeader = expLeader; } else { newLeader = addLeader; leaderAdd = 1; }
		var gDesc = $("#groupD").val();
		var checkReqAdd = [gTitle, newLabel, newLeader, gDesc];
		for (g=0; g<=4; g++) {	var thisValue = checkReqAdd[g];	if (thisValue == '') { warnOn = 1; } }
		if (warnOn == 1) { requiredField(); console.log(checkReqAdd); return false; }
		else { processingData = 1;
				$.ajax({
					type: "POST",
					url: "operations/updateRequest.php", 
					data: { dataRequest: "AddGroup", groupID: groupCount, groupTitle: gTitle, groupLabel: newLabel, labelAR: labelAdd, groupSuper: newLeader, superAR: leaderAdd, labelDesc: gDesc, acceptLabel: acceptArray },
					success: function (data) {
					$("#compGroups").html(data);
					console.log(data);
					processingData = 0;
					}
				});
				}
				}
			});
		if (dumpData2 == 0) { dumpData2 = 1; 
		var countAddUser = 0;
		jQuery.each(nameArray, function( i, val ) {
		var getUserIDGroup = idArray[countAddUser];
		$("#addGroupUser").append("<div id='float"+getUserIDGroup+"' class='popGroupName'>"+val+"</div>");
		countAddUser++;
				});
		$(".popGroupName").draggable({ containment: $("#page3")});
		$(".dropTarget").droppable({
		drop: function( event, ui ) { 
		var userGroupID = ui.draggable.prop('id');
		var changeUGID = userGroupID.substr(5);
		var userMemParent = $(this).parent().attr("id");
		var getUserMemContent = $("#"+userGroupID).text();
		$(this).parent().find(".addGroupList").append("<div id='user"+changeUGID+"' class='"+userMemParent+"-child listItemGroup'><span style='pointer-events:none;'><b>•</b>"+getUserMemContent+"</span></div>");
		$("#"+userGroupID).remove();
		$(".listItemGroup").mousedown(function(e) {
		if (e.which == 1) {
		var replaceUserIDGroup = $(this).attr("id");
		var fixGroupUID = replaceUserIDGroup.substr(4);
		var gElementReplaceContent = $(this).text();
		gElementReplaceContent = gElementReplaceContent.substr(1);
		var mouseX, mouseY;
		$(document).mousemove(function(e) {
			mouseX = e.pageX;
			mouseY = e.pageY;
			});
			$("#"+replaceUserIDGroup).parent().parent().mouseleave( function(e) {
			$("#"+replaceUserIDGroup).parent().parent().unbind("mouseleave");
			e.stopImmediatePropagation();
			$(document).unbind("mousemove");
			$(".popGroupName").draggable( "disable" );
			$("#"+replaceUserIDGroup).remove();
			$("#page3").append("<div id='float"+fixGroupUID+"' class='popGroupName' style='position:absolute; width:16.7%; display:none;'>"+gElementReplaceContent+"</div>");
			var getGGElementNoReWidth = $("#float"+fixGroupUID).width();
			var getGGElementNoReHeight = $("#float"+fixGroupUID).height();
			getGGElementNoReWidth = getGGElementNoReWidth - 60;
			getGGElementNoReHeight = getGGElementNoReHeight + 20;
			var correctGGNoReLeft = (mouseX - getGGElementNoReWidth)+"px";
			var correctGGNoReTop = (mouseY - getGGElementNoReHeight)+"px";
			$("#float"+fixGroupUID).css({"left":correctGGNoReLeft, "top":correctGGNoReTop}).show();
			$(".popGroupName").draggable({ containment: $("#page3")});
			$(".popGroupName").draggable( "enable" );
								})
							}
						});
					}
				});
			}
		$(".groupAddBtn").on("click", function() {
			var findGroupID = $(this).parent().attr("id");
			var updateUGUser = '';
			var countUGArray = 0;
			var upUGArray = ['Nothing'];
			var saveGroupTitle = $(this).parent().find(".gNameGroup").text();
			$("."+findGroupID+"-child").each(function() {
			countUGArray++;
			var getThisUGID = $(this).attr("id");
			getThisUGID = getThisUGID.substr(4);
			updateUGUser = updateUGUser+getThisUGID+"+|+";
			upUGArray.push(getThisUGID);
			$(this).removeClass(findGroupID+"-child");
				})
			if (countUGArray != 0) {
			updateUGUser = updateUGUser.substring(0,updateUGUser.length - 3)
			$.ajax({
					type: "POST",
					url: "operations/updateRequest.php", 
					data: { dataRequest: "AddGroupUser", userGroupArray: updateUGUser, ggCountArr: countUGArray },
					success: function (data) {
					for (b = 1; b <= countUGArray; b++) {
					var crntGCountLoop = upUGArray[b];
					$("span#group"+crntGCountLoop).empty();
					$("span#group"+crntGCountLoop).append("<b>Group:</b> "+saveGroupTitle);
					}
					console.log(data);
						}
					})
				}
			else { return false; }
			})
		}
		</script>
		<div id="compGroups">
		<?php
		global $groupCount;
		if ($groupCount !== '0') {
		global $groupList;
		echo $groupList;}
		else { echo "No groups currently available"; }
		?>
		</div>
		</div>
		<div class="rightBox">
			<div class="tabBox"><div id="uTab7" class="addBoxTabs activeTab">Groups</div><div id="uTab8" class="addBoxTabs inactiveTab">Assign</div><div id="uTab9" class="addBoxTabs inactiveTab">Edit</div></div>
		<div id="box7" class="userBox">
		<div id="addGroup">
		<span id="addTitle">Create New Group</span>
		<div style="float:left; width:100%; height:1.7vh; clear:both;"></div>
		<div class="groupAdd"><span class="addSpan">Group Title:</span><input id="groupTitle" type="text" style="float:right; width:60%; height:3vh;" placeholder="Sales Team"/></div>
		<div id="groupName" class="groupAdd"><span class="addSpan" style="line-height:3.3vh">Group Label:</span><select id="currentLabels" style="float:left; margin-left:1.4vw; width:40%; height:3vh;">
		<?php
		global $buildMenu;
		echo $buildMenu;
		?>
		</select><br>
		<span class="addSpan" style="font-size:0.9vw; font-weight:bold; margin-left:3vw; clear:left;" >Add New:</span><input id="groupLabel" type="text" style="float:right; width:50%; margin-right: 2.2vw; height:3vh;" placeholder="Office"/></div>
		<div id="groupLeader" class="groupAdd"><span class="addSpan" style="line-height:3.3vh">Assign Leader:</span><input id="newLeaders"><br>
		<span class="addSpan" style="font-size:0.9vw; font-weight:bold; margin-left:2.6vw; clear:left;">Add Leader:</span><select id="currentLeaders" style="float:left; margin-left:0.45vw; width:40%; height:3vh;">
		<script>
		jQuery.each(supervisors, function( i, val ) {
		$("#currentLeaders").append(val);
		});
		</script>
		</select></div>
		<div id="groupDesc" class="groupAdd" style="margin-top: -0.7vh;"><textarea id="groupD" style="float:left; width:90%; margin-left: 0.8vw; height:9vh;" cols="30" rows="10" placeholder="Brief description of this group."/></textarea></div>
		<button id="addGroupButton" style="float:left; width:80%; height:5.5vh; margin-left:2vw; clear:both; background:#f6f6f6; box-shadow: 3px 3px 0 #DDD;">Add Group</button>
		</div>
		</div>
		<div id="box8" class="userBox"><div id="threePageB2">Assign User</div><div id="addGroupUser"></div></div>
		<div id="box9" class="userBox"></div>
		</div>
		</div>
		
		<div id="page4" class="subpage" style="display:none;">
		<div id="page4wrapper" class="pWrap">
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
					url: "operations/updateRequest.php", 
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
		
		$("#colorPicker").change(function() {
		var schemeStart = $("#colorPicker").spectrum("get");
		schemeNext = schemeStart.toHsv();
		var schemeColor = schemeStart.toRgb();
		hScheme = schemeNext['h'];
		sScheme = schemeNext['s'];
		vScheme = schemeNext['v'];
		var finishScheme = Please.make_scheme({h:hScheme, s:sScheme, v:vScheme},{scheme_type: 'analogous', format: 'rgb'});
		var firstColor = "rgba("+schemeColor['r']+","+schemeColor['g']+","+schemeColor['b']+",1) 0%,";
		var secondColor = "rgba("+finishScheme[0]['r']+","+finishScheme[0]['g']+","+finishScheme[0]['b']+",1) 10%,";
		var thirdColor = "rgba("+finishScheme[1]['r']+","+finishScheme[1]['g']+","+finishScheme[1]['b']+",1) 20%,";
		var fourthColor = "rgba("+finishScheme[2]['r']+","+finishScheme[2]['g']+","+finishScheme[2]['b']+",1) 60%,";
		var fifthColor = "rgba("+finishScheme[3]['r']+","+finishScheme[3]['g']+","+finishScheme[3]['b']+",1) 100%";
		var backgroundSelect = "background: -webkit-linear-gradient(-60deg, "+firstColor+secondColor+thirdColor+fourthColor+fifthColor+");";
		console.log(backgroundSelect);
		});
		}
		</script>
		<div id="allRoles">
		<?php
		global $roleUCount;
		if ($roleUCount !== '0') {
		global $roleList;
		echo $roleList;}
		else { echo "No groups currently available"; }
		?>
		</div>
		</div>
		<div class="rightBox">
			<div class="tabBox"><div id="uTab10" class="addBoxTabs activeTab">Create</div><div id="uTab11" class="addBoxTabs inactiveTab">Edit</div><div id="uTab12" class="addBoxTabs inactiveTab">Settings</div></div>
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
                <span class="error">You are not authorized to access this page.</span> Please <a href="/login.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>