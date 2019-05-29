<?php
require_once __DIR__ . '/../src/init.php';

$amount = $_REQUEST['amount'] ? $_REQUEST['amount'] * 100 : false;
$invoice = $_REQUEST['invoice'];
$full_name = $_REQUEST['full_name'];
$email = $_REQUEST['emailTxt'];

if ($amount && $invoice && $email) {
	$transaction = $paystation->createTransaction($amount, $invoice, $email, $full_name); // Replace 'sample_checkout_transaction' with your own merchant reference.
}
else {
	$transaction = new \Paystation\Transaction();
	$transaction->transactionId = -1;
	$transaction->hasError = true;
	$transaction->errorMessage = "No amount / email / invoice specified.";
}
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Immigration Trust Payment</title>
	<link rel="stylesheet" type="text/css" href="css/paystation.css?1">
</head>
<body>
<div class="content">
	<div class="box">
		<br />
		<div id="payment_wrapper" class="payment-wrapper">
			<?= $transaction->hasError ? "<h1 style=\"color:red\">$transaction->errorMessage</h1>" : "<iframe class=\"paystation-payment-frame\" src=\"$transaction->digitalOrderUrl\"></iframe>" ?>
		</div>
	</div>
</div>
<script src="js/paystation.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
  const _paymentFrameWrapper = document.getElementById('payment_wrapper');
  const _fullname = '<?= $full_name ?>';
  const _email = '<?= $email ?>';
  const _payment_amount = '<?= $amount ?>';
  const _invoice_number = '<?= $invoice ?>';
	const _paymentFrame = _paymentFrameWrapper.firstElementChild;
	const _transactionId = '<?= $transaction->transactionId ?>';

	// make sure it isn't an error message
	if (_paymentFrame.nodeName === 'IFRAME' && _transactionId) {
		Paystation.pollTransactionDetails(_transactionId, onTransactionResponse);
	}

	// This function will get a response every time we poll the website.
	// Most of these responses will get transaction details for an incomplete transaction while the user is still entering their details in the iframe.
	function onTransactionResponse(err, transaction) {
		if (err) {
			// have some error handling if you want
		}

		// hasError is for all errors regardless if they come from paystation or us, which could happen before the transaction completes.
		// errorCode is a paystation response which is set after a transaction is complete. A negative error code means no error code has been returned.
		if (transaction && (transaction.errorCode > -1 || transaction.hasError)) {
			if (transaction.errorCode == 0) {
        sendConfirmationEmail();
      }
			onTransactionComplete(transaction);
		}
  }
  
  function sendConfirmationEmail() {
    var data = {
        'full_name': _fullname,
        'receive_email': _email,
        'payment_amount': _payment_amount,
        'invoice_number': _invoice_number
    };
    // POST data to the php file
    $.ajax({ 
        url: 'mail.php', 
        data: data,
        type: 'POST',
        success: function (data) {
        }
    });
  }

	// Remove the iframe and stop polling the transaction details. Show a response to the user.
	function onTransactionComplete(transaction) {
		Paystation.closePaymentFrame(_paymentFrame);
		Paystation.stopPolling();

		// Display the outcome to the user i.e. "Transaction successful" or "Insufficient funds"
		// You might want to handle these differently depending on the errorCode (transaction.errorCode)
		_paymentFrameWrapper.innerHTML = '<h1>' + transaction.errorMessage + '</h1>';
	}
</script>
</body>
</html>
