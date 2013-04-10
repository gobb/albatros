<?php
namespace ARIPD\AdminBundle\Service;
use Monolog\Handler\StreamHandler;

use Monolog\Logger;

use ARIPD\AdminBundle\Entity\Iso4217;

use ARIPD\AdminBundle\Entity\Fxrate;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class FxrateService {
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container,
			EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}

	public function test() {
		return $this->retrieveToday();
	}
	
	/**
	 * http://www.tcmb.gov.tr/kurlar/today.xml
	 * Gets the latest currency conversion rates from TCMB
	 * and updates iso4217 table in the database
	 * 
	 * @return boolean
	 */
	private function retrieveToday() {
		$url = 'http://www.tcmb.gov.tr/kurlar/today.xml';
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
	
		if ( $xml = simplexml_load_string($output) ) {
			foreach ( $this->getIso4217s() as $iso4217 ) {
				if ($iso4217->getCode() == 'TRL') {
					$rate = 1;
				}
				else {
					$buy = $xml->xpath(sprintf('/Tarih_Date/Currency[@CurrencyCode="%s"]', $iso4217->getCode()));
					$rate = floatval($buy[0]->BanknoteBuying);
					
					//if ($rate != $iso4217->getRate()) {
						$date = new \DateTime();
						$message = sprintf('Fxrate updated on %s. New value for %s is %s', $date->format('Y-m-d H:i:s'), $iso4217->getCode(), $rate);
						//$logger = $this->get('logger');
						//$logger->info($message);
						$log = new Logger('fxrate');
						$log->pushHandler(new StreamHandler(__DIR__ . '/../../../../app/logs/fxrate.log', Logger::INFO));
						$log->addInfo($message);
					//}
				}
				$iso4217->setRate($rate);
				$this->em->persist($iso4217);
			}
			$this->em->flush();
				
			return true;
		}
	
		return false;
	
	}
	
	private function getLastRecord() {
		return $this->em->createQueryBuilder()
				->select('f')
				->from('ARIPD\AdminBundle\Entity\Fxrate', 'f')
				->orderBy('f.date', 'DESC')
				->getQuery()->getFirstResult();
		;
	}

	
	private function isExist(\DateTime $startDate) {
		return $this->em->getRepository('ARIPDAdminBundle:Fxrate')->findOneByDate($startDate);
	}

	private function getIso4217s() {
		return $this->em->getRepository('ARIPDAdminBundle:Iso4217')->findAll();
	}
	
	var $currencies = array(
		"USD"=>"US Dollar",
		"CAD"=>"Canadian Dollar",
		"DKK"=>"DKK",
		"SEK"=>"SEK",
		"CHF"=>"CHF",
		"NOK"=>"NOK",
		"JPY"=>"JPY",
		"SAR"=>"SAR",
		"KWD"=>"KWD",
		"AUD"=>"Australian Dollar",
		"EUR"=>"Euro",
		"GBP"=>"GBP"
	);
	
	private function populateWithoutCURL() {
		$lastRecord = $this->getLastRecord();
		$startDate = ($lastRecord==null)?new \DateTime('2002-06-18'):$lastRecord->getDate();
		$endDate = new \DateTime();
		$url[] = array();
		
		while ( $startDate <= $endDate ) {
			if ( false == $this->isExist($startDate) ) {
				$url = 'http://www.tcmb.gov.tr/kurlar/'.$startDate->format('Ym').'/'.$startDate->format('dmY').'.xml';
				$urla[] = $url;
				if ($xmlstr = @file_get_contents($url)) {
					$Root = new \SimpleXMLElement($xmlstr);
					foreach ($Root->Currency as $curr) {
						if (in_array($curr['CurrencyCode'], array_keys($this->currencies))) {
							$entity = new Fxrate();
							$entity->setDate($startDate);
							$entity->setCode($curr['CurrencyCode']);
							$entity->setForexBuying($curr->ForexBuying);
							$entity->setForexSelling($curr->ForexSelling);
							$entity->setBanknoteBuying($curr->BanknoteBuying);
							$entity->setBanknoteSelling($curr->BanknoteSelling);
							$this->em->persist($entity);
						}
					}
				}
			}			
			$startDate = $startDate->modify('+1 day');//add(new \DateInterval('P1D'));
		}
		$this->em->flush();
		
		return array(
				'startDate'=>$startDate,
				'endDate'=>$endDate,
				'url'=>$urla,
		);
	}
	
	private function populateWithCURL() {
		$lastRecord = $this->getLastRecord();
		$startDate = ($lastRecord==null)?new \DateTime('2002-06-18'):$lastRecord->getDate();
		$endDate = new \DateTime();
		$url[] = array();
		
		while ( $startDate <= $endDate ) {
			if ( false == $this->isExist($startDate) ) {
				$url = 'http://www.tcmb.gov.tr/kurlar/'.$startDate->format('Ym').'/'.$startDate->format('dmY').'.xml';
				$urla[] = $url;
				
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($curl);
				curl_close($curl);
				
				$xml = @simplexml_load_string($output);
				$date = $startDate->format('m/d/Y');
				$nodes = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]', $date));
				$currencies = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]/Currency', $date));
				foreach ($currencies as $currency) {
					if (in_array($currency['CurrencyCode'], array_keys($this->currencies))) {
						$entity = new Fxrate();
						$entity->setDate($startDate);
						$entity->setCode($curr['CurrencyCode']);
						$entity->setForexBuying($curr->ForexBuying);
						$entity->setForexSelling($curr->ForexSelling);
						$entity->setBanknoteBuying($curr->BanknoteBuying);
						$entity->setBanknoteSelling($curr->BanknoteSelling);
						$this->em->persist($entity);
					}
				}
			}
			$startDate = $startDate->modify('+1 day');//add(new \DateInterval('P1D'));
		}
		$this->em->flush();
		
		return array(
				'startDate'=>$startDate,
				'endDate'=>$endDate,
				'url'=>$urla,
		);
	}
		
	private function retrieveRates(\DateTime $date = null) {
		$date = ($date==null)?new \DateTime():$date;
		
		switch ($date->format('N')) {
			case 1:
				$date = $date->modify('-3 day');
				break;
			case 5:
				$date = $date->modify('-1 day');
				break;
			case 6:
				$date = $date->modify('-1 day');
				break;
			case 7:
				$date = $date->modify('-2 day');
				break;
		}
		
		$url = 'http://www.tcmb.gov.tr/kurlar/'.$date->format('Ym').'/'.$date->format('dmY').'.xml';
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		
		if ( $xml = simplexml_load_string($output) ) {
			//$nodes = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]', $date->format('m/d/Y')));
			//$currencies = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]/Currency', $date->format('m/d/Y')));
			//$usd = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]/Currency[@CurrencyCode="%s"]/%s', $date->format('m/d/Y'), "USD", "ForexSelling"));
			
			//$currencies = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]/Currency', $date->format('m/d/Y')));
			
			foreach ( $this->getIso4217s() as $iso4217 ) {
				$buy  = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]/Currency[@CurrencyCode="%s"]/%s', $date->format('m/d/Y'), $iso4217->getCode(), "BanknoteBuying"));
				$sell = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]/Currency[@CurrencyCode="%s"]/%s', $date->format('m/d/Y'), $iso4217->getCode(), "BanknoteSelling"));
				
				$entity = new Fxrate();
				$entity->setDate($date);
				$entity->setIso4217($iso4217);
				$entity->setBanknoteBuying(1);
				$entity->setBanknoteSelling(1);
				$entity->setForexBuying(1);
				$entity->setForexSelling(1);
				$this->em->persist($entity);
				
			}
			$this->em->flush();
			
		}
		
		return $buy;
		
	}
	
	public function getFromCentralBank($startDate=null)
	{
	
		$startDate = ($startDate==null)?strtotime(date("Y-m-d")):$startDate;
	
		$day_of_the_week = date('N', $startDate);
		if ($day_of_the_week == 5) {
			$startDate = strtotime("-1 day", $startDate);
		}
		elseif ($day_of_the_week == 6) {
			$startDate = strtotime("-1 day", $startDate);
		}
		elseif ($day_of_the_week == 7) {
			$startDate = strtotime("-2 day", $startDate);
		}
		//echo date("Y-m-d H:i:s", $startDate);exit;
	
		$d = date("d", $startDate);
		$m = date("m", $startDate);
		$Y = date("Y", $startDate);
	
		$url = 'http://www.tcmb.gov.tr/kurlar/'.$Y.$m.'/'.$d.$m.$Y.'.xml';
		echo $url;
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		$xml = @simplexml_load_string($output);
		if (!$xml) {
			$d = $d-1;
			$yesterdayTimestamp = mktime(0, 0, 0, $m, $d, $Y);
			$yesterdayDate = date("Y-m-d", $yesterdayTimestamp);
			//echo 'Error while parsing the document. Try to parse the date ' . $yesterdayDate;
			$this->getFromCentralBank($yesterdayTimestamp);
		}
		//exit;
	
		$date = "$m/$d/$Y";
		//echo $date;exit;
		$nodes = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]', $date));
		if (!empty($nodes)) {
			//printf('At least one building named "%s" found', $date);
			$currencies = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]/Currency', $date));
			foreach ($currencies as $currency) {
				if (in_array($currency['CurrencyCode'], array_keys($this->currencies))) {
					echo $currency['CurrencyCode'].$currency->BanknoteSelling.PHP_EOL;
					/*
						$this->update(
								"currency",
								array(
										"currencyConversionRate"=>$currency->BanknoteSelling
								),
								"currencyCode = :currencyCode",
								array(
										"currencyCode"=>$currency['CurrencyCode']
								)
						);
					*/
				}
			}
			return true;
		} else {
			printf('No xml info found for the date "%s"', $date);
		}
		return false;
	}
	
	public function getFxrateData()
	{
		$this->populateWithoutCURL();
		
		$currency = in_array($_GET["currency"], array_keys($this->currencies)) ? $_GET["currency"] : "USD";
		
		$data["currency"]["options"] = $this->currencies;
		$data["currency"]["selected"] = $currency;
		
		$data["start"] = ($_GET["start"]=="") ? date("Y-m-d", strtotime("-1 month")) : $_GET["start"];
		//$data["end"] = ($_GET["end"]=="") ? date("Y-m-d", strtotime("-1 day")) : $_GET["end"];
		$data["end"] = ($_GET["end"]=="") ? date("Y-m-d", strtotime("now")) : $_GET["end"];
		
		$data["chart1"] = $this->graph1($currency, $data["start"], $data["end"]);
		
		//print_r($data);exit;
		return $data;
	}
	
	
	public function graph1($currency="", $start="", $end="")
	{
		/*
		 * TODO: DAYOFWEEK işlevi kulanılarak (Cumartesi=7 ve Pazar=1) günlerinin bunların listelenmesi engellenecek
		 * EXTRACT işlevi kullanılarak bizim tatil günlerinin olduğu bir dizi yaratılarak bunların listelenmesi engellenecek
		 * 6 sıfır atıldığı tarihten önceki değerler için 1000000 değerine bölünecek
		 */
		$sql = "SELECT ";
		$sql .= "UNIX_TIMESTAMP(fxrateDate) as fxt, ";
		if ($currency=="") {
			$sql .= "CASE WHEN `BanknoteSelling`>10000 THEN `BanknoteSelling`/1000000 ELSE `BanknoteSelling` END AS `forex` ";
		}
		else {
			$sql .= "CASE WHEN `BanknoteSelling`>10000 THEN `BanknoteSelling`/1000000 ELSE `BanknoteSelling` END AS `forex` ";
		}
		$sql .= "FROM ".$this->sTable." ";
		$sql .= "WHERE `currencyCode` = '$currency' ";
		if ($start=="" && $end=="") {
			$sql .= "AND `fxrateDate` BETWEEN DATE_ADD(NOW(), INTERVAL -1 MONTH) AND NOW() ";
		}
		else {
			$sql .= "AND `fxrateDate` BETWEEN '$start' AND '$end' ";
		}
		$sql .= "ORDER BY ";
		$sql .= "fxt ASC ";
		//echo $sql;exit;
		
		$rows = $this->run($sql);
		$total = count($rows);
		
		if ($total > 0)
		{
			$i=0;
			foreach ($rows as $row) {
				$data[$i]["x"] = intval($row["fxt"]);
				$x_val[] = intval($row["fxt"]);
				$data[$i]["y"] = floatval($row["forex"]);
				$y_val[] = floatval($row["forex"]);
				$i++;
			}
		}
		else {
			$data = array();
		}

		$x_min = min($x_val);
		$x_max = max($x_val);
		$x_steps = 60*60*24*7*4;

		$y_min = min($y_val);
		$y_max = max($y_val);

		$data = json_encode($data);

		$json = <<<EOT
{
  "elements": [
    {
      "type": "scatter_line",
      "colour": "#DB1750",
      "width": 3,
      "dot-style": {
        "type": "hollow-dot",
        "dot-size": 3,
        "halo-size": 2,
        "tip": "#date:d M Y#<br>Value: #val#"
      },
      "values": $data
  }
  ],
  "title": {
    "text": "Exchange Rates History, ($start - $end)",
    "style": "{font-size: 16px; color:#C40000; font-family: Calibri; text-align: center;}"
  },
  "menu":{
  	"colour":"#ffffff",
  	"outline_colour":"#707070",
  	"values":[{"type":"text","text":"Save image","javascript-function":"saveAsImage"},{"type":"text","text":"Pick a date range","javascript-function":"datepicker"}]
  },
  "bg_colour": "#FFFFFF",
  "x_axis": {
    "min": $x_min,
    "max": $x_max,
    "steps": $x_steps,
    "labels": {
      "text": "#date:l jS, M Y#",
      "steps": $x_steps,
      "visible-steps": 2,
      "rotate": 90
    }
  },
  "y_axis": {
    "min": $y_min,
    "max": $y_max,
    "steps": 0.01
  },
  "num_decimals": 4
}
EOT;

		return $json;
	}
	
	
}