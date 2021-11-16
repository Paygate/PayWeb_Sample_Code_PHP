<?php
/*
 * Copyright (c) 2021 PayGate (Pty) Ltd
 *
 * Author: App Inlet (Pty) Ltd
 *
 * Released under the GNU General Public License
 */

/*
 * Once the client is ready to be redirected to the payment page, we get all the information needed and initiate the transaction with PayGate.
 * This checks that all the information is valid and that a transaction can take place.
 * If the initiate is successful we are returned a request ID and a checksum which we will use to redirect the client to PayWeb3.
 */

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

include_once('../lib/php/global.inc.php');



/*
 * Include the helper PayWeb 3 class
 */
require_once('paygate.payweb3.php');

$reference = filter_var($_POST['REFERENCE'], FILTER_SANITIZE_STRING);
$mandatoryFields = array(
    'PAYGATE_ID'       => filter_var($_POST['PAYGATE_ID'], FILTER_SANITIZE_STRING),
    'REFERENCE'        => $reference,
    'AMOUNT'           => filter_var($_POST['AMOUNT'] * 100, FILTER_SANITIZE_NUMBER_INT),
    'CURRENCY'         => filter_var($_POST['CURRENCY'], FILTER_SANITIZE_STRING),
    // Reference added so it can be retrieved in result
    'RETURN_URL'       => filter_var($_POST['RETURN_URL'], FILTER_SANITIZE_URL) . '?reference=' . $reference,
    'TRANSACTION_DATE' => filter_var($_POST['TRANSACTION_DATE'], FILTER_SANITIZE_STRING),
    'LOCALE'           => filter_var($_POST['LOCALE'], FILTER_SANITIZE_STRING),
    'COUNTRY'          => filter_var($_POST['COUNTRY'], FILTER_SANITIZE_STRING),
    'EMAIL'            => filter_var($_POST['EMAIL'], FILTER_SANITIZE_EMAIL)
);

$optionalFields = array(
    'PAY_METHOD'        => (isset($_POST['PAY_METHOD']) ? filter_var(
        $_POST['PAY_METHOD'],
        FILTER_SANITIZE_STRING
    ) : ''),
    'PAY_METHOD_DETAIL' => (isset($_POST['PAY_METHOD_DETAIL']) ? filter_var(
        $_POST['PAY_METHOD_DETAIL'],
        FILTER_SANITIZE_STRING
    ) : ''),
    'NOTIFY_URL'        => (isset($_POST['NOTIFY_URL']) ? filter_var($_POST['NOTIFY_URL'], FILTER_SANITIZE_URL) . '?reference=' . $reference : ''),
    'USER1'             => (isset($_POST['USER1']) ? filter_var($_POST['USER1'], FILTER_SANITIZE_URL) : ''),
    'USER2'             => (isset($_POST['USER2']) ? filter_var($_POST['USER2'], FILTER_SANITIZE_URL) : ''),
    'USER3'             => (isset($_POST['USER3']) ? filter_var($_POST['USER3'], FILTER_SANITIZE_URL) : ''),
    'VAULT'             => (isset($_POST['VAULT']) ? filter_var($_POST['VAULT'], FILTER_SANITIZE_NUMBER_INT) : ''),
    'VAULT_ID'          => (isset($_POST['VAULT_ID']) ? filter_var($_POST['VAULT_ID'], FILTER_SANITIZE_STRING) : '')
);

// Override POST value for demo only
$mandatoryFields['PAYGATE_ID'] = PAYGATE_ID;

$data = array_merge($mandatoryFields, $optionalFields);

$encryption_key = $_POST['encryption_key'];
// Override POST value for demo only
$encryption_key = PAYGATE_SECRET;

/*
 * Initiate the PayWeb 3 helper class
 */
$PayWeb3 = new PayGate_PayWeb3();
/*
 * if debug is set to true, the curl request and result as well as the calculated checksum source will be logged to the php error log
 */
$PayWeb3->setDebug(true);
/*
 * Set the encryption key of your PayGate PayWeb3 configuration
 */
$PayWeb3->setEncryptionKey($encryption_key);
/*
 * Set the array of fields to be posted to PayGate
 */
