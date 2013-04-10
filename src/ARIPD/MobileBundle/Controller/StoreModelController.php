<?php
namespace ARIPD\MobileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/store/model")
 */
class StoreModelController extends Controller {
	
	/**
	 * 
	 * @param number $id
	 * @param number $slug
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/{id}/{slug}/show", requirements={"id" = "\d+"}, name="aripd_mobile_store_model_show")
	 * @Template()
	 */
	public function showAction($id, $slug) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDStoreBundle:Model')->find($id);
		return $this
				->render('ARIPDMobileBundle:StoreModel:show.html.twig',
						compact('entity'));
	}
	
}
