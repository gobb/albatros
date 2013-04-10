<?php
namespace ARIPD\MobileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/store/product")
 */
class StoreProductController extends Controller {
	
	/**
	 * @Route("/index", name="aripd_mobile_store_product_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDStoreBundle:Category')->findAll();
		return $this
				->render('ARIPDMobileBundle:StoreProduct:index.html.twig',
						compact('entities'));
	}

	/**
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_mobile_store_product_show")
	 * @Template()
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDStoreBundle:Product')->find($id);
		return $this
				->render('ARIPDMobileBundle:StoreProduct:show.html.twig',
						compact('entity'));
	}
	
}
