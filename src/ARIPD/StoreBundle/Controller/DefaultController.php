<?php
namespace ARIPD\StoreBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class DefaultController extends Controller {

	/**
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/", name="aripd_store_index")
	 * @Template()
	 */
	public function indexAction() {
		return $this->render('::ARIPDStoreBundle/Default/index.html.twig');
	}

}
