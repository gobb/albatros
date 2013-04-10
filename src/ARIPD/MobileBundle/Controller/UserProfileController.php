<?php

namespace ARIPD\MobileBundle\Controller;
use FOS\UserBundle\Model\UserInterface;

use ARIPD\MobileBundle\Form\Type\UserProfileFormType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/user/profile")
 */
class UserProfileController extends Controller {
	
	/**
	 * @Route("/show", name="aripd_mobile_user_profile_show")
	 * @Template()
	 * @Secure(roles="ROLE_USER")
	 */
	public function showAction() {
		$entity = $this->get('security.context')->getToken()->getUser();
		if (!is_object($entity) || !$entity instanceof UserInterface) {
			throw new AccessDeniedException(
					'This user does not have access to this section.');
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDMobileBundle:UserProfile:show.html.twig',
						compact('entity'));
	}

	/**
	 * @Route("/edit", name="aripd_mobile_user_profile_edit")
	 * @Template()
	 * @Secure(roles="ROLE_USER")
	 */
	public function editAction() {
		
	}

}
