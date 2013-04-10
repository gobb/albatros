<?php
namespace ARIPD\UserBundle\Controller;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/profile")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class ProfileController extends Controller {

	/**
	 * @Route("/show", name="aripd_user_profile_show")
	 * @Template()
	 */
	public function showAction() {
		$entity = $this->container->get('security.context')->getToken()
				->getUser();
		if (!is_object($entity) || !$entity instanceof UserInterface) {
			throw new AccessDeniedException(
					'This user does not have access to this section.');
		}

		return $this->get('templating')
				->renderResponse('::ARIPDUserBundle/Profile/show.html.twig',
						compact('entity'));
	}

	/**
	 * @Route("/edit", name="aripd_user_profile_edit")
	 * @Template()
	 */
	public function editAction(Request $request) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException(
					'This user does not have access to this section.');
		}

		/** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
		$dispatcher = $this->container->get('event_dispatcher');

		$event = new GetResponseUserEvent($user, $request);
		$dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		/** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
		$formFactory = $this->container->get('fos_user.profile.form.factory');

		$form = $formFactory->createForm();
		$form->setData($user);

		if ('POST' === $request->getMethod()) {
			$form->bind($request);

			if ($form->isValid()) {
				/** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
				$userManager = $this->container->get('fos_user.user_manager');

				$event = new FormEvent($form, $request);
				$dispatcher
						->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

				$userManager->updateUser($user);

				if (null === $response = $event->getResponse()) {
					$url = $this->container->get('router')
							->generate('aripd_user_profile_show');
					$response = new RedirectResponse($url);
				}

				$dispatcher
						->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED,
								new FilterUserResponseEvent($user, $request,
										$response));

				return $response;
			}
		}

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/Profile/edit.html.'
								. $this->container
										->getParameter(
												'fos_user.template.engine'),
						array('user' => $user, 'form' => $form->createView()));

	}

}
