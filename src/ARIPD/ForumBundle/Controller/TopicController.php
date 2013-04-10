<?php
namespace ARIPD\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDForumBundle:Topic
 * 
 * @Route("/topic")
 */
class TopicController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_forum_topic_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDForumBundle:Topic')
				->findByParent(null);

		return $this->container->get('templating')
				->renderResponse('::ARIPDForumBundle/Topic/index.html.twig',
						compact('entities'));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_forum_topic_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDForumBundle:Topic')->find($id);

		$entities = $em->getRepository('ARIPDForumBundle:Thread')
				->findBy(array('topic' => $id));

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 10);

		return $this->container->get('templating')
				->renderResponse('::ARIPDForumBundle/Topic/show.html.twig',
						array('entity' => $entity, 'pagination' => $pagination,));
	}

}
