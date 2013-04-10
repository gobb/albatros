<?php
namespace ARIPD\IntranetBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use ARIPD\IntranetBundle\Helper\EarthquakeHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * 
 * @Route("/earthquake")
 */
class EarthquakeController extends Controller {
	
	/**
	 * @Route("/", name="aripd_intranet_earthquake_index")
	 * @Template()
	 */
	public function indexAction() {
		return $this->render('ARIPDIntranetBundle:Earthquake:index.html.twig');
	}

	public function jsonAction() {
		$helper = new EarthquakeHelper();
		$entities = $helper->retrieve();

		$serializer = $this->container->get('serializer');
		return new Response($serializer->serialize($entities, 'json'), 200,
				array('Content-Type' => 'application/json'));
	}
}
