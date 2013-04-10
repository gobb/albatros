<?php
namespace ARIPD\IntranetBundle\Helper;

class EarthquakeHelper
{
	public function retrieve()
	{
		//$url = "http://www.koeri.boun.edu.tr/scripts/lst9.asp";
		$url = "http://www.koeri.boun.edu.tr/scripts/lasteq.asp";
		//$contents = $this->file_get_contents_utf8($url);
		
		$contentHTML = file_get_contents($url);
		$contents = mb_convert_encoding($contentHTML, 'UTF-8', mb_detect_encoding($contentHTML, 'UTF-8, ISO-8859-1', true));
		
		
		$depremler = array();
		$depremler["cols"][0] = array("id"=>"A","label"=>"Date","pattern"=>"","type"=>"date");
		$depremler["cols"][1] = array("id"=>"B","label"=>"Enlem","pattern"=>"","type"=>"number");
		$depremler["cols"][2] = array("id"=>"C","label"=>"Boylam","pattern"=>"","type"=>"number");
		$depremler["cols"][3] = array("id"=>"D","label"=>"Derinlik","pattern"=>"","type"=>"number");
		$depremler["cols"][4] = array("id"=>"E","label"=>"Magnitude","pattern"=>"","type"=>"number");
		$depremler["cols"][5] = array("id"=>"F","label"=>"Yer","pattern"=>"","type"=>"string");
		
		
		$pattern='/(\d{4})\.(\d{2})\.(\d{2})\s(\d{2}):(\d{2}):(\d{2})\s+(\d{2}\.\d{4})\s+(\d{2}\.\d{4})\s+(\d+\.\d)\s+([\d\.-\s]+)(.+)/';
		preg_match_all($pattern, $contents, $out);
		unset($out[0]);
		$nof = 50;
		for($i = 0; $i < $nof; $i++) {
			//$depremler[] = array('timestamp'=>mktime($out[4][$i], $out[5][$i], $out[6][$i], $out[2][$i], $out[3][$i], $out[1][$i]), 'enlem'=>$out[7][$i], 'boylam'=>$out[8][$i], 'derinlik'=>$out[9][$i], 'buyukluk'=>trim(str_replace("-.-", "", $out[10][$i])), 'yer'=>trim($out[11][$i]));
			$depremler["rows"][$i]["c"][0] = array(
					'v'=>'Date('.$out[1][$i].', '.($out[2][$i]-1).', '.$out[3][$i].', '.$out[4][$i].', '.$out[5][$i].', '.$out[6][$i].')',
					'f'=>strftime("%Y-%m-%d %H:%M:%S",strtotime($out[1][$i].'-'.$out[2][$i].'-'.$out[3][$i].' '.$out[4][$i].':'.$out[5][$i].':'.$out[6][$i]))
			);
			$depremler["rows"][$i]["c"][1] = array('v'=>$out[7][$i]);
			$depremler["rows"][$i]["c"][2] = array('v'=>$out[8][$i]);
			$depremler["rows"][$i]["c"][3] = array('v'=>$out[9][$i]);
			$depremler["rows"][$i]["c"][4] = array('v'=>trim(str_replace("-.-", "", $out[10][$i])));
			$depremler["rows"][$i]["c"][5] = array('v'=>trim($out[11][$i]));
		}
		unset($out);
		//print_r($depremler);
		
		return $depremler;
	}

	private function trigger($sayi=5)
	{
		//$url = "http://www.koeri.boun.edu.tr/scripts/lst9.asp";
		$url = "http://www.koeri.boun.edu.tr/scripts/lasteq.asp";
		$contents = $this->file_get_contents_utf8($url);
		$pattern='/(\d{4})\.(\d{2})\.(\d{2})\s(\d{2}):(\d{2}):(\d{2})\s+(\d{2}\.\d{4})\s+(\d{2}\.\d{4})\s+(\d+\.\d)\s+([\d\.-\s]+)(.+)/';
		preg_match_all($pattern, $contents, $out);
		unset($out[0]);
		$depremler = array();
		for($i = 0; $i < $sayi; $i++) {
			$depremler[] = array('timestamp'=>mktime($out[4][$i], $out[5][$i], $out[6][$i], $out[2][$i], $out[3][$i], $out[1][$i]), 'enlem'=>$out[7][$i], 'boylam'=>$out[8][$i], 'derinlik'=>$out[9][$i], 'buyukluk'=>trim(str_replace("-.-", "", $out[10][$i])), 'yer'=>trim($out[11][$i]));
		}
		unset($out);
		return $depremler;
	}

	private function file_get_contents_utf8($url)
	{
		return mb_convert_encoding(file_get_contents($url), 'UTF-8', mb_detect_encoding($contentHTML, 'UTF-8, ISO-8859-1', true));
	}

}