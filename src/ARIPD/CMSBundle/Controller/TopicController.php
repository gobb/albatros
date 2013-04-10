<?php
namespace ARIPD\CMSBundle\Controller;

use ARIPD\CMSBundle\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Subtopic
 *
 * @Route("/topic")
 */
class TopicController extends Controller {

	public function topbarAction() {
		$request = $this->container->get('request');

		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDCMSBundle:Topic')
				->createQueryBuilder('t')->leftJoin('t.iso639', 'i')
				->where('i.a2 = ?1')->setParameter(1, $request->getLocale())
				->andWhere('t.hidden = ?2')->setParameter(2, false)->getQuery()
				->getResult();

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Topic/topbar.html.twig',
						compact('entities'));

	}

	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_cms_topic_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$request = $this->container->get('request');
		$iso639 = $em->getRepository('ARIPDAdminBundle:Iso639')
				->findOneByA2($request->getLocale());

		$entities = $em->getRepository('ARIPDCMSBundle:Topic')
				->findBy(array('iso639' => $iso639->getId(), 'hidden' => false));

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Topic/list.html.twig',
						compact('entities'));

	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_cms_topic_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Topic')->find($id);

		$entities = $em->getRepository('ARIPDCMSBundle:Post')
				->findBy(array('approved' => true, 'topic' => $id));

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 10);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDCMSBundle/Topic/' . $entity->getTemplateTopic(),
						array('entity' => $entity, 'pagination' => $pagination,));
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/hidden", name="aripd_cms_topic_hidden")
	 * @Template()
	 */
	public function hiddenAction() {
		$em = $this->getDoctrine()->getManager();

		$request = $this->container->get('request');
		$iso639 = $em->getRepository('ARIPDAdminBundle:Iso639')
				->findOneByA2($request->getLocale());

		$entities = $em->getRepository('ARIPDCMSBundle:Topic')
				->findBy(array('iso639' => $iso639->getId(), 'hidden' => true));

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Topic/hidden.html.twig',
						array('entities' => $entities,));
	}

}
