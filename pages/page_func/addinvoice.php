<?php
require_once 'config.php';
sec_session_start();
?>
	
<html>
  <head>
    <title>Elegant Garage Doors</title>
    <link rel="stylesheet" href="style.css">
	<style>
	.subItems { margin-top:1vh; }
	</style>
  </head>
  <?php if (login_check($mysqli) == true) : ?>
  <body>
    <?php
	$unfinished = $_GET['unfinish'];
	if ($unfinished === 0) {
	$pID = $_GET['primaryID'];
	$eID = $_GET['empID'];
	$cID = $_GET['customerID'];
	$dID = $_GET['completeJob'];
	if (!empty($_POST['addcost'])) { $addcost = $_POST['addcost']; } else { $addcost = 0.00; }
	if (!empty($_POST['comments'])) { $comments = $_POST['comments']; } else { $comments = NULL; }
	if (!empty($_GET['costID'])) {
	$toteCost = $_GET['costID'];
	$tID = $_GET['costID'];
	if (isset($_GET['depositID'])) { $depID = $_GET['depositID']; $toteCost = $_GET['depositID']; } else { $depID = 0;}
	}
	$variableString = "pID=".$pID."&eID=".$eID."&cID=".$cID."&dID=".$dID."&addcost=".$addcost."&tID=".$tID."&depID=".$depID."&getPrice=".$toteCost."&comments=".$comments."&unfinish=".$unfinished;
    } else {
	$changeID = $_GET['lastID'];
	$uEmployee = $_GET['empID'];
	$toteCost = $_GET['remainPay'];
	$variableString = "changeID=".$changeID."&uEmployee=".$uEmployee."&unfinish=".$unfinished;
	}
	if ($METHOD_TO_USE == "AIM") {
        ?>
        <form method="post" action="process_sale.php?<?php echo $variableString; ?>" id="checkout_form" style="width:50%;margin-top:4vh;margin-left:2vw;">
        <?php
    } else {
        ?>
        <form method="post" action="<?php echo (AUTHORIZENET_SANDBOX ? AuthorizeNetDPM::SANDBOX_URL : AuthorizeNetDPM::LIVE_URL)?>" id="checkout_form" style="width:50%;margin-top:4vh;margin-left:2vw;">
        <?php
        $time = time();
        $fp_sequence = $time;
        $fp = AuthorizeNetDPM::getFingerprint(AUTHORIZENET_API_LOGIN_ID, AUTHORIZENET_TRANSACTION_KEY, $toteCost, $fp_sequence, $time);
        $sim = new AuthorizeNetSIM_Form(
            array(
            'x_amount'        => $toteCost,
            'x_fp_sequence'   => $fp_sequence,
            'x_fp_hash'       => $fp,
            'x_fp_timestamp'  => $time,
            'x_relay_response'=> "TRUE",
            'x_relay_url'     => $site_root,
            'x_login'         => AUTHORIZENET_API_LOGIN_ID,
            'x_test_request'  => TEST_REQUEST,
            )
        );
        echo $sim->getHiddenFieldString();
    }
    ?>
      <fieldset style="border-style:solid;border-color:#000;">
        <div>
          <label>Credit Card Number</label>
          <input type="text" class="text required creditcard" size="15" name="x_card_num" value="6011000000000012"></input>
        </div>
        <div class="subItems">
          <label>Exp.</label>
          <input type="text" class="text required" size="4" name="x_exp_date" value="04/15"></input>
        </div>
        <div class="subItems">
          <label>CCV</label>
          <input type="text" class="text required" size="4" name="x_card_code" value="782"></input>
        </div>
      </fieldset>
      <fieldset style="border-style:solid;border-top:none;border-color:#000;">
        <div>
          <label>First Name</label>
          <input type="text" class="text required" size="15" name="x_first_name" value="John"></input>
        </div>
        <div class="subItems">
          <label>Last Name</label>
          <input type="text" class="text required" size="14" name="x_last_name" value="Doe"></input>
        </div>
      </fieldset>
      <fieldset style="border-style:solid;border-top:none;border-color:#000;">
        <div>
          <label>Address</label>
          <input type="text" class="text required" size="26" name="x_address" value="123 Four Street"></input>
        </div>
        <div class="subItems">
          <label>City</label>
          <input type="text" class="text required" size="15" name="x_city" value="San Francisco"></input>
        </div>
      </fieldset>
      <fieldset style="border-style:solid;border-top:none;border-color:#000;">
        <div>
          <label>State</label>
          <input type="text" class="text required" size="4" name="x_state" value="CA"></input>
        </div>
        <div class="subItems">
          <label>Zip Code</label>
          <input type="text" class="text required" size="9" name="x_zip" value="94133"></input>
        </div>
        <div class="subItems">
          <label>Country</label>
          <input type="text" class="text required" size="22" name="x_country" value="US"></input>
        </div>
      </fieldset>
      <input type="submit" value="Submit Order" class="submit buy" style="height:5vh;width:11vw;margin-top:1vh;">
    </form>
  </body>
  <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/index.php">login</a>.
            </p>
        <?php endif; ?>
</html>
