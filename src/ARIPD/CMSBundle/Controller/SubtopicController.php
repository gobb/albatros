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
 * @Route("/subtopic")
 */
class SubtopicController extends Controller {

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_cms_subtopic_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Subtopic')->find($id);

		$entities = $em->getRepository('ARIPDCMSBundle:Post')
				->findBy(array('approved' => true, 'subtopic' => $id));

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 10);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDCMSBundle/Topic/'
								. $entity->getTopic()->getTemplateTopic(),
						array('entity' => $entity, 'pagination' => $pagination,));
	}

}
