<?php
/*
 * Copyright (c) 2021 PayGate (Pty) Ltd
 *
 * Author: App Inlet (Pty) Ltd
 *
 * Released under the GNU General Public License
 */

/*
 * This is an example page of the form fields required for a PayGate PayWeb 3 transaction.
 */

include_once('../lib/php/global.inc.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>PayWeb 3 - Initiate</title>
    <link rel="stylesheet" href="../lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="../lib/css/paygate.css">
</head>
<body>
<div class="container container-sm">
    <div class="z-top">Step: Initiate</div>
    <div class="z-nav login">
        <ul class="nav nav-tabs flex-nowrap" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="initiate-tab" href="#initiate" role="tab" data-toggle="tab">
                    Initiate</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="query-tab" href="/<?php
                echo $root; ?>/query.php" role="tab">Query</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="simple-initiate-tab" href="/<?php
                echo $root; ?>/simple_initiate.php" >
                    Simple Initiate</a>
            </li>
        </ul>
    </div>
    <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="initiate" role="tab-panel">
            <form class="form" id="loginForm" action="request.php" name="paygate_initiate_form" method="post">
                <div class="z-form-group-wrapper">
                    <div class="form-group z-formgroup">
                        <label for="PAYGATE_ID">PayGate ID</label>
                        <input type="text" name="PAYGATE_ID" id="PAYGATE_ID" class="form-control"
                               value="10011072130"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="REFERENCE">Reference</label>
                        <input type="text" name="REFERENCE" id="REFERENCE" class="form-control" value="<?php
                        echo generateReference(); ?>"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="AMOUNT">Amount</label>
                        <input type="text" name="AMOUNT" id="AMOUNT" class="form-control" value="100"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="CURRENCY">Currency</label>
                        <input type="text" name="CURRENCY" id="CURRENCY" class="form-control" value="ZAR"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="RETURN_URL">Return URL</label>
                        <input type="text" name="RETURN_URL" id="RETURN_URL" class="form-control" value="<?php
                        echo $fullPath['protocol'] . $fullPath['host'] . '/' . $root . '/result.php'; ?>"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="TRANSACTION_DATE">Transaction Date</label>
                        <input type="text" name="TRANSACTION_DATE" id="TRANSACTION_DATE" class="form-control"
                               value="<?php
                               echo getDateTime('Y-m-d H:i:s'); ?>"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="LOCALE">Locale</label>
                        <input type="text" name="LOCALE" id="LOCALE" class="form-control" value="en-za"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="COUNTRY">Country</label>
                        <select name="COUNTRY" id="COUNTRY" class="form-control">
                            <?php
                            echo generateCountrySelectOptions(); ?>
                        </select>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="EMAIL">Customer Email</label>
                        <input type="text" name="EMAIL" id="EMAIL" class="form-control" value="support@paygate.co.za"/>
                    </div>
                    <div class="form-group z-formgroup">
                        <label for="encryption_key">Encryption Key</label>
                        <input type="text" name="encryption_key" id="encryption_key" class="form-control"
                               value="secret"/>
                    </div>
                    <div class="form-group">
                        <div class="z-buttons">
                            <button type="button" class="btn btn-primary" data-toggle="collapse"
                                    data-target="#extraFieldsDiv" aria-expanded="false" aria-controls="extraFieldsDiv">
                                Extra Fields
                            </button>
                        </div>
                    </div>
                    <div id="extraFieldsDiv" class="collapse well well-sm">
                        <div class="form-group z-formgroup">
                            <label for="PAY_METHOD">Pay Method</label>
                            <input type="text" name="PAY_METHOD" id="PAY_METHOD" class="form-control"
                                   placeholder="optional"/>
                        </div>
                        <div class="form-group z-formgroup">
                            <label for="PAY_METHOD_DETAIL">Pay Method Detail</label>
                            <input type="text" name="PAY_METHOD_DETAIL" id="PAY_METHOD_DETAIL" class="form-control"
                                   placeholder="optional"/>
                        </div>
                        <div class="form-group z-formgroup">
                            <label for="NOTIFY_URL">Notify URL</label>
                            <input type="text" name="NOTIFY_URL" id="NOTIFY_URL" class="form-control"
                                   placeholder="optional"/>
                        </div>
                        <div class="form-group z-formgroup">
                            <label for="USER1">User Field 1</label>
                            <input type="text" name="USER1" id="USER1" class="form-control" placeholder="optional"/>
                        </div>
                        <div class="form-group z-formgroup">
                            <label for="USER2">User Field 2</label>
                            <input type="text" name="USER2" id="USER2" class="form-control" placeholder="optional"/>
                        </div>
                        <div class="form-group z-formgroup">
                            <label for="USER3">User Field 3</label>
                            <input type="text" name="USER3" id="USER3" class="form-control" placeholder="optional"/>
                        </div>
                        <div class="form-group z-formgroup">
                            <label for="VAULT">Vault</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="VAULT" id="VAULTOFF" value="" checked>
                                    No card Vaulting
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="VAULT" id="VAULTNO" value="0">
                                    Don't Vault card
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="VAULT" id="VAULTYES" value="1">
                                    Vault card
                                </label>
                            </div>
                        </div>
                        <div class="form-group z-formgroup">
                            <label for="VAULT_ID">Vault ID</label>
                            <input type="text" name="VAULT_ID" id="VAULT_ID" class="form-control"
                                   placeholder="optional"/>
                        </div>
                    </div>
                </div>

                <div class="z-buttons">
                    <input type="submit" name="btnSubmit" class="btn btn-success"
                           value="Calculate Checksum"/>
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
