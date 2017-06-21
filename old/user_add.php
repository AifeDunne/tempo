<?php
include_once '../includes/register.inc.php';
include_once '../includes/functions.php';
 
sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Elegant Garage Doors</title>
		<link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
		<style>
		.menuBar {
		float:left; 
		width:97%; 
		height:11%;
		background: -moz-linear-gradient(-45deg,  rgba(37,143,235,0.9) 0%, rgba(22,127,226,0.91) 50%, rgba(16,110,210,0.91) 51%, rgba(23,80,171,0.91) 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,rgba(37,143,235,0.9)), color-stop(50%,rgba(22,127,226,0.91)), color-stop(51%,rgba(16,110,210,0.91)), color-stop(100%,rgba(23,80,171,0.91))); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(-45deg,  rgba(37,143,235,0.9) 0%,rgba(22,127,226,0.91) 50%,rgba(16,110,210,0.91) 51%,rgba(23,80,171,0.91) 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(-45deg,  rgba(37,143,235,0.9) 0%,rgba(22,127,226,0.91) 50%,rgba(16,110,210,0.91) 51%,rgba(23,80,171,0.91) 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(-45deg,  rgba(37,143,235,0.9) 0%,rgba(22,127,226,0.91) 50%,rgba(16,110,210,0.91) 51%,rgba(23,80,171,0.91) 100%); /* IE10+ */
		background: linear-gradient(135deg,  rgba(37,143,235,0.9) 0%,rgba(22,127,226,0.91) 50%,rgba(16,110,210,0.91) 51%,rgba(23,80,171,0.91) 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e6258feb', endColorstr='#e81750ab',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
		color:#FFF;
		font-family: 'Muli', sans-serif;
		}
		.FPageLinks { width: auto; font-size: 2vw; margin-left: 2.8vw; line-height: 5vw; color:#FFF; text-decoration:none;}
		.FPageLinks:hover {text-decoration:underline; }
		</style>
    </head>
    <body style="background:url(/background/BG4L.jpg); background-size: cover  !important; background-repeat: no-repeat  !important; background-attachment: fixed  !important; background-position: center !important;">
        <?php if (login_check($mysqli) == true) : ?>
	<div style="float: left; width: 21%; height: 87vh; margin-top:0; margin-left: -0.5vw; background:rgba(255,255,255,0.7);">
		<div id="Link1" class="menuBar" style="margin-bottom:4.85%; margin-top:4.85%; border-bottom:2px solid white; border-right:10px solid white;"><a href="/protected_page2.php" class="FPageLinks">Home</a></div>
		<div id="Link2" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/tech_set.php" class="FPageLinks">Set Technician</a></div>
		<div id="Link3" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobAssign.php" class="FPageLinks">Set Jobs</a></div>
		<div id="Link4" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/job_add.php" class="FPageLinks">Unassign Jobs</a></div>
		<div id="Link5" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/progJobs.php" class="FPageLinks">On-Hold Jobs</a></div>
		<div id="Link6" class="menuBar" style="margin-bottom:4.85%; border-top:2px solid white; border-bottom:2px solid white; border-right:10px solid white;"><a href="/pages/jobComplete.php" class="FPageLinks">Completed Jobs</a></div>
		<div id="Link7" class="menuBar" style="height:11%; border-top:2px solid white; border-right:10px solid white;"><a href="/includes/logout.php" class="FPageLinks">Log Out</a></div>
	</div>
	<div style="float:left; width:38%; margin-left:2vw; margin-bottom:2vw; background:rgba(255,255,255,0.9); padding: 3vw;">
	 <?php if (!empty($error_msg)) { echo $error_msg; } ?>
        <ul>
            <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
            <li>Emails must have a valid email format</li>
            <li>Passwords must be at least 6 characters long</li>
            <li>Passwords must contain
                <ul>
                    <li>At least one uppercase letter (A..Z)</li>
                    <li>At least one lower case letter (a..z)</li>
                    <li>At least one number (0..9)</li>
                </ul>
            </li>
            <li>Your password and confirmation must match exactly</li>
        </ul>
        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post" name="registration_form">
            Username: <input type='text' name='username' id='username' /><br>
            Email: <input type="text" name="email" id="email" /><br>
			Employee Name: <input type='text' name='realname' id='realname' /><br>
            Password: <input type="password"name="password" id="password"/><br>
            Confirm password: <input type="password" name="confirmpwd" id="confirmpwd" /><br>
            <input type="button" value="Register" onclick="return regformhash(this.form, this.form.username, this.form.email, this.form.password, this.form.confirmpwd);" /> 
        </form>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>