<?php
namespace ARIPD\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDBlogBundle:Post
 * 
 * @Route("/post")
 */
class PostController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_blog_post_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		
		$entities = $em->getRepository('ARIPDBlogBundle:Post')
				->createQueryBuilder('p')->select('p')
				->where('p.approved = ?1')->setParameter(1, true)
				->orderBy('p.updatedAt', 'DESC')
				->getQuery()->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 12);

		return $this->container->get('templating')
				->renderResponse('::ARIPDBlogBundle/Post/index.html.twig',
						compact('pagination'));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * @param string $slug
	 * 
	 * @Route("/{id}/{slug}/show", requirements={"id" = "\d+"}, name="aripd_blog_post_show")
	 * @Template()
	 */
	public function showAction($id, $slug) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDBlogBundle:Post')
				->findOneBy(array('approved' => true, 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$this->container->get('aripdblog.post_service')->saveHit($entity);

		return $this->container->get('templating')
				->renderResponse('::ARIPDBlogBundle/Post/show.html.twig',
						array('entity' => $entity,));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param string $tag
	 * 
	 * @Route("/{tag}/tag", name="aripd_blog_post_tag")
	 * @Template()
	 */
	public function tagAction($tag) {
		$em = $this->getDoctrine()->getManager();
		
		$entities = $em->getRepository('ARIPDBlogBundle:Post')
		->createQueryBuilder('p')->select(array('p','t'))
		->innerJoin('p.tags', 't')
		->where('t.name = ?1')->setParameter(1, $tag)
		->andWhere('p.approved = ?2')->setParameter(2, true)
		->orderBy('p.updatedAt', 'DESC')
		->getQuery()->getResult();
		
		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 12);

		return $this->container->get('templating')
				->renderResponse('::ARIPDBlogBundle/Post/index.html.twig',
						compact('pagination'));
	}

}
