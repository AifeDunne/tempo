<?php

if(!isset($_SESSION)){
    session_start();
}
$_SESSION['user_id'] = 1;

$db = new PDO('mysql:dbname=app_sqldata122;host=powderhousedesignsco.ipagemysql.com', 'app_admin33', '135xDrrr32');

if(!isset($_SESSION['user_id'])) {
    die("You are not signed in.");
}