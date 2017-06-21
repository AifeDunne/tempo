<?php
include_once 'includes/system_function.php';
 
sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tempo - Business Management</title>
		<link href='http://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="homePage/homePage.css" />
		<script type="text/JavaScript" src="js/jQuery.js"></script> 
    </head>
    <body>
        <?php if (login_check($mysqli) == true) : ?>
	<?php
	if ($_SESSION['userPriv'] === '1') { include 'homePage/employee1.php'; }
	else if ($_SESSION['userPriv'] === '2') { include 'homePage/supervisor1.php'; }
	?>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/login.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>