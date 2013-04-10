<?php
namespace ARIPD\DefaultBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDAdminBundle:Iso4217
 *
 * @Route("/iso4217")
 */
class Iso4217Controller extends Controller {

	/**
	 * Lists entities as a table
	 * 
	 * @Route("/table", name="aripd_default_iso4217_table")
	 * @Template()
	 */
	public function tableAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDAdminBundle:Iso4217')->findByActive(true);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDDefaultBundle/Iso4217/table.html.twig',
						compact('entities'));
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_default_iso4217_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDAdminBundle:Iso4217')->findByActive(true);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDDefaultBundle/Iso4217/list.html.twig',
						compact('entities'));
	}

	/**
	 * Sets entity
	 * 
	 * @Route("/{code}/set", name="aripd_default_iso4217_set")
	 * @Template()
	 */
	public function setAction($code) {
		$request = $this->getRequest();
		$referer = $request->headers->get('referer');
		
		$em = $this->getDoctrine()->getManager();
		$iso4217 = $em->getRepository('ARIPDAdminBundle:Iso4217')->findOneByCode($code);
		
		$session = $request->getSession();
		$session->set('iso4217', $iso4217);

		return $this->redirect($referer);
	}

}
