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
 * Include the helper PayWeb 3 class
 */
require_once('paygate.payweb3.php');

$data = array(
    'PAYGATE_ID'     => '',
    'PAY_REQUEST_ID' => '',
    'REFERENCE'      => '',
);

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

if (isset($_POST['btnSubmit'])) {
    /*
     * Create array of data to query PAyGate with
     */
    $data = array(
        'PAYGATE_ID'     => $paygateId,
        'PAY_REQUEST_ID' => $_POST['PAY_REQUEST_ID'],
        'REFERENCE'      => $_POST['REFERENCE']
    );

    $encryption_key = $paygateSecret;

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
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>PayWeb 3 - Query</title>
    <link rel="stylesheet" href="../lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="../lib/css/paygate.css">
</head>
<body>
<div class="container container-sm">
    <div class="z-top">Step: Query</div>
    <div class="z-nav login">
        <ul class="nav nav-tabs flex-nowrap" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="result-tab" href="#query" role="tab" data-toggle="tab">
                    Query</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="initiate-tab" href="/<?php
                echo $root; ?>/index.php" role="tab">Initiate</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="query-tab" href="/<?php
                echo $root; ?>/query.php" role="tab">New Query</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="simple-query-tab" href="/<?php
                echo $root; ?>/simple_query.php" role="tab">Simple Query</a>
            </li>
        </ul>
    </div>
    <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="query" role="tab-panel">
            <form class="form" id="loginForm" name="query_paygate_form" action="<?php
            echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="z-form-group-wrapper">
                    <div class="form-group z-formgroup">
                        <label for="PAYGATE_ID">PayGate ID</label>
                        <input type="text" name="PAYGATE_ID" id="PAYGATE_ID" class="form-control" value="<?php
                        echo $data['PAYGATE_ID'] != '' ? $data['PAYGATE_ID'] : '10011072130'; ?>"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="PAY_REQUEST_ID">Pay Request ID</label>
                        <input type="text" name="PAY_REQUEST_ID" id="PAY_REQUEST_ID" class="form-control" value="<?php
                        echo $data['PAY_REQUEST_ID'] != '' ? $data['PAY_REQUEST_ID'] : ''; ?>"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="REFERENCE">Reference</label>
                        <input type="text" name="REFERENCE" id="REFERENCE" class="form-control" value="<?php
                        echo $data['REFERENCE'] != '' ? $data['REFERENCE'] : ''; ?>"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="encryption_key">Encryption Key</label>
                        <input type="text" name="encryption_key" id="encryption_key" class="form-control" value="<?php
                        echo $paygateSecret != '' ? $paygateSecret : 'secret'; ?>"/>
                    </div>
                </div>
                <div class="z-form-group-wrapper">
                    <?php
                    if (isset($PayWeb3->queryResponse) || isset($PayWeb3->lastError)) {
                        /*
                         * We have received a response from PayWeb3
                         */
                        ?>
                        <div class="well" style="padding: 5px; box-shadow: 0px 0px 6px 3px #eee;">
                            <h4 class="d-inline-block p-2">Query Result</h4>
                            <?php
                            if ( ! isset($PayWeb3->lastError)) {
                                /*
                                 * It is not an error, so continue
                                 */
                                foreach ($PayWeb3->queryResponse as $key => $value) {
                                    /*
                                     * Loop through the key / value pairs returned
                                     */

                                    echo <<<HTML
								<div class="form-group z-formgroup d-flex">
									<label for="{$key}_RESPONSE">{$key}</label>
										<p class="ml-auto" id="{$key}_RESPONSE">{$value}</p>
								</div>
HTML;
                                }
                            } elseif (isset($PayWeb3->lastError)) {
                                /*
                                 * otherwise handle the error response
                                 */
                                echo $PayWeb3->lastError;
                            } ?>
                        </div>
                        <?php
                    } ?>
                </div>
                <div class="z-buttons">
                    <input type="submit" id="doQueryBtn" class="btn btn-success" value="Do Query"
                           name="btnSubmit">
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
