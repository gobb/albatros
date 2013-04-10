<?php
namespace ARIPD\IntranetBundle\Service;
use ARIPD\IntranetBundle\Entity\TCMB;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TCMBService {
	protected $container;
	protected $em;
	
	public function __construct(ContainerInterface $container, EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
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
	
	public function retrieve($objects) {
		$op = '{
		"cols": [
		{"id":"","label":"Date","pattern":"","type":"date"},
		{"id":"","label":"Stock low","pattern":"","type":"number"},
		{"id":"","label":"Stock open","pattern":"","type":"number"},
		{"id":"","label":"Stock close","pattern":"","type":"number"},
		{"id":"","label":"Stock high","pattern":"","type":"number"}
		],
		"rows": [';
			
		foreach ($objects as $object) {
			$date = $object->getDate();
			$date = 'Date('.$date->format('Y').', '.($date->format('m')-1).', '.$date->format('d').')';
			$op .= '{"c":[{"v":"'.$date.'","f":null},{"v":'.$object->getForexBuying().',"f":null},{"v":'.$object->getForexBuying().',"f":null},{"v":'.$object->getForexBuying().',"f":null},{"v":'.$object->getForexBuying().',"f":null}]},';
		}
			
		$op .= ']}';
		return $op;
	}
	
	public function getFromCentralBank($startDate=null)
	{
		if ($startDate == null) {
			$startDate = new \DateTime();
		}
		
		switch ($startDate->format('N')) {
			case 5:
			case 6:
				$startDate->sub(new \DateInterval('P1D'));
				break;
			case 7:
				$startDate->sub(new \DateInterval('P2D'));
				break;
		}
		
		$url = 'http://www.tcmb.gov.tr/kurlar/'.$startDate->format("Ym").'/'.$startDate->format("dmY").'.xml';
		echo sprintf('Connecting "%s" => ', $url);
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		$xml = @simplexml_load_string($output);
		if (!$xml) {
			echo 'Result NOK' . PHP_EOL;
			$startDate->sub(new \DateInterval('P1D'));
			$this->getFromCentralBank($startDate);
		}
		
		echo 'Result OK' . PHP_EOL;
		
		$nodes = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]', $startDate->format("m/d/Y")));
		if (!empty($nodes)) {
			$currencies = $xml->xpath(sprintf('/Tarih_Date[@Date="%s"]/Currency', $startDate->format("m/d/Y")));
			foreach ($currencies as $currency) {
				if (in_array($currency['CurrencyCode'], array_keys($this->currencies))) {
					$entity = new TCMB();
					$entity->setDate($startDate);
					$entity->setCode($currency['CurrencyCode']);
					$entity->setBanknotebuying($currency->BanknoteBuying);
					$entity->setBanknoteselling($currency->BanknoteSelling);
					$entity->setForexbuying($currency->ForexBuying);
					$entity->setForexselling($currency->ForexSelling);
					$this->em->persist($entity);
				}
				$this->em->flush();
			}
			return true;
		}
		else {
			printf('No xml info found for the date "%s"', $startDate->format("m/d/Y"));
		}
		return false;
	}
	
	public function populate()
	{
		$startDate = $this->getDateStart();
		if ($startDate == null) {
			$startDate = new \DateTime();
			$startDate->setDate(2012, 6, 18);
		}
		$endDate = new \DateTime();
		
		while ( $startDate <= $endDate ) {
			switch ($startDate->format('N')) {
				case 6:
					$startDate->add(new \DateInterval('P2D'));
					break;
				case 7:
					$startDate->add(new \DateInterval('P1D'));
					break;
			}
			
			echo $startDate->format('N l: Y-m-d') . PHP_EOL;
			
			if (!$this->isExist($startDate)) {
				$url = 'http://www.tcmb.gov.tr/kurlar/'.$startDate->format("Ym").'/'.$startDate->format("dmY").'.xml';
				if ($xmlstr = @file_get_contents($url)) {
					$Root = new \SimpleXMLElement($xmlstr);
					foreach ($Root->Currency as $currency)
					{
						if (in_array($currency['CurrencyCode'], array_keys($this->currencies))) {
							$entity = new TCMB();
							$entity->setDate($startDate);
							$entity->setCode($currency['CurrencyCode']);
							$entity->setBanknotebuying($currency->BanknoteBuying);
							$entity->setBanknoteselling($currency->BanknoteSelling);
							$entity->setForexbuying($currency->ForexBuying);
							$entity->setForexselling($currency->ForexSelling);
							$this->em->persist($entity);
						}
					}
					$this->em->flush();
				}
			}
			$startDate->add(new \DateInterval('P1D'));
		}
	}
	
	
	private function getDateStart()
	{
		$entity = $this->em->getRepository('ARIPDIntranetBundle:TCMB')->createQueryBuilder('t')
		->orderBy('t.date', 'DESC')
		->setMaxResults(1)
		->getQuery()->getOneOrNullResult();
		
		if ($entity == null) {
			return null;
		}
		return $entity->getDate();
	}

	
	private function isExist($startDate)
	{
		$entities = $this->em->getRepository('ARIPDIntranetBundle:TCMB')->createQueryBuilder('t')
		->where('t.date = ?1')->setParameter(1, $startDate->format('Y-m-d'))
		->getQuery()->getResult();
		
		if ($entities == null) {
			return false;
		}
		return true;
	}

	
	
	
	
	
	
	function getFxrateData()
	{
		$this->populate();
		
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
	
	
	function graph1($currency="", $start="", $end="")
	{
		/*
		 * TODO: DAYOFWEEK işlevi kulanılarak (Cumartesi=7 ve Pazar=1) günlerinin bunların listelenmesi engellenecek
		 * EXTRACT işlevi kullanılarak bizim tatil günlerinin olduğu bir dizi yaratılarak bunların listelenmesi engellenecek
		 * 6 sıfır atıldığı tarihten (01.01.2005) önceki değerler için 1000000 değerine bölünecek
		 * http://www.tcmb.gov.tr/kurlar/200412/31122004.xml
		 * http://www.tcmb.gov.tr/kurlar/200501/03012005.xml
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