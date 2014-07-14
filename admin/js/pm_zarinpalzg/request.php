<?php

	include_once('lib/nusoap.php');
	
	$merchantID = $_POST['TransactionAccountID'];
	
	$amount = $_POST['TransactionAmount'];
	
	 $Description = $_POST['TransactionDesc'];
	$callBackUrl = $_POST['TransactionRedirectUrl'];

			$client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl'); 
	$client->soap_defencoding = 'UTF-8';
	$Email = $_POST['TransactionEmail']; // Optional
	$Mobile =$_POST['TransactionMobail']; // Optional

	$result = $client->call('PaymentRequest', array(
													array(
															'MerchantID' 	=> $merchantID,
															'Amount' 		=> $amount,
															'Description' 	=> $Description,
															'Email' 		=> $Email,
															'Mobile' 		=> $Mobile,
															'CallbackURL' 	=> $callBackUrl
														)
													)
	);
	
	//Redirect to URL You can do it also by creating a form
	if($result['Status'] == 100)
	{
		Header('Location: https://www.zarinpal.com/pg/StartPay/'.$result['Authority'] .'/ZarinGate');
	} else {
		echo'ERR: '.$result['Status'];
	}
?>





?>