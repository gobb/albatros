<?php
namespace ARIPD\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends BaseController {
	protected function renderLogin(array $data) {
		$template = sprintf('::ARIPDUserBundle/Security/login.html.%s',
				$this->container->getParameter('fos_user.template.engine'));

		return $this->container->get('templating')
				->renderResponse($template, $data);
	}
}
