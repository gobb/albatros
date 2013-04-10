<?php
namespace ARIPD\MobileBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/user/purchaseorder")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class UserPurchaseorderController extends Controller {

	/**
	 * @Route("/index", name="aripd_mobile_user_purchaseorder_index")
	 * @Template()
	 */
	public function indexAction() {
		$entities = $this->get('aripduser.purchaseorder_service')->getAll();

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDMobileBundle:UserPurchaseorder:index.html.twig',
						compact('entities'));
	}

	/**
	 * @Route("/create", name="aripd_mobile_user_purchaseorder_create")
	 * @Template()
	 */
	public function createAction() {
		$poid = $this->get('aripduser.purchaseorder_service')->saveForMobile();

		return $this
				->redirect(
						$this
								->generateUrl('aripd_mobile_user_purchaseorder_show',
										array('poid' => $poid)));

	}

}
