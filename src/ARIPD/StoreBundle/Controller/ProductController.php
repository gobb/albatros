<?php
namespace ARIPD\StoreBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDStoreBundle:Product
 *
 * @Route("/product")
 */
class ProductController extends Controller {

	/**
	 * Lists recommended entities by product id
	 * 
	 * @Route("/{id}/recommend", requirements={"id" = "\d+"}, name="aripd_store_product_recommend")
	 * @Template()
	 */
	public function recommendAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Product')->find($id);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDStoreBundle/Product/recommend.html.twig',
						array('entities' => $entity->getMyRecommends()));
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_store_product_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')->findAll();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1),
						$this->container->get('aripd_config')
								->get('b2c_paginator_nof_products'));

		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Product/list.html.twig',
						compact('entities', 'pagination'));
	}

	/**
	 * Lists entities by category id
	 * 
	 * @Route("/{id}/{template}/listbycategoryid", name="aripd_store_product_listbycategoryid")
	 * @Template()
	 */
	public function listbycategoryidAction($id, $template) {
		$em = $this->getDoctrine()->getManager();

		/**
		 * defaultcategory ye göre
		 */
		$entities = $em->getRepository('ARIPDStoreBundle:Product')
				->findBy(array('defaultcategory' => $id));

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1),
						$this->container->get('aripd_config')
								->get('b2c_paginator_nof_products'));

		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Product/' . $template,
						compact('entities', 'pagination'));
	}

	/**
	 * Lists new entities
	 * 
	 * @Route("/brandnew", name="aripd_store_product_brandnew")
	 * @Template()
	 */
	public function brandnewAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')
				->findByBrandnew(true);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDStoreBundle/Product/brandnew.html.twig',
						compact('entities'));
	}

	/**
	 * Lists latest entities
	 * 
	 * @Route("/{maxResults}/latest", requirements={"maxResults" = "\d+"}, name="aripd_store_product_latest")
	 * @Template()
	 */
	public function latestAction($maxResults = 3) {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')
				->createQueryBuilder('p')->orderBy('p.publishedAt', 'DESC')
				->setMaxResults($maxResults)->getQuery()->getResult();

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDStoreBundle/Product/latest.html.twig',
						compact('entities'));
	}

	/**
	 * Lists vitrine entities
	 * 
	 * @Route("/vitrine", name="aripd_store_product_vitrine")
	 * @Template()
	 */
	public function vitrineAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')
				->createQueryBuilder('p')->where('p.approved = ?1')
				->setParameter(1, true)->andWhere('p.publishedAt <= ?2')
				->setParameter(2, new \DateTime())->andWhere('p.vitrine = ?3')
				->setParameter(3, true)->orderBy('p.publishedAt', 'DESC')
				->getQuery()->getResult();

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDStoreBundle/Product/vitrine.html.twig',
						compact('entities'));
	}

	public function jsonAction() {
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDStoreBundle:Product')->findAll();

		$serializer = $this->container->get('serializer');
		return new Response($serializer->serialize($entities, 'json'), 200,
				array('Content-Type' => 'application/json'));
	}

	/**
	 * @Route("/{maxResults}/showbytags", requirements={"maxResults" = "\d+"}, name="aripd_store_product_showbytags")
	 * @Template()
	 */
	public function showByTagsAction($maxResults = 1) {
		$tags = $this->container->get('session')->get('aripdads_tags');

		$em = $this->getDoctrine()->getManager();

		$qb = $em->getRepository('ARIPDAdsBundle:Advertisement')
				->createQueryBuilder('a');
		$qb->select(array('a', 't'));
		$qb->leftJoin('a.tags', 't');
		if ($tags) {
			foreach ($tags as $k => $tag) {
				if ($k == 0)
					$qb->where("t.name = '" . $tag->getName() . "'");
				else
					$qb->orWhere("t.name = '" . $tag->getName() . "'");
			}
		}
		$qb->groupBy('a.id');
		$qb->setMaxResults($maxResults);
		$entities = $qb->getQuery()->getResult();

		foreach ($entities as $entity) {
			$this->container->get('aripdads.ads_service')->saveView($entity);
		}

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDAdsBundle/Advertisement/list.html.twig',
						compact('entities'));
	}

}
