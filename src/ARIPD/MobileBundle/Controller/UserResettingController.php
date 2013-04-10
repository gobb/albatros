<?php
namespace ARIPD\MobileBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ResettingController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/user/resetting")
 */
class UserResettingController extends BaseController {

	/**
	 * @Route("/request", name="aripd_mobile_user_resetting_request")
	 * @Template()
	 */
	public function requestAction() {
		return $this->container->get('templating')
				->renderResponse(
						'ARIPDMobileBundle:UserResetting:request.html.'
								. $this->getEngine());
	}

    /**
     * Request reset user password: submit form and send email
     */
    public function sendEmailAction(Request $request)
    {
        $username = $request->request->get('username');

        /** @var $user UserInterface */
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return $this->container->get('templating')->renderResponse('::ARIPDUserBundle/Resetting/request.html.'.$this->getEngine(), array('invalid_username' => $username));
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return $this->container->get('templating')->renderResponse('::ARIPDUserBundle/Resetting/passwordAlreadyRequested.html.'.$this->getEngine());
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->container->get('session')->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));
        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);

        /*****************************************************
         * Log to the database
        *****************************************************/
        $this->container->get('aripduser.log_service')->saveLog($user, 'logtype___user_resetting_request');
        
        return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_check_email'));
    }

	public function checkEmailAction() {
		$session = $this->container->get('session');
		$email = $session->get(static::SESSION_EMAIL);
		$session->remove(static::SESSION_EMAIL);

		if (empty($email)) {
			// the user does not come from the sendEmail action
			return new RedirectResponse(
					$this->container->get('router')
							->generate('fos_user_resetting_request'));
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDMobileBundle:UserResetting:checkEmail.html.'
								. $this->getEngine(),
						array('email' => $email,));
	}

	public function resetAction(Request $request, $token) {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.resetting.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('aripd_user_profile_show');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        /*****************************************************
         * Log to the database
        *****************************************************/
        $this->container->get('aripduser.log_service')->saveLog($user, 'logtype___user_resetting_confirmed');
        
        return $this->container->get('templating')->renderResponse('::ARIPDUserBundle/Resetting/reset.html.'.$this->getEngine(), array(
            'token' => $token,
            'form' => $form->createView(),
        ));
	}

}
