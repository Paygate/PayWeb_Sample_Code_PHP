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

    $reference = generateReference();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
	<head>
		<title>PayWeb 3 - Initiate</title>
		<style type="text/css">
			label {
				margin-top: 5px;
				display: inline-block;
				width: 150px;
			}
		</style>
	</head>
	<body>
		<a href="/<?php echo $root; ?>/index.php">back to Initiate</a>
		<form action="simple_request.php" method="post" name="paygate_initiate_form">
			<label for="PAYGATE_ID">PayGate ID</label>
			<input type="text" name="PAYGATE_ID" id="PAYGATE_ID" value="10011072130" />
			<br>
			<label for="REFERENCE">Reference</label>
			<input type="text" name="REFERENCE" id="REFERENCE" value="<?php echo $reference; ?>" />
			<br>
			<label for="AMOUNT">Amount</label>
			<input type="text" name="AMOUNT" id="AMOUNT" value="100" />
			<br>
			<label for="CURRENCY">Currency</label>
			<input type="text" name="CURRENCY" id="CURRENCY" value="ZAR" />
			<br>
			<label for="RETURN_URL">Return URL</label>
			<input type="text" name="RETURN_URL" id="RETURN_URL" value="<?php echo $fullPath['protocol'] . $fullPath['host'] . '/' . $root . '/result.php?reference=' . $reference; ?>" />
			<br>
			<label for="TRANSACTION_DATE">Transaction Date</label>
			<input type="text" name="TRANSACTION_DATE" id="TRANSACTION_DATE" value="<?php echo getDateTime('Y-m-d H:i:s'); ?>" />
			<br>
			<label for="LOCALE">Locale</label>
			<input type="text" name="LOCALE" id="LOCALE" value="en-za" />
			<br>
			<label for="COUNTRY">Country</label>
			<select name="COUNTRY" id="COUNTRY">
				<?php echo generateCountrySelectOptions(); ?>
			</select>
			<br>
			<label for="EMAIL">Customer Email</label>
			<input type="text" name="EMAIL" id="EMAIL" value="support@paygate.co.za" />
			<br>
			<label for="encryption_key">Encryption Key</label>
			<input type="text" name="encryption_key" id="encryption_key" value="secret" />
			<br>
			<h4>Extra Fields</h4>
			<label for="PAY_METHOD">Pay Method</label>
			<input type="text" name="PAY_METHOD" id="PAY_METHOD" placeholder="optional" />
			<br>
			<label for="PAY_METHOD_DETAIL">Pay Method Detail</label>
			<input type="text" name="PAY_METHOD_DETAIL" id="PAY_METHOD_DETAIL" placeholder="optional" />
			<br>
			<label for="NOTIFY_URL">Notify URL</label>
			<input type="text" name="NOTIFY_URL" id="NOTIFY_URL" placeholder="optional" />
			<br>
			<label for="USER1">User Field 1</label>
			<input type="text" name="USER1" id="USER1" placeholder="optional" />
			<br>
			<label for="USER2">User Field 2</label>
			<input type="text" name="USER2" id="USER2" placeholder="optional" />
			<br>
			<label for="USER3">User Field 3</label>
			<input type="text" name="USER3" id="USER3" placeholder="optional" />
			<br>
			<h4>Vault</h4>
			<label for="VAULTOFF">No card Vaulting</label>
			<input type="radio" name="VAULT" id="VAULTOFF" value="" checked>
			<br>
			<label for="VAULTNO">Don't Vault card</label>
			<input type="radio" name="VAULT" id="VAULTNO" value="0">
			<br>
			<label for="VAULTYES">Vault card</label>
			<input type="radio" name="VAULT" id="VAULTYES" value="1">
			<br>
			<label for="VAULT_ID">Vault ID</label>
			<input type="text" name="VAULT_ID" id="VAULT_ID" placeholder="optional" />
			<br>
			<br>
			<input type="submit" name="btnSubmit" class="btn btn-success btn-block" value="Calculate Checksum" />
		</form>
		<script type="text/javascript" src="../lib/js/jquery.min.js"></script>
		<script type="text/javascript" src="../lib/js/bootstrap.min.js"></script>
	</body>
</html>
