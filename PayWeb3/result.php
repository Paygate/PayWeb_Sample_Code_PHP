<?php
/*
 * Copyright (c) 2021 PayGate (Pty) Ltd
 *
 * Author: App Inlet (Pty) Ltd
 *
 * Released under the GNU General Public License
 */

	/*
	 * Once the client has completed the transaction on the PayWeb page, they will be redirected to the RETURN_URL set in the initate
	 * Here we will check the transaction status and process accordingly
	 *
	 */

	include_once('../lib/php/global.inc.php');

	/*
	 * Include the helper PayWeb 3 class
	 */
	require_once('paygate.payweb3.php');

/*
* We do not use $_SESSION to save information as PayWeb no longer maintains the session on return from the payment portal
*
* In production code the PAYGATE_ID and PAYGATE_SECRET
* would be stored in configuration and retrieved from
* there.
*
* For this demo code we will use only the test credentials
*/
const PAYGATE_ID = '10011072130';
const PAYGATE_SECRET = 'secret';

// Retrieve from config in production code
$paygateId = PAYGATE_ID; // for demo only
$paygateSecret = PAYGATE_SECRET; // for demo only

// Retrieve reference from query string
$reference = filter_var($_GET['reference'], FILTER_SANITIZE_STRING);
$reference = $reference != '' ? $reference : '';

	/*
	 * insert the returned data as well as the merchant specific data PAYGATE_ID and REFERENCE in array
	 */
	$data = array(
		'PAYGATE_ID'         => $paygateId,
		'PAY_REQUEST_ID'     => $_POST['PAY_REQUEST_ID'],
		'TRANSACTION_STATUS' => $_POST['TRANSACTION_STATUS'],
		'REFERENCE'          => $reference,
		'CHECKSUM'           => $_POST['CHECKSUM']
	);

	/*
	 * initiate the PayWeb 3 helper class
	 */
	$PayWeb3 = new PayGate_PayWeb3();
	/*
	 * Set the encryption key of your PayGate PayWeb3 configuration
	 */
	$PayWeb3->setEncryptionKey($paygateSecret);
	/*
	 * Check that the checksum returned matches the checksum we generate
	 */
	$isValid = $PayWeb3->validateChecksum($data)
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
	<head>
	    <meta http-equiv="content-type" content="text/html; charset=utf-8">
	    <title>PayWeb 3 - Result</title>
		<link rel="stylesheet" href="../lib/css/bootstrap.min.css">
		<link rel="stylesheet" href="../lib/css/paygate.css">
	</head>
	<body>
    <div class="container container-sm">
        <div class="z-top">Step: Result</div>
        <div class="z-nav login">
            <ul class="nav nav-tabs flex-nowrap" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="result-tab" href="#result" role="tab" data-toggle="tab">
                        Result</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="query-tab" href="/<?php
                    echo $root; ?>/index.php" role="tab">Initiate</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="query-tab" href="/<?php
                    echo $root; ?>/query.php" role="tab">Query</a>
                </li>
            </ul>
        </div>
        <div class="tab-content tab-content-basic">
            <div class="tab-pane fade show active" id="result" role="tab-panel">
                <form class="form" id="loginForm" name="query_paygate_form" action="query.php" method="post">
                    <div class="z-form-group-wrapper">
                        <div class="form-group z-formgroup d-flex">
                            <label for="checksumResult">Checksum result</label>
                            <p id="checksumResult" class="ml-auto"><?php echo !$isValid ? 'The checksums do not match <i class="glyphicon glyphicon-remove text-danger"></i>' : 'Checksums match OK <i class="glyphicon glyphicon-ok text-success"></i>'; ?></p>
                        </div>
                        <div class="form-group z-formgroup d-flex">
                            <label for="PAY_REQUEST_ID">Pay Request ID</label>
                            <p id="PAY_REQUEST_ID" class="ml-auto"><?php echo $data['PAY_REQUEST_ID']; ?></p>
                        </div>
                        <div class="form-group z-formgroup d-flex">
                            <label for="TRANSACTION_STATUS">Transaction Status</label>
                            <p id="TRANSACTION_STATUS" class="ml-auto"><?php echo $data['TRANSACTION_STATUS']; ?> (<?php echo $PayWeb3->getTransactionStatusDescription($data['TRANSACTION_STATUS']) ?>)</p>
                        </div>
                        <div class="form-group z-formgroup d-flex">
                            <label for="CHECKSUM">Checksum</label>
                            <p id="CHECKSUM" class="ml-auto"><?php echo $data['CHECKSUM']; ?></p>
                        </div>
                    </div>
                    <!-- Hidden fields to post to the Query service -->
                    <input type="hidden" name="PAYGATE_ID" value="<?php echo $data['PAYGATE_ID']; ?>" />
                    <input type="hidden" name="PAY_REQUEST_ID" value="<?php echo $data['PAY_REQUEST_ID']; ?>" />
                    <input type="hidden" name="REFERENCE" value="<?php echo $data['REFERENCE']; ?>" />
                    <input type="hidden" name="encryption_key" value="<?php echo $paygateSecret; ?>" />
                    <div class="z-buttons">
                        <input type="submit" class="btn btn-success" value="Query PayGate" name="btnSubmit">
                        <p class="mt-5"><a href="index.php" class="btn btn-primary">New Transaction</a></p>
                        <p id="loginimageregister"><img src="../lib/images/PayGate_logo.svg" alt="logo" width="150px">
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
		<script type="text/javascript" src="../lib/js/jquery.min.js"></script>
		<script type="text/javascript" src="../lib/js/bootstrap.min.js"></script>
	</body>
</html>
