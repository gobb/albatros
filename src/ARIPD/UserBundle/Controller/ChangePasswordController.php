<?php
namespace ARIPD\UserBundle\Controller;
use FOS\UserBundle\Event\FilterUserResponseEvent;

use Symfony\Component\HttpFoundation\RedirectResponse;

use FOS\UserBundle\FOSUserEvents;

use FOS\UserBundle\Event\FormEvent;

use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Model\UserInterface;

use FOS\UserBundle\Controller\ChangePasswordController as BaseController;

class ChangePasswordController extends BaseController {

	public function changePasswordAction(Request $request) {
		$user = $this->container->get('security.context')->getToken()->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		/** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
		$formFactory = $this->container->get('fos_user.change_password.form.factory');
		
		$form = $formFactory->createForm();
		$form->setData($user);
		
		if ($request->isMethod('POST')) {
			$form->bind($request);
		
			if ($form->isValid()) {
				/** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
				$userManager = $this->container->get('fos_user.user_manager');
				/** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
				$dispatcher = $this->container->get('event_dispatcher');
		
				$event = new FormEvent($form, $request);
				$dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, $event);
		
				$userManager->updateUser($user);
		
				if (null === $response = $event->getResponse()) {
					$url = $this->container->get('router')->generate('aripd_user_profile_show');
					$response = new RedirectResponse($url);
				}
		
				$dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
		
				return $response;
			}
		}
		
		return $this->container->get('templating')->renderResponse(
				'::ARIPDUserBundle/ChangePassword/changePassword.html.'.$this->container->getParameter('fos_user.template.engine'),
				array('form' => $form->createView())
		);
	}

}
