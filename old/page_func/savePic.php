<?php
	include_once 'cloudCred.php';
	include_once 'db_connect.php';
	define('UPLOAD_DIR', 'images/');
	
	$allPictures = $_POST['pictures'];
	$firstPic = $allPictures[0];
	$secondPic = $allPictures[1];
	$thirdPic = $allPictures[2];
	$primJob = "job".$_POST['jobID'];
	$picCount = '';
	
		function CloudPic($fileImage) {
		global $primJob;
		$fPicture = 'images/'.$fileImage.'.png';
		$fOptions = array("public_id" => $primJob."/".$fileImage);
		\Cloudinary\Uploader::upload($fPicture, $fOptions);
		$removePic = $_SERVER['DOCUMENT_ROOT']."/pages/page_func/".$fPicture;
		unlink($removePic);
		}
		function UploadPic($crntImage, $imgID) {
		$fileName = "notePic".$imgID;
		$crntImage = str_replace('data:image/png;base64,', '', $crntImage);
		$crntImage = str_replace(' ', '+', $crntImage);
		$dataP = base64_decode($crntImage);
		$fileC = UPLOAD_DIR . $fileName . '.png';
		$addPhoto = file_put_contents($fileC, $dataP);
		CloudPic($fileName);
		}
		
	if (strlen($firstPic) !== 1) { UploadPic($firstPic, 1); $picCount.= "1,"; }
	if (strlen($secondPic) !== 1) { UploadPic($secondPic, 2); $picCount.= "2,"; }
	if (strlen($thirdPic) !== 1) { UploadPic($thirdPic, 3); $picCount.= "3,"; }
	if (!empty($picCount)) {
	$picCount = substr($picCount, 0, -1);
	$checkQuery = "INSERT INTO checkList VALUES (NULL, ".$_POST['jobID'].", '".$picCount."')";
	$checkSafety = $mysqli->query($checkQuery);
	}
?>