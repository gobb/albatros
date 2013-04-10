<?php
namespace ARIPD\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDStoreBundle:Brand
 *
 * @Route("/brand")
 */
class BrandController extends Controller {
	
	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_store_brand_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDStoreBundle:Brand')->find($id);

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entity->getProducts(),
						$this->get('request')->query->get('page', 1), 7);
		
		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Brand/show.html.twig',
						compact('entity', 'pagination'));
	}

	public function jsonAction($id) {
		$serializer = $this->container->get('serializer');

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDStoreBundle:Brand')->find($id);

		return new Response($serializer->serialize($entity, 'json'), 200,
				array('Content-Type' => 'application/json'));
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_store_brand_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Brand')->findAll();

		return $this
				->render('::ARIPDStoreBundle/Brand/list.html.twig',
						compact('entities'));
	}
	
}