$PayWeb3->setInitiateRequest($data);

/*
 * Do the curl post to PayGate
 */
$returnData = $PayWeb3->doInitiate();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>PayWeb 3 - Request</title>
    <link rel="stylesheet" href="../lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="../lib/css/paygate.css">
</head>
<body>
<div class="container container-sm">
    <div class="z-top">Step: Request / Redirect</div>
    <div class="z-nav login">
        <ul class="nav nav-tabs flex-nowrap" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="request-tab" href="#request" role="tab" data-toggle="tab">
                    Request</a>
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
        <div class="tab-pane fade show active" id="request" role="tab-panel">
            <form class="form" id="loginForm" action="<?php
            echo $PayWeb3::$process_url ?>"
                  method="post" name="paygate_process_form">
                <div class="z-form-group-wrapper">
                    <div class="form-group z-formgroup d-flex">
                        <label for="PAYGATE_ID">PayGate ID</label>
                        <p class="ml-auto" id="PAYGATE_ID"><?php
                            echo $data['PAYGATE_ID']; ?></p>
                    </div>
                    <div class="form-group z-formgroup d-flex">
                        <label for="REFERENCE">Reference</label>
                        <p class="ml-auto" id="REFERENCE" class="form-control-static"><?php
                            echo $data['REFERENCE']; ?></p>
                    </div>
                    <div class="form-group z-formgroup d-flex">
                        <label for="AMOUNT">Amount</label>
                        <p id="AMOUNT" class="ml-auto"><?php
                            echo $data['AMOUNT']; ?></p>
                    </div>
                    <div class="form-group z-formgroup d-flex">
                        <label for="CURRENCY">Currency</label>
                        <p id="CURRENCY" class="ml-auto"><?php
                            echo $data['CURRENCY']; ?></p>
                    </div>
                    <div class="form-group z-formgroup d-flex">
                        <label for="RETURN_URL">Return URL</label>
                        <p id="RETURN_URL" class="ml-auto"><?php
                            echo $data['RETURN_URL']; ?></p>
                    </div>
                    <div class="form-group z-formgroup d-flex">
                        <label for="LOCALE">Locale</label>
                        <p id="LOCALE" class="ml-auto"><?php
                            echo $data['LOCALE']; ?></p>
                    </div>
                    <div class="form-group z-formgroup d-flex">
                        <label for="COUNTRY">Country</label>
                        <p id="COUNTRY" class="ml-auto"><?php
                            echo $data['COUNTRY']; ?></p>
                    </div>
                    <div class="form-group z-formgroup d-flex">
                        <label for="TRANSACTION_DATE">Transaction Date</label>
                        <p id="TRANSACTION_DATE" class="ml-auto"><?php
                            echo $data['TRANSACTION_DATE']; ?></p>
                    </div>
                    <div class="form-group z-formgroup d-flex">
                        <label for="EMAIL">Customer Email</label>
                        <p id="EMAIL" class="ml-auto"><?php
                            echo $data['EMAIL']; ?></p>
                    </div>
                    <div class="form-group z-formgroup d-flex">
                        <label for="encryption_key">Encryption Key</label>
                        <p id="encryption_key" class="ml-auto"><?php
                            echo $encryption_key; ?></p>
                    </div>
                    <?php
                    $displayOptionalFields = false;

                    foreach (array_keys($optionalFields) as $key => $value) {
                        if ($data[$value] != '') {
                            $displayOptionalFields = true;
                        }
                    }

                    if ($displayOptionalFields) {
                        echo <<<HTML
					<div class="well">
HTML;


                        if ($data['PAY_METHOD'] != '') {
                            echo <<<HTML
					<div class="form-group z-formgroup d-flex">
						<label for="PAY_METHOD">Pay Method</label>
						<p id="PAY_METHOD" class="ml-auto">{$data['PAY_METHOD']}</p>
					</div>
HTML;
                        }

                        if ($data['PAY_METHOD_DETAIL'] != '') {
                            echo <<<HTML
					<div class="form-group z-formgroup d-flex">
						<label for="PAY_METHOD_DETAIL">Pay Method Detail</label>
						<p id="PAY_METHOD_DETAIL" class="ml-auto">{$data['PAY_METHOD_DETAIL']}</p>
					</div>
HTML;
                        }

                        if ($data['NOTIFY_URL'] != '') {
                            echo <<<HTML
					<div class="form-group z-formgroup d-flex">
						<label for="NOTIFY_URL">Notify Url</label>
						<p id="NOTIFY_URL" class="ml-auto">{$data['NOTIFY_URL']}</p>
					</div>
HTML;
                        }

                        if ($data['USER1'] != '') {
                            echo <<<HTML
					<div class="form-group z-formgroup d-flex">
						<label for="USER1">User Field 1</label>
						<p id="USER1" class="ml-auto">{$data['USER1']}</p>
					</div>
HTML;
                        }

                        if ($data['USER2'] != '') {
                            echo <<<HTML
					<div class="form-group z-formgroup d-flex">
						<label for="USER2">User Field 2</label>
						<p id="USER2" class="ml-auto">{$data['USER2']}</p>
					</div>
HTML;
                        }

                        if ($data['USER3'] != '') {
                            echo <<<HTML
					<div class="form-group z-formgroup d-flex">
						<label for="USER3" class="col-sm-3 control-label">User Field 3</label>
						<p id="USER3" class="form-control-static">{$data['USER3']}</p>
					</div>
HTML;
                        }

                        if ($data['VAULT'] != '') {
                            echo <<<HTML
					<div class="form-group">
						<label for="VAULT" class="col-sm-3 control-label">Vault</label>
						<p id="VAULT" class="form-control-static">{$data['VAULT']}</p>
					</div>
HTML;
                        }

                        if ($data['VAULT_ID'] != '') {
                            echo <<<HTML
					<div class="form-group">
						<label for="VAULT_ID" class="col-sm-3 control-label">Vault ID</label>
						<p id="VAULT_ID" class="form-control-static">{$data['VAULT_ID']}</p>
					</div>
HTML;
                        }

                        echo <<<HTML
					</div>
HTML;
                    } ?>
                </div>
            </form>
            <form class="form" action="<?php
            echo $PayWeb3::$process_url ?>" method="post" name="paygate_process_form">
                <?php
                if (isset($PayWeb3->processRequest) || isset($PayWeb3->lastError)){
                /*
                 * We have received a response from PayWeb3
                 */

                /*
                 * TextArea for display example purposes only.
                 */
                ?>
                <div class="z-form-group-wrapper">
                    <div class="form-group z-formgroup">
                        <label for="request">Request Result</label><br>
                        <textarea class="form-control" rows="3" cols="50" id="request"><?php
                            if ( ! isset($PayWeb3->lastError)) {
                                foreach ($PayWeb3->processRequest as $key => $value) {
                                    echo <<<HTML
{$key} = {$value}

HTML;
                                }
                            } else {
                                /*
                                 * handle the error response
                                 */
                                echo $PayWeb3->lastError;
                            } ?>
						</textarea>
                    </div>
                    <?php
                    if ( ! isset($PayWeb3->lastError)) {
                        /*
                         * It is not an error, so continue
                         */

                        /*
                         * Check that the checksum returned matches the checksum we generate
                         */
                        $isValid = $PayWeb3->validateChecksum($PayWeb3->initiateResponse);

                        if ($isValid) {
                            /*
                             * If the checksums match loop through the returned fields and create the redirect from
                             */
                            foreach ($PayWeb3->processRequest as $key => $value) {
                                echo <<<HTML
					<input type="hidden" name="{$key}" value="{$value}" />
HTML;
                            }
                        } else {
                            echo 'Checksums do not match';
                        }
                    }
                    /*
                     * Submit form as/when needed
                     */
                    ?>
                </div>
                <div class="z-buttons">
                    <input class="btn btn-success" type="submit" name="btnSubmit" value="Redirect"/>
                    <p id="loginimageregister"><img src="../lib/images/PayGate_logo.svg" alt="logo" width="150px">
                    </p>
                </div>
                    <?php
                } ?>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="../lib/js/jquery.min.js"></script>
<script type="text/javascript" src="../lib/js/bootstrap.min.js"></script>
</body>
</html>
