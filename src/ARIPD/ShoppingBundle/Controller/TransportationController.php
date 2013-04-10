<?php
namespace ARIPD\ShoppingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDShoppingBundle:Transportation
 * 
 * @Route("/transportation")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class TransportationController extends Controller {
	
	/**
	 * Creates form
	 */
	public function formAction() {
		$session = $this->get('session');
		
		$em = $this->getDoctrine()->getManager();
		
		$entity = $em->getRepository('ARIPDShoppingBundle:Payment')
				->find($session->get('payment'));
		
		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDShoppingBundle/Transportation/form.html.twig',
						compact('entity'));
	}

	/**
	 * Lists entities
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/list", name="aripd_shopping_transportation_list")
	 * @Template()
	 */
	public function listAction() {
		$session = $this->get('session');
		$payment = $session->get('payment');
		if (empty($payment)) {
			return $this
					->redirect(
							$this->generateUrl('aripd_shopping_payment_list'));
		}

		$basketAmountTotalAfterVouchercode = $this
				->get('aripduser.basket_service')
				->basketAmountTotalAfterVouchercode();
		
		/**
		 * Ücretsiz taşımadan yararlandır
		 */
		if ($basketAmountTotalAfterVouchercode >= $this->container->get('aripd_config')->get('shopping_transportation_foc_minimum_amount') ) {
			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('You will get free shipping', array(), 'ARIPDShoppingBundle'));
		}
		
		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDShoppingBundle/Transportation/list.html.twig');
	}

	public function jsonAction() {
		$serializer = $this->container->get('serializer');

		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDShoppingBundle:Transportation')
				->findAll();

		return new Response($serializer->serialize($entities, 'json'), 200,
				array('Content-Type' => 'application/json'));
	}

	/**
	 * Sets transportation to session
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/set", name="aripd_shopping_transportation_set")
	 * @Method("POST")
	 * @Template()
	 */
	public function setAction() {
		$request = $this->getRequest();
		$session = $this->container->get('session');
		$session
				->set('transportation',
						$request->request->get('transportation'));
		
		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_shopping_postaladdress_list'));
	}

}
