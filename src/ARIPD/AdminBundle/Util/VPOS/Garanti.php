<?php
namespace ARIPD\AdminBundle\Util\VPOS;

class Garanti {
	public $OOS_TDS_SERVICE_URL;
	public $XML_SERVICE_URL;

	public $secure3dsecuritylevel = '3D_FULL';//3D_PAY, 3D_FULL, 3D_HALF
	public $cardnumber;
	public $cardexpiredatemonth;
	public $cardexpiredateyear;
	public $cardcvv2;

	public $mode = 'PROD';//PROD, TEST
	public $apiversion = 'v0.01';
	public $terminalprovuserid = 'PROVAUT';
	public $terminaluserid = 'PROVAUT';////Oluşturulan yeni kullanıcı adı
	public $terminalmerchantid;//Banka tarafından gönderilen işyeri no
	public $txntype = 'sales';
	public $txnamount;//$odenecek_tutar; //İşlem Tutarı 1.00 TL için 100 yazılacak.
	public $txncurrencycode = '949';
	public $txninstallmentcount = '';//Taksit Sayısı. Boş gönderdildiği takdirde nakit olarak tahsil edilecektir.
	public $orderid;//Sipariş Numarası
	public $groupid;//Grup Numarası normal satış:1 kampanyalı satış:2
	private $terminalid;//Garanti Bankası tarafından oluşturulan kullanıcının ID bilgisi
	private $terminalid_;//Toplam 9 karakterden oluşacak başında 0 bulunacak kullanıcı ID bilgisi
	public $successurl;//Onaylandığı takdirde web sitenizde ziyaret edeceği sayfa
	public $errorurl;//Onaylanmadığı takdirde web sitenizde ziyaret edeceği sayfa
	public $customeripaddress;
	public $customeremailaddress;//Müşteri E-Posta Adresi

	public $storeKey;
	public $provisionPassword;

	public function __construct() {
	}

	public function init_curl($request) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->OOS_TDS_SERVICE_URL);
		curl_setopt($ch, CURLOPT_POST, 1);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			print curl_error($ch);
		} else {
			curl_close($ch);
		}
		return $result;
	}

	public function tds_data() {
		return "secure3dsecuritylevel=" . $this->secure3dsecuritylevel . "&"
				. "cardnumber=" . $this->cardnumber . "&"
				. "cardexpiredatemonth=" . $this->cardexpiredatemonth . "&"
				. "cardexpiredateyear=" . $this->cardexpiredateyear . "&"
				. "cardcvv2=" . $this->cardcvv2 . "&" . "mode=" . $this->mode
				. "&" . "apiversion=" . $this->apiversion . "&"
				. "terminalprovuserid=" . $this->terminalprovuserid . "&"
				. "terminaluserid=" . $this->terminaluserid . "&"
				. "terminalmerchantid=" . $this->terminalmerchantid . "&"
				. "txntype=" . $this->txntype . "&" . "txnamount="
				. $this->txnamount . "&" . "txncurrencycode="
				. $this->txncurrencycode . "&" . "txninstallmentcount="
				. $this->txninstallmentcount . "&" . "orderid="
				. $this->orderid . "&" . "terminalid=" . $this->terminalid
				. "&" . "successurl=" . $this->successurl . "&" . "errorurl="
				. $this->errorurl . "&" . "customeripaddress="
				. $this->customeripaddress . "&" . "customeremailaddress="
				. $this->customeremailaddress . "&" . "secure3dhash="
				. $this->getSecure3dhash();
	}

	public function setTerminalid($terminalid) {
		$this->terminalid = $terminalid;
		$this->terminalid_ = "0" . $terminalid;
	}

	public function getSecure3dhash() {
		$SecurityData = strtoupper(
				sha1($this->provisionPassword . $this->terminalid_));
		return strtoupper(
				sha1(
						$this->terminalid . $this->orderid . $this->txnamount
								. $this->successurl . $this->errorurl
								. $this->txntype . $this->txninstallmentcount
								. $this->storeKey . $SecurityData));

	}

}
