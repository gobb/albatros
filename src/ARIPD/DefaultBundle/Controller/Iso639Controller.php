<?php
namespace ARIPD\DefaultBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDAdminBundle:Iso639
 *
 * @Route("/iso639")
 */
class Iso639Controller extends Controller {

	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_default_iso639_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDAdminBundle:Iso639')->findByActive(true);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDDefaultBundle/Iso639/list.html.twig',
						compact('entities'));
	}

	/**
	 * Sets entity
	 * 
	 * @Route("/{a2}/set", name="aripd_default_iso639_set")
	 * @Template()
	 */
	public function setAction($a2) {
		$request = $this->getRequest();
		//$locale = $request->getLocale();
		$request->setLocale($a2);
		$referer = $request->headers->get('referer');
		var_dump($referer);exit;
		
		$router = $this->get("router");
		//$routeParams = $router->match($request->getPathInfo());
		$routeParams = $router->match($request->getPathInfo());
		var_dump($routeParams);exit;
		
		$routeName = $routeParams['_route'];
		
		/*
		$em = $this->getDoctrine()->getManager();
		$iso639 = $em->getRepository('ARIPDAdminBundle:Iso639')->findOneByCode($a2);
		
		$session = $request->getSession();
		$session->set('a2', $a2);
		*/
		
		return $this->redirect($referer);
	}

}
