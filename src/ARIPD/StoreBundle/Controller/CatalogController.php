<?php
namespace ARIPD\StoreBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/catalog")
 */
class CatalogController extends Controller {

	/**
	 * 
	 * @Route("/test")
	 */
	public function testAction() {
		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Catalog/test.html.twig');
	}

	/**
	 * Shows catalog for retail
	 * 
	 * @Route("/retail", name="aripd_store_catalog_retail")
	 * @Route("/retail/page-{page}/", name="aripd_store_catalog_retailpaging")
	 * @Template()
	 */
	public function retailAction($page = 1) {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')->findAll();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities, $page,
						$this->container->get('aripd_config')
								->get('b2c_paginator_nof_products'));

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDStoreBundle/Catalog/retail.html.twig',
						compact('entities', 'pagination'));
	}

	/**
	 * Shows catalog for bulk
	 * 
	 * @Route("/bulk", name="aripd_store_catalog_bulk")
	 * @Template()
	 */
	public function bulkAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')->findAll();

		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Catalog/bulk.html.twig',
						compact('entities'));
	}

}
