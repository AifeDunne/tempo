<?php
	require $_SERVER['DOCUMENT_ROOT'].'/cloud_data/Cloudinary.php';
	require $_SERVER['DOCUMENT_ROOT'].'/cloud_data/Uploader.php';
	require $_SERVER['DOCUMENT_ROOT'].'/cloud_data/Api.php';
	\Cloudinary::config(array(
	"cloud_name" => "system-overflow", 
	"api_key" => "521918182833273", 
	"api_secret" => "KRuEJj_btOmIMmMnBkAS0FdCoHY"
	));
?>