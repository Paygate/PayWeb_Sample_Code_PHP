<?php
/*
 * Copyright (c) 2021 PayGate (Pty) Ltd
 *
 * Author: App Inlet (Pty) Ltd
 *
 * Released under the GNU General Public License
 */

include_once('../lib/php/global.inc.php');

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

// Retrieve from config in production code
$paygateId     = PAYGATE_ID; // for demo only
$paygateSecret = PAYGATE_SECRET; // for demo only

/*
 * Include the helper PayWeb 3 class
 */
require_once('paygate.payweb3.php');

$data = array(
    'PAYGATE_ID'     => '',
    'PAY_REQUEST_ID' => '',
    'REFERENCE'      => '',
);

$encryption_key = $paygateSecret;

if (isset($_POST['btnSubmit'])) {
    /*
     * Create array of data to query PAyGate with
     */
    $data = array(
        'PAYGATE_ID'     => $paygateId,
        'PAY_REQUEST_ID' => $_POST['PAY_REQUEST_ID'],
        'REFERENCE'      => $_POST['REFERENCE']
    );

    /*
     * Initiate the PayWeb 3 helper class
     */
    $PayWeb3 = new PayGate_PayWeb3();
    /*
     * Set the encryption key of your PayGate PayWeb3 configuration
     */
    $PayWeb3->setEncryptionKey($encryption_key);
    /*
     * Set the array of fields to be posted to PayGate
     */
    $PayWeb3->setQueryRequest($data);
    /*
     * Do the curl post to PayGate
     */
    $returnData = $PayWeb3->doQuery();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <title>PayWeb 3 - Query</title>
    <style type="text/css">
        label {
            margin-top: 5px;
            display: inline-block;
            width: 200px;
        }
    </style>
</head>
<body>
<a href="/<?php
echo $root; ?>/query.php">back to Query</a>
<form action="simple_query.php" method="post">
    <label for="PAYGATE_ID" class="col-sm-3 control-label">PayGate ID</label>
    <input type="text" name="PAYGATE_ID" id="PAYGATE_ID" class="form-control" value="<?php
    echo $data['PAYGATE_ID'] != '' ? $data['PAYGATE_ID'] : '10011072130'; ?>"/>
    <br>
    <label for="PAY_REQUEST_ID" class="col-sm-3 control-label">Pay Request ID</label>
    <input type="text" name="PAY_REQUEST_ID" id="PAY_REQUEST_ID" class="form-control" value="<?php
    echo $data['PAY_REQUEST_ID'] != '' ? $data['PAY_REQUEST_ID'] : ''; ?>"/>
    <br>
    <label for="REFERENCE" class="col-sm-3 control-label">Reference</label>
    <input type="text" name="REFERENCE" id="REFERENCE" class="form-control" value="<?php
    echo $data['REFERENCE'] != '' ? $data['REFERENCE'] : ''; ?>"/>
    <br>
    <label for="encryption_key" class="col-sm-3 control-label">Encryption Key</label>
    <input type="text" name="encryption_key" id="encryption_key" class="form-control" value="<?php
    echo $encryption_key != '' ? $encryption_key : 'secret'; ?>"/>
    <br>
    <br>
    <input type="submit" id="doQueryBtn" class="btn btn-success btn-block" value="Do Query" name="btnSubmit">
    <br>
</form>
<?php
if (isset($PayWeb3->queryResponse) || isset($PayWeb3->lastError)) {
    echo '<label for="response">RESPONSE: </label><br>';
    /*
     * We have received a response from PayWeb3
     */
    if ( ! isset($PayWeb3->lastError)) {
        /*
         * It is not an error, so continue
         */
        echo <<<HTML
				<textarea name="response" id="response" rows="20" cols="100">
HTML;
        foreach ($PayWeb3->queryResponse as $key => $value) {
            /*
             * Loop through the key / value pairs returned
             */

            echo <<<HTML
{$key}={$value}

HTML;
        }
        echo <<<HTML
				</textarea>
HTML;
    } elseif (isset($PayWeb3->lastError)) {
        /*
         * otherwise handle the error response
         */
        echo 'ERROR: ' . $PayWeb3->lastError;
    }
} ?>
</body>
</html>
