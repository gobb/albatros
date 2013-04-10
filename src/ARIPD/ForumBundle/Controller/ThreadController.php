<?php
namespace ARIPD\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDForumBundle:Thread
 * 
 * @Route("/thread")
 */
class ThreadController extends Controller {
	
	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * @param string $slug
	 * 
	 * @Route("/{id}/{slug}/show", requirements={"id" = "\d+"}, name="aripd_forum_thread_show")
	 * @Template()
	 */
	public function showAction($id, $slug) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDForumBundle:Thread')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$this->container->get('aripdforum.thread_service')->saveHit($entity);

		return $this->container->get('templating')
				->renderResponse('::ARIPDForumBundle/Thread/show.html.twig',
						compact('entity'));
	}

}
