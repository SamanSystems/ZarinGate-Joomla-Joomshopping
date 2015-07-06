<?php
defined('_JEXEC') or die('Restricted access');

class pm_zarinpalzg extends PaymentRoot{
    
    function showPaymentForm($params, $pmconfigs){
        include(dirname(__FILE__)."/paymentform.php");
    }

	//function call in admin
	function showAdminFormParams($params){
	  $array_params = array('acc', 'transaction_end_status', 'transaction_pending_status', 'transaction_failed_status');
	  foreach ($array_params as $key){
	  	if (!isset($params[$key])) $params[$key] = '';
	  } 
	  $orders = &JModel::getInstance('orders', 'JshoppingModel'); //admin model
      include(dirname(__FILE__)."/adminparamsform.php");	  
	}

	function checkTransaction($pmconfigs, $order, $act){
        $jshopConfig = &JSFactory::getConfig();
		
		require_once('lib/nusoap.php');
	
		$merchantID = $pmconfigs['acc'];
		$amount = round($order->order_total);
		$Authority = $_GET['Authority'];
		
		if($_GET['Status'] == 'OK'){
		// URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl
		$client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl'); 
		$client->soap_defencoding = 'UTF-8';
		$result = $client->call('PaymentVerification', array(
															array(
																	'MerchantID'	 => $merchantID,
																	'Authority' 	 => $Authority,
																	'Amount'	 	 => $amount
																)
															)
		);
		if($result['Status'] == 100){
			echo 'پرداخت شما با موفقیت انجام شد. شناسه پرداخت :'. $result['RefID'];
			$return = 1;
		} else {
			echo 'پرداخت شما شکست خورد:'. $result['Status'];
			$return = 0;

		}

	} else {
		echo 'پرداخت کنسل شد';
		$return = 0;

	}
        
    return array($return, "");    
		
     

	}

	function showEndForm($pmconfigs, $order){
        
        $jshopConfig = &JSFactory::getConfig();        
	    /*$item_name = sprintf(_JSHOP_PAYMENT_PRODUCT_IN_SITE, $jshopConfig->store_name);*/
        $item_name = sprintf(_JSHOP_PAYMENT_NUMBER, $order->order_number);
        $_country = &JTable::getInstance('country', 'jshop');
        $_country->load($order->country);
        $country = $_country->country_code_2;

         $notify_url = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=pm_zarinpalzg&no_lang=1";
        $return = JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=return&orderid=".$order->order_id."&js_paymentclass=pm_zarinpalzg";
        $cancel_return = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_zarinpalzg";
        $merchantID = $pmconfigs['acc'];
        ?>
        <html>
        <body>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />            
        </head>
        <form id="paymentform" action="<?php echo JURI::base();?>components/com_jshopping/payments/pm_zarinpalzg/request.php" name = "paymentform" method = "post">
	    <input id="TransactionAccountID" type='hidden' name='TransactionAccountID' value='<?php print $merchantID; ?>' /><br />
	    <input id="TransactionAmount" type='hidden' name='TransactionAmount' value='<?php print round($order->order_total); ?>' /><br />
	    <input id="TransactionRedirectUrl" type='hidden' name='TransactionRedirectUrl' value='<?php print  $return; ?>'/><br />
	    <input id="TransactionDesc" type='hidden' name='TransactionDesc' value='<?php print $order->order_id; ?>' /><br />
        <input id="TransactionMobail" type='hidden' name='TransactionMobail' value='<?php print $order->mobil_phone; ?>' /><br />
               <input id="TransactionEmail" type='hidden' name='TransactionEmail' value='<?php print $order->d_email; ?>' /><br />

        </form>   
        
        <?php echo "<br>".  $redirectAddress;echo "در حال انتقال به صفحه پرداخت . لطفا کمی صبر نمایید" ?>
        <br>
        <script type="text/javascript">document.getElementById('paymentform').submit();</script>
        </body>
        </html>
        <?php
        die();
	}
    
    function getUrlParams($pmconfigs){                        
        $params = array(); 
        $params['order_id'] = JRequest::getInt("orderid");
        $params['hash'] = "";
        $params['checkHash'] = 0;
        $params['checkReturnParams'] = 1;
    return $params;
    }
    
}
?>
