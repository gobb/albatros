<?php
namespace ARIPD\AdminBundle\Util\VPOS;

class Akbank extends VPOSAdapterAbstract {
	
	public function getFields($parameters) {
		$pan = $parameters['pan'];
		$Ecom_Payment_Card_ExpDate_Month = $parameters['Ecom_Payment_Card_ExpDate_Month'];
		$Ecom_Payment_Card_ExpDate_Year = $parameters['Ecom_Payment_Card_ExpDate_Year'];
		$cv2 = $parameters['cv2'];
		
		$amount = $parameters['amount'];
		$taksit = $parameters['taksit'];
		
		$clientid = $parameters['clientid'];
		$oid = $parameters['oid'];
		$okUrl = $parameters['okUrl'];
		$failUrl = $parameters['failUrl'];
		$islemtipi = $parameters['islemtipi'];
		
		$storetype = $parameters['storetype'];
		$storekey = $parameters['storekey'];
		
		$rnd = microtime();
		$hashstr = sprintf("%s%s%s%s%s%s%s%s%s", $clientid, $oid, $amount, $okUrl, $failUrl, $islemtipi, $taksit, $rnd, $storekey);
		$hash = base64_encode(pack('H*',sha1($hashstr)));
		 
		return
				"pan={$pan}".
				"&Ecom_Payment_Card_ExpDate_Month={$Ecom_Payment_Card_ExpDate_Month}".
				"&Ecom_Payment_Card_ExpDate_Year={$Ecom_Payment_Card_ExpDate_Year}".
				"&cv2={$cv2}".
				"&clientid={$clientid}".
				"&amount={$amount}".
				"&oid={$oid}".
				"&okUrl={$okUrl}".
				"&failUrl={$failUrl}".
				"&storetype={$storetype}".
				"&rnd={$rnd}".
				"&hash={$hash}".
				"&islemtipi={$islemtipi}".
				"&taksit={$taksit}"
		;
	}
	
	public function testGetFields() {
		$pan = "5571135571135575"; // 4355084355084358, 5571135571135575
		$Ecom_Payment_Card_ExpDate_Month = "12";
		$Ecom_Payment_Card_ExpDate_Year = "12";
		$cv2 = "000";
		
		$clientid = "100200000";
		$oid = md5(uniqid(rand(), true));
		$amount = 3.6;
		$okUrl		= "https://albatros/app_dev.php/shopping/payment/3dreturn";
		$failUrl	= "https://albatros/app_dev.php/shopping/payment/3dreturn";
		$islemtipi = "Auth";
		$taksit = "";
		
		$storetype = "3d_pay";
		$storekey = "123456";
		
		$rnd = microtime();
		$hashstr = sprintf("%s%s%s%s%s%s%s%s%s", $clientid, $oid, $amount, $okUrl, $failUrl, $islemtipi, $taksit, $rnd, $storekey);
		$hash = base64_encode(pack('H*',sha1($hashstr)));
		 
		return
				"pan={$pan}".
				"&Ecom_Payment_Card_ExpDate_Month={$Ecom_Payment_Card_ExpDate_Month}".
				"&Ecom_Payment_Card_ExpDate_Year={$Ecom_Payment_Card_ExpDate_Year}".
				"&cv2={$cv2}".
				"&clientid={$clientid}".
				"&amount={$amount}".
				"&oid={$oid}".
				"&okUrl={$okUrl}".
				"&failUrl={$failUrl}".
				"&storetype={$storetype}".
				"&rnd={$rnd}".
				"&hash={$hash}".
				"&islemtipi={$islemtipi}".
				"&taksit={$taksit}"
		;
	}
	
}
