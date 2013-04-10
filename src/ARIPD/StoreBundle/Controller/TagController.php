<?php
namespace ARIPD\StoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDStoreBundle:Tag
 *
 * @Route("/tag")
 */
class TagController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_store_tag_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Tag')
				->createQueryBuilder('t')->groupBy('t.name')->orderBy('t.name')
				->getQuery()->getResult();

		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Tag/list.html.twig',
						compact('entities'));
	}
	
	/**
	 * Lists entities
	 * 
	 * @Route("/{category_id}/listbycategoryid", name="aripd_store_tag_listbycategoryid")
	 * @Template()
	 */
	public function listByCategoryIdAction($category_id) {
		$em = $this->getDoctrine()->getManager();

		/**
		 * defaultcategory ye göre
		 */
		/*
		$entities = $em->getRepository('ARIPDStoreBundle:Tag')->createQueryBuilder('t')
				->select(array('t'))
				->leftJoin('t.products', 'p')
				->where('p.defaultcategory = ?1')->setParameter(1, $category_id)
				->groupBy('t.name')
				->orderBy('t.name')
				->getQuery()->getResult();
		*/

		/**
		 * categories e göre
		 */
		$entities = $em->getRepository('ARIPDStoreBundle:Tag')->createQueryBuilder('t')
				->select(array('t'))
				->leftJoin('t.products', 'p')
				->leftJoin('p.categories', 'c')
				->where('c.id = ?1')->setParameter(1, $category_id)
				->groupBy('t.name')
				->orderBy('t.name')
				->getQuery()->getResult();
		
		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Tag/listByCategoryId.html.twig',
						compact('entities'));
	}
	
}
