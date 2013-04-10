<?php
namespace ARIPD\UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/purchaseorder")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class PurchaseorderController extends Controller {
	
	/**
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/index", name="aripd_user_purchaseorder_index")
	 * @Template()
	 */
	public function indexAction() {
		$entities = $this->get('aripduser.purchaseorder_service')->getAll();
		
		return $this
				->render('::ARIPDUserBundle/Purchaseorder/index.html.twig',
						compact('entities'));
	}

	/**
	 * 
	 * @param string $poid
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/{poid}/show", name="aripd_user_purchaseorder_show")
	 * @Template()
	 */
	public function showAction($poid) {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		
		$purchaseorder = $em->getRepository('ARIPDUserBundle:Purchaseorder')
				->findOneBy(array('user' => $user->getId(), 'poid' => $poid));

		return $this
				->render('::ARIPDUserBundle/Purchaseorder/show.html.twig',
						compact('purchaseorder'));
	}
	
}
