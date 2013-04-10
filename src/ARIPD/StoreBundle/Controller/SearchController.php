<?php
namespace ARIPD\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDStoreBundle:Search
 *
 * @Route("/search")
 */
class SearchController extends Controller {

	/**
	 * @Route("/result", name="aripd_store_search_result")
	 * @Template()
	 */
	public function resultAction() {
		$request = $this->getRequest();
		$q = $request->query->get('q');
		
		$this->container->get('aripdstore.search_service')->saveSearch($q);

		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')
				->createQueryBuilder('p')->select(array('p'))
				->where('p.approved = ?1')->setParameter(1, true)
				->andWhere('p.name LIKE ?3 OR p.code LIKE ?4')
				->setParameter(3, "%$q%")->setParameter(4, "%$q%")->getQuery()
				->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 12);

		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Search/result.html.twig',
						array('entities' => $entities, 'pagination' => $pagination, 'q' => $q,));
	}

	/**
	 * @Route("/{tag}/tag", name="aripd_store_search_tag")
	 * @Template()
	 */
	public function tagAction($tag) {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')
				->createQueryBuilder('p')->select(array('p', 't'))
				->innerJoin('p.tags', 't')->where('t.name = ?1')
				->setParameter(1, $tag)->andWhere('p.approved = ?2')
				->setParameter(2, true)->orderBy('p.updatedAt', 'DESC')
				->getQuery()->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 12);

		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Search/result.html.twig',
						array('pagination' => $pagination, 'q' => $tag,));
	}

}
