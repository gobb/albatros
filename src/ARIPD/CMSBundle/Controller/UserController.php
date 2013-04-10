<?php
namespace ARIPD\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Subtopic
 *
 * @Route("/user")
 */
class UserController extends Controller {
	
	/**
	 * @Route("/{username}/show", requirements={"username" = "\w+"}, name="aripd_cms_user_show")
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
				->renderResponse('::ARIPDCMSBundle/User/show.html.twig',
						compact('entity'));
	}
	
}
