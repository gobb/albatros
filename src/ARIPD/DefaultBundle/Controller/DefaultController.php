<?php
/*
 * This file is part of the Albatros package.
 *
 * (c) ARIPD <bilgi@aripd.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ARIPD\DefaultBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDDefaultBundle:Default
 *
 * @Route("/")
 */
class DefaultController extends Controller {

	/**
	 * Shows home page
	 * 
	 * @Route("/", name="aripd_default_index")
	 * @Template()
	 */
	public function indexAction() {
		return $this->container->get('templating')
				->renderResponse('::index.html.twig');
	}

	/**
	 * Shows test page
	 * 
	 * @Route("/test", name="aripd_default_test")
	 * @Template()
	 */
	public function testAction() {
		
		/*
		$url = 'http://localhost:8983/solr/admin/ping';
		if ($xmlstr = @file_get_contents($url)) {
		  $Root = new \SimpleXMLElement($xmlstr);
		  echo $Root->str;
		}
		else {
		  echo 'not connected';
		}
		exit;
		 */

		/*
		$client = $this->get('solarium.client');
		$ping = $client->createPing();
		try{
		  $result = $client->ping($ping);
		  echo 'Ping başarılı, bağlantı kuruldu.';
		  echo "<pre>";
		  print_r($result->getData());
		  echo "</pre>";
		}catch(\Solarium_Exception $e){
		  echo 'Ping başarısız, kontrol et.';
		}
		exit;
		 */

		/*
		$client = $this->get('solarium.client');
		$query = $client->createSelect();
		//$query->setRows(30);
		$query->setStart(0)->setRows(5);
		//$query->setFields(array('id','name','description','content','features','price'));
		$query->addSort('id', \Solarium_Query_Select::SORT_ASC);
		//$query->setQuery('memory');
		
		$resultset = $client->select($query);
		
		header('Content-Type: text/html; charset=utf-8');
		echo 'NumFound: '.$resultset->getNumFound();
		foreach ($resultset as $document) {
		  echo '<hr/><table>';
		  foreach($document AS $field => $value) {
		    if(is_array($value)) $value = implode(', ', $value);
		    echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
		  }
		  echo '</table>';
		}
		exit;
		 */

		$session = $this->container->get('session');
		print_r($session);
		exit;

		$request = $this->getRequest();
		var_dump($request->cookies);
		exit;

		$request = $this->getRequest();
		var_dump($request->attributes);
		exit;

	}

}
