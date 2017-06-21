<?php
include_once 'db_connect.php';
if (isset($_GET['acceptThis'])) {
$setPrimary = $_GET['acceptThis'];
$primaryQuery = "UPDATE accepted_jobs SET primary_job = 1 WHERE jobsID = ".$setPrimary;
if($updatePrimary = $mysqli->query($primaryQuery)) {
header('Location: /protected_page.php');}
else {
printf ($mysqli->error);
} 
}
?>