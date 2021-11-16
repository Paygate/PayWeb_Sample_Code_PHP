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

const PAYGATE_ID     = '10011072130';
const PAYGATE_SECRET = 'secret';

include_once('../lib/php/global.inc.php');

/*
 * Include the helper PayWeb 3 class
 */
require_once('paygate.payweb3.php');

$reference       = filter_var($_POST['REFERENCE'], FILTER_SANITIZE_STRING);
$mandatoryFields = array(
    'PAYGATE_ID'       => filter_var($_POST['PAYGATE_ID'], FILTER_SANITIZE_STRING),
    'REFERENCE'        => $reference,
    'AMOUNT'           => filter_var($_POST['AMOUNT'] * 100, FILTER_SANITIZE_NUMBER_INT),
    'CURRENCY'         => filter_var($_POST['CURRENCY'], FILTER_SANITIZE_STRING),
    'RETURN_URL'       => filter_var($_POST['RETURN_URL'], FILTER_SANITIZE_URL),
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
    <title>PayWeb 3 - Request</title>
    <style type="text/css">
        label {
            margin-top: 5px;
            display: inline-block;
            width: 150px;
        }
    </style>
</head>
<body>
<form action="<?php
echo $PayWeb3::$process_url ?>" method="post" name="paygate_process_form">
    <label for="PAYGATE_ID">PayGate ID</label>
    <span id="PAYGATE_ID"><?php
        echo $data['PAYGATE_ID']; ?></span>
    <br>
    <label for="REFERENCE">Reference</label>
    <span id="REFERENCE"><?php
        echo $data['REFERENCE']; ?></span>
    <br>
    <label for="AMOUNT">Amount</label>
    <span id="AMOUNT"><?php
        echo $data['AMOUNT']; ?></span>
    <br>
    <label for="CURRENCY">Currency</label>
    <span id="CURRENCY"><?php
        echo $data['CURRENCY']; ?></span>
    <br>
    <label for="RETURN_URL">Return URL</label>
    <span id="RETURN_URL"><?php
        echo $data['RETURN_URL']; ?></span>
    <br>
    <label for="LOCALE">Locale</label>
    <span id="LOCALE"><?php
        echo $data['LOCALE']; ?></span>
    <br>
    <label for="COUNTRY">Country</label>
    <span id="COUNTRY"><?php
        echo $data['COUNTRY']; ?></span>
    <br>
    <label for="TRANSACTION_DATE">Transaction Date</label>
    <span id="TRANSACTION_DATE"><?php
        echo $data['TRANSACTION_DATE']; ?></span>
    <br>
    <label for="EMAIL">Customer Email</label>
    <span id="EMAIL"><?php
        echo $data['EMAIL']; ?></span>
    <br>
    <?php
    if ($data['PAY_METHOD'] != '') {
        echo <<<HTML
					<label for="PAY_METHOD">Pay Method</label>
					<span id="PAY_METHOD">{$data['PAY_METHOD']}</span>
					<br>
HTML;
    }

    if ($data['PAY_METHOD_DETAIL'] != '') {
        echo <<<HTML
					<label for="PAY_METHOD_DETAIL">Pay Method Detail</label>
					<span id="PAY_METHOD_DETAIL">{$data['PAY_METHOD_DETAIL']}</span>
					<br>
HTML;
    }

    if ($data['NOTIFY_URL'] != '') {
        echo <<<HTML
					<label for="NOTIFY_URL">Notify Url</label>
					<span id="NOTIFY_URL">{$data['NOTIFY_URL']}</span>
					<br>
HTML;
    }

    if ($data['USER1'] != '') {
        echo <<<HTML
					<label for="USER1">User Field 1</label>
					<span id="USER1">{$data['USER1']}</span>
					<br>
HTML;
    }

    if ($data['USER2'] != '') {
        echo <<<HTML
					<label for="USER2">User Field 2</label>
					<span id="USER2">{$data['USER2']}</span>
					<br>
HTML;
    }

    if ($data['USER3'] != '') {
        echo <<<HTML
					<label for="USER3">User Field 3</label>
					<span id="USER3">{$data['USER3']}</span>
					<br>
HTML;
    }

    if ($data['VAULT'] != '') {
        echo <<<HTML
					<label for="VAULT">Vault</label>
					<span id="VAULT">{$data['VAULT']}</span>
					<br>
HTML;
    }

    if ($data['VAULT_ID'] != '') {
        echo <<<HTML
					<label for="VAULT_ID">Vault ID</label>
					<span id="VAULT_ID">{$data['VAULT_ID']}</span>
					<br>
HTML;
    } ?>
    <label for="encryption_key">Encryption Key</label>
    <span id="encryption_key"><?php
        echo $encryption_key; ?></span>
    <br>
    <?php
    if (isset($PayWeb3->processRequest) || isset($PayWeb3->lastError)) {
        /*
         * We have received a response from PayWeb3
         */

        /*
         * TextArea for display example purposes only.
         */
        ?>
        <label style="vertical-align: top;" for="response">RESPONSE</label>
        <textarea class="form-control" rows="5" cols="100" id="response"><?php
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
        <br>
        <input class="btn btn-success btn-block" type="submit" name="btnSubmit" value="Redirect"/>
        <br>
    <?php
    } ?>
</form>
</body>
</html>
