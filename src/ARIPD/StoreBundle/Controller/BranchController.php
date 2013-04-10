<?php
namespace ARIPD\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDStoreBundle:Branch
 * 
 * @Route("/branch")
 */
class BranchController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_store_branch_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDStoreBundle:Branch')->findAll();

		if (!$entities) {
			throw $this->createNotFoundException('Unable to find');
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Branch/list.html.twig',
						compact('entities'));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_store_branch_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDStoreBundle:Branch')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Branch/show.html.twig',
						compact('entity'));
	}
	
}
