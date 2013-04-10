<?php
namespace ARIPD\UserBundle\EventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class AuthenticationListener implements AuthenticationSuccessHandlerInterface,
		AuthenticationFailureHandlerInterface, LogoutSuccessHandlerInterface {

	protected $container;
	protected $em;
	protected $router;
	protected $security;

	public function __construct(ContainerInterface $container,
			EntityManager $em, Router $router, SecurityContext $security) {
		$this->container = $container;
		$this->em = $em;
		$this->router = $router;
		$this->security = $security;
	}

	/**
	 * This is called when an interactive authentication attempt succeeds.
	 * This is called by authentication listeners inheriting from AbstractAuthenticationListener.
	 * 
	 * @see \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface::onAuthenticationSuccess()
	 * @param Request $request
	 * @param TokenInterface $token
	 * @return Response the response to return
	 */
	public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
		if ($request->isXmlHttpRequest()) {
			$result = array('success' => true);
			$response = new Response(json_encode($result));
			$response->headers->set('Content-Type', 'application/json');
		} else {
			//echo $request->request->get('_target_path');exit;
			if ($this->security->isGranted('ROLE_SUPERADMIN')) {
				//$url = $this->router->generate('category_index');
				$url = $request->headers->get('referer');
			} elseif ($this->security->isGranted('ROLE_ADMIN')) {
				$url = $request->headers->get('referer');
			} elseif ($this->security->isGranted('ROLE_USER')) {
				$url = $request->headers->get('referer');
			}
			$response = new RedirectResponse($url);
		}
		return $response;
	}

	public function onAuthenticationFailure(Request $request,
			AuthenticationException $exception) {
		if ($request->isXmlHttpRequest()) {
			$result = array('success' => false, 'message' => $exception->getMessage());
			$response = new Response(json_encode($result));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		} else {
			$referer = $request->headers->get('referer');
			$request->getSession()->getFlashBag()->add('global-notice', $exception->getMessage());
			return new RedirectResponse($referer);
		}
	}

	public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
		$user = $event->getAuthenticationToken()->getUser();

		if ($user instanceof UserInterface) {
			$this->container->get('aripduser.log_service')->saveLog($user, 'logtype___user_login');
		}
	}

	public function onLogoutSuccess(Request $request) {

		$obj = unserialize($request->getSession()->get('_security_primary_auth'));
		//$obj = unserialize($request->getSession()->get('_security_main'));
		// buradaki _security_den sonra gelen main ifadesi app/config/security.yml'deki
		// firewall tanımlamalarındaki "main" isimli firewalldan gelmekte

		if ($obj && $obj->getUser() instanceof UserInterface) {
			$user = $this->em->getRepository('ARIPDUserBundle:User')->findOneByUsername($obj->getUser()->getUsername());
			$this->container->get('aripduser.log_service')->saveLog($user, 'logtype___user_logout');
		}

		$referer_url = $request->headers->get('referer');
		
		if ($referer_url) {
			$url = $referer_url;
		} else {
			$url = $this->container->get('router')->generate('aripd_default_index');
		}
		
		$request->getSession()->getFlashBag()->add('global-notice', $url);
		
		return new RedirectResponse($url);

	}
	
}