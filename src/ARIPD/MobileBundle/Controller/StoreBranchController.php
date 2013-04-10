<?php

namespace ARIPD\MobileBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/store/branch")
 */
class StoreBranchController extends Controller {
	
	/**
	 * @Route("/index", name="aripd_mobile_store_branch_index")
	 * @Template()
	 * 
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDStoreBundle:Branch')->findAll();
		return $this
				->render('ARIPDMobileBundle:StoreBranch:index.html.twig', compact('entities'));
	}
	
	/**
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_mobile_store_branch_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDStoreBundle:Branch')->findOneById($id);
		return $this
				->render('ARIPDMobileBundle:StoreBranch:show.html.twig', compact('entity'));
	}

	/**
	 * @Route("/{id}/map", requirements={"id" = "\d+"}, name="aripd_mobile_store_branch_map")
	 * @Template()
	 */
	public function mapAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDStoreBundle:Branch')->findOneById($id);
		return $this
				->render('ARIPDMobileBundle:StoreBranch:map.html.twig', compact('entity'));
	}

}
