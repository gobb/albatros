<?php
namespace ARIPD\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Subtopic
 *
 * @Route("/writer")
 */
class WriterController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_cms_writer_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$users = $em->getRepository('ARIPDUserBundle:User')->findAll();

		$entities = array();
		foreach ($users as $user) {
			if ($user->hasRole('ROLE_WRITER'))
				$entities[] = $user;
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Writer/index.html.twig',
						compact('entities'));
	}

	/**
	 * @Route("/{username}/show", requirements={"username" = "\w+"}, name="aripd_cms_writer_show")
	 * @Template()
	 */
	public function showAction($username) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDUserBundle:User')
				->findOneByUsername($username);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Writer/show.html.twig',
						compact('entity'));
	}
	
}
