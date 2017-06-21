<?php
include_once 'db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/functions.php';
sec_session_start();
unset($_SESSION['grabSchedule']);
header('Location: /pages/tech_set.php');
?>