<?php
namespace ARIPD\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/store/search")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class StoreSearchController extends Controller {

	public function reportAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Search')->findAll();

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:StoreSearch:report.html.twig',
						compact('entities'));
	}

	/**
	 * @Route("/index", name="aripd_admin_storesearch_index")
	 * @Template()
	 */
	public function indexAction() {
		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:StoreSearch:index.html.twig');
	}

}
