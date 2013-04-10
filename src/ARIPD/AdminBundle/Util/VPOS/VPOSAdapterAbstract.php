<?php
namespace ARIPD\AdminBundle\Util\VPOS;
class VPOSAdapterAbstract {

	public function postData($url, $fields) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		$response = curl_exec($ch);
		
		
		//var_dump($response);exit;
		//echo(HtmlEntities($response));exit;
		
		
		if (curl_errno($ch)) {
			$message = curl_error($ch);
		} else {
			curl_close($ch);
			$message = "";
		}

		return array("succeed" => (bool) !$message, "message" => $message,
				"response" => $response);
	}

}
