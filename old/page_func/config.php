<?php
require_once 'anet_php_sdk/AuthorizeNet.php';
$METHOD_TO_USE = "AIM";
define("AUTHORIZENET_API_LOGIN_ID", "9G72cD9sj5T");
define("AUTHORIZENET_TRANSACTION_KEY", "9k5Le6484C2bZ6M7");
define("AUTHORIZENET_SANDBOX",false);       // Set to false to test against production
define("TEST_REQUEST", "FALSE");           // You may want to set to true if testing against production
define("AUTHORIZENET_MD5_SETTING","");                // Add your MD5 Setting.
$site_root = "http://workflow.systemoverflow.com/"; // Add the URL to your site

include_once 'db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/functions.php';
