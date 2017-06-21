<?php
require_once 'config.php';
sec_session_start();

if ($METHOD_TO_USE == "AIM") {

	$unfinished = $_GET['unfinish'];
	if ($unfinished === 0) {
	$getPrice = $_GET['getPrice'];
	$addcost = $_GET['addcost'];
	$amount = $getPrice + $addcost;
	$pID = $_GET['pID'];
	$eID = $_GET['eID'];
	$cID = $_GET['cID'];
	$dID = $_GET['dID'];
	$comments = $_GET['comments'];
	$tID = $_GET['tID'];
	$depID = $_GET['depID'];
	$variableString = "pID=".$pID."&eID=".$eID."&cID=".$cID."&dID=".$dID."&addcost=".$addcost."&tID=".$tID."&depID=".$depID."&comments=".$comments."&unfinish=".$unfinished;
	} else {
	$changeID = $_GET['changeID'];
	$uEmployee = $_GET['uEmployee'];
	$variableString = "changeID=".$changeID."&uEmployee=".$uEmployee."&unfinish=".$unfinished;
	}
    $transaction = new AuthorizeNetAIM;
    $transaction->setSandbox(AUTHORIZENET_SANDBOX);
    $transaction->setFields(
        array(
        'amount' => $amount, 
        'card_num' => $_POST['x_card_num'], 
        'exp_date' => $_POST['x_exp_date'],
        'first_name' => $_POST['x_first_name'],
        'last_name' => $_POST['x_last_name'],
        'address' => $_POST['x_address'],
        'city' => $_POST['x_city'],
        'state' => $_POST['x_state'],
        'country' => $_POST['x_country'],
        'zip' => $_POST['x_zip'],
        'email' => $_POST['x_email'],
        'card_code' => $_POST['x_card_code'],
        )
    );
    $response = $transaction->authorizeAndCapture();
    if ($response->approved) {
        header('Location: completeInvoice.php?'.$variableString);
    } else {
        header('Location: error_page.php?response_reason_code='.$response->response_reason_code.'&response_code='.$response->response_code.'&response_reason_text=' .$response->response_reason_text);
    }
} elseif (count($_POST)) {
    $response = new AuthorizeNetSIM;
    if ($response->isAuthorizeNet()) {
        if ($response->approved) {
            $return_url = $site_root . '/pages/page_func/completeInvoice.php?'.$variableString;
        } else {
            $return_url = $site_root . 'error_page.php?response_reason_code='.$response->response_reason_code.'&response_code='.$response->response_code.'&response_reason_text=' .$response->response_reason_text;
        }
        echo AuthorizeNetDPM::getRelayResponseSnippet($return_url);
    } else {
        echo "MD5 Hash failed. Check to make sure your MD5 Setting matches the one in config.php";
    }
}