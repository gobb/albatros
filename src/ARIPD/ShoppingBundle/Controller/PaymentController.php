<?php
namespace ARIPD\ShoppingBundle\Controller;

use ARIPD\AdminBundle\Util\VPOS\Akbank;
use ARIPD\ShoppingBundle\Form\Type\MoneytransferFormType;
use ARIPD\ShoppingBundle\Form\Model\MoneytransferEnquiry;
use ARIPD\ShoppingBundle\Form\Type\CreditcardFormType;
use ARIPD\ShoppingBundle\Form\Model\CreditcardEnquiry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDShoppingBundle:Payment
 * 
 * @Route("/payment")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class PaymentController extends Controller {

	/**
	 * 
	 * Bayilerin uzaktan POS yoluyla ödeme yapmaları için
	 * 
	 * @Route("/pos", requirements={"_scheme" = "https"}, name="aripd_shopping_payment_pos")
	 * @Template()
	 * @PreAuthorize("hasRole('ROLE_ADMIN')")
	 */
	public function posAction() {
		$enquiry = new CreditcardEnquiry();
		$form = $this->createForm(new CreditcardFormType(), $enquiry);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$payment = $em->getRepository('ARIPDShoppingBundle:Payment')
						->findOneById(28);
				print_r($payment);
				exit;

				$postData = $request->request
						->get('aripdshopping_creditcardformtype');

				$ccno = $postData['ccno'];
				$cvc = $postData['cvc'];
				$expdateYear = $postData['expdateYear'];
				$expdateMonth = $postData['expdateMonth'];
				$amount = $postData['amount'];
				$taksit = $payment->getParameter();

				$parameters = array(
						'url' => $payment->getPaymentgroup()->getGate2(),
						'pan' => $ccno,
						'Ecom_Payment_Card_ExpDate_Year' => $expdateYear,
						'Ecom_Payment_Card_ExpDate_Month' => $expdateMonth,
						'cv2' => $cvc, 'amount' => $amount, 'currency' => 949,
						'taksit' => $taksit,
						'clientid' => $payment->getPaymentgroup()
								->getClientid(),
						'storetype' => $payment->getPaymentgroup()
								->getStoretype(),
						'storekey' => $payment->getPaymentgroup()
								->getStorekey(),
						'oid' => md5(uniqid(rand(), true)),
						'okUrl' => $this->get('router')
								->generate('aripd_shopping_payment_3dreturn',
										array(), true),
						'failUrl' => $this->get('router')
								->generate('aripd_shopping_payment_3dreturn',
										array(), true), 'islemtipi' => 'Auth',);

				$akbank = new Akbank();
				//$fields = $akbank->testGetFields();
				$fields = $akbank->getFields($parameters);
				$response = $akbank->postData($parameters['url'], $fields);
				//var_dump($response);exit;

			}
		}
		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDShoppingBundle/Payment/pos.html.twig',
						array('form' => $form->createView(),));
	}

	public function formAction() {
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDShoppingBundle:Paymentgroup')
				->findByActive(true);

		$basketAmountTotalAfterVouchercode = $this
				->get('aripduser.basket_service')
				->basketAmountTotalAfterVouchercode();

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDShoppingBundle/Payment/form.html.twig',
						compact('entities', 'basketAmountTotalAfterVouchercode'));
	}

	/**
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/list", name="aripd_shopping_payment_list")
	 * @Template()
	 */
	public function listAction() {
		$shoppingbasket = $this->get('aripduser.basket_service')->getBasket();

		if (empty($shoppingbasket)) {
			return $this
					->redirect($this->generateUrl('aripd_user_basket_index'));
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDShoppingBundle/Payment/list.html.twig');
	}

	/**
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/set", name="aripd_shopping_payment_set")
	 * @Method("POST")
	 * @Template()
	 */
	public function setAction() {
		$request = $this->getRequest();
		$session = $this->container->get('session');
		$session->set('payment', $request->request->get('payment'));
		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_shopping_transportation_list'));
	}

	/**
	 * @Route("/3dreturn", requirements={"_scheme" = "https"}, name="aripd_shopping_payment_3dreturn")
	 * @Template()
	 */
	public function returnAction() {
		$request = $this->getRequest();
		if ($request->request->get('mdStatus') == "1") {
			$poid = $this->get('aripduser.purchaseorder_service')->save();

			$this->get('session')->getFlashBag()
					->add('global-notice',
							$this->get('translator')
									->trans(
											'Credit Card Payment succeeded', array(), 'ARIPDShoppingBundle'));

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_user_purchaseorder_show',
											array('poid' => $poid)));
		} else {
			$this->get('session')->getFlashBag()
					->add('global-notice',
							$this->get('translator')
									->trans(
											'Credit Card Payment unsuccessful', array(), 'ARIPDShoppingBundle'));

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_shopping_payment_checkout'));
		}
	}

	/**
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response|multitype:
	 * 
	 * @Route("/checkout", requirements={"_scheme" = "https"}, name="aripd_shopping_payment_checkout")
	 * @Template()
	 */
	public function checkoutAction() {
		$request = $this->getRequest();
		$session = $request->getSession();

		$user = $this->get("security.context")->getToken()->getUser();

		$deliveryaddress = $session->get('deliveryaddress');
		$invoiceaddress = $session->get('invoiceaddress');
		if (empty($deliveryaddress) || empty($invoiceaddress)) {
			if (empty($deliveryaddress)) {
				$this->get('session')->getFlashBag()
						->add('global-notice',
								$this->get('translator')
										->trans(
												'Please select a delivery address',
												array(), 'ARIPDShoppingBundle'));
			} elseif (empty($invoiceaddress)) {
				$this->get('session')->getFlashBag()
						->add('global-notice',
								$this->get('translator')
										->trans(
												'Please select an invoice address',
												array(), 'ARIPDShoppingBundle'));
			}
			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_shopping_postaladdress_list'));
		}

		$em = $this->getDoctrine()->getManager();
		$payment = $em->getRepository('ARIPDShoppingBundle:Payment')
				->findOneById($session->get('payment'));
		$transportation = $em
				->getRepository('ARIPDShoppingBundle:Transportation')
				->findOneById($session->get('transportation'));
		$deliveryaddress = $em->getRepository('ARIPDUserBundle:Postaladdress')
				->findOneById($session->get('deliveryaddress'));
		$invoiceaddress = $em->getRepository('ARIPDUserBundle:Postaladdress')
				->findOneById($session->get('invoiceaddress'));

		$enquiry = null;
		if ($payment->getPaymentgroup()->getPaymentgroupType()->getCode()
				== 'cc') {
			$enquiry = new CreditcardEnquiry();
			$form = $this->createForm(new CreditcardFormType(), $enquiry);

			if ($request->getMethod() == 'POST') {
				$form->bind($request);
				if ($form->isValid()) {

					/**
					 * TODO
					 * Şu anda sadece AKBANK 3d_pay full modeli için çalışıyor
					 */

					$postData = $request->request
							->get('aripdshopping_creditcardformtype');
					$ccno = $postData['ccno'];
					$cvc = $postData['cvc'];
					$expdateYear = $postData['expdateYear'];
					$expdateMonth = $postData['expdateMonth'];

					$amount = $this->get('aripduser.basket_service')
							->basketAmountTotalAfterVouchercodeAfterPaymentAfterTransportation();
					$taksit = $payment->getParameter();

					$parameters = array(
							'url' => $payment->getPaymentgroup()->getGate2(),
							'pan' => $ccno,
							'Ecom_Payment_Card_ExpDate_Year' => $expdateYear,
							'Ecom_Payment_Card_ExpDate_Month' => $expdateMonth,
							'cv2' => $cvc, 'amount' => $amount,
							'currency' => 949, 'taksit' => $taksit,
							'clientid' => $payment->getPaymentgroup()
									->getClientid(),
							'storetype' => $payment->getPaymentgroup()
									->getStoretype(),
							'storekey' => $payment->getPaymentgroup()
									->getStorekey(),
							'oid' => md5(uniqid(rand(), true)),
							'okUrl' => $this->get('router')
									->generate(
											'aripd_shopping_payment_3dreturn',
											array(), true),
							'failUrl' => $this->get('router')
									->generate(
											'aripd_shopping_payment_3dreturn',
											array(), true),
							'islemtipi' => 'Auth',);

					$akbank = new Akbank();
					//$fields = $akbank->testGetFields();
					$fields = $akbank->getFields($parameters);
					$response = $akbank->postData($parameters['url'], $fields);
					//var_dump($response);exit;

					if ($response['succeed']) {
						echo ($response['response']);
					} else {
						var_dump($response);
					}
					exit;

					//$poid = $this->get('aripduser.purchaseorder_service')->save();

					//$session->getFlashBag()->add('global-notice', 'Siparişiniz işleme alındı. Siparişinizin durumu: ' . $status->getName());

					return $this
							->redirect(
									$this
											->generateUrl(
													'aripd_user_purchaseorder_show',
													array('poid' => $poid)));

				}
			}

			return $this
					->render(
							'::ARIPDShoppingBundle/Payment/checkout.html.twig',
							array('payment' => $payment,
									'deliveryaddress' => $deliveryaddress,
									'invoiceaddress' => $invoiceaddress,
									'transportation' => $transportation,
									'basketAmountTotal' => $this
											->get('aripduser.basket_service')
											->basketAmountTotal(),
									'basketAmountTotalAfterVouchercode' => $this
											->get('aripduser.basket_service')
											->basketAmountTotalAfterVouchercode(),
									'basketAmountTotalAfterVouchercodeAfterPayment' => $this
											->get('aripduser.basket_service')
											->basketAmountTotalAfterVouchercodeAfterPayment(),
									'basketAmountTotalAfterVouchercodeAfterPaymentAfterTransportation' => $this
											->get('aripduser.basket_service')
											->basketAmountTotalAfterVouchercodeAfterPaymentAfterTransportation(),
									'form' => $form->createView(),));
		} elseif ($payment->getPaymentgroup()->getPaymentgrouptype()->getCode()
				== 'mt') {
			$enquiry = new MoneytransferEnquiry();
			$form = $this->createForm(new MoneytransferFormType(), $enquiry);

			if ($request->getMethod() == 'POST') {
				$form->bind($request);
				if ($form->isValid()) {

					$poid = $this->get('aripduser.purchaseorder_service')
							->save();

					//$session->getFlashBag()->add('global-notice', 'Siparişiniz işleme alındı. Siparişinizin durumu: ' . $status->getName());

					return $this
							->redirect(
									$this
											->generateUrl(
													'aripd_user_purchaseorder_show',
													array('poid' => $poid)));
				}
			}

			return $this
					->render(
							'::ARIPDShoppingBundle/Payment/checkout.html.twig',
							array('payment' => $payment,
									'deliveryaddress' => $deliveryaddress,
									'invoiceaddress' => $invoiceaddress,
									'transportation' => $transportation,
									'basketAmountTotal' => $this
											->get('aripduser.basket_service')
											->basketAmountTotal(),
									'basketAmountTotalAfterVouchercode' => $this
											->get('aripduser.basket_service')
											->basketAmountTotalAfterVouchercode(),
									'basketAmountTotalAfterVouchercodeAfterPayment' => $this
											->get('aripduser.basket_service')
											->basketAmountTotalAfterVouchercodeAfterPayment(),
									'basketAmountTotalAfterVouchercodeAfterPaymentAfterTransportation' => $this
											->get('aripduser.basket_service')
											->basketAmountTotalAfterVouchercodeAfterPaymentAfterTransportation(),
									'form' => $form->createView(),));
		} elseif ($payment->getPaymentgroup()->getPaymentgrouptype()->getCode()
				== 'pd') {
			$enquiry = new MoneytransferEnquiry();
			$form = $this->createForm(new MoneytransferFormType(), $enquiry);

			if ($request->getMethod() == 'POST') {
				$form->bind($request);
				if ($form->isValid()) {

					$poid = $this->get('aripduser.purchaseorder_service')
							->save();

					//$session->getFlashBag()->add('global-notice', 'Siparişiniz işleme alındı. Siparişinizin durumu: ' . $status->getName());

					return $this
							->redirect(
									$this
											->generateUrl(
													'aripd_user_purchaseorder_show',
													array('poid' => $poid)));
				}
			}

			return $this
					->render(
							'::ARIPDShoppingBundle/Payment/checkout.html.twig',
							array('payment' => $payment,
									'deliveryaddress' => $deliveryaddress,
									'invoiceaddress' => $invoiceaddress,
									'transportation' => $transportation,
									'basketAmountTotal' => $this
											->get('aripduser.basket_service')
											->basketAmountTotal(),
									'basketAmountTotalAfterVouchercode' => $this
											->get('aripduser.basket_service')
											->basketAmountTotalAfterVouchercode(),
									'basketAmountTotalAfterVouchercodeAfterPayment' => $this
											->get('aripduser.basket_service')
											->basketAmountTotalAfterVouchercodeAfterPayment(),
									'basketAmountTotalAfterVouchercodeAfterPaymentAfterTransportation' => $this
											->get('aripduser.basket_service')
											->basketAmountTotalAfterVouchercodeAfterPaymentAfterTransportation(),
									'form' => $form->createView(),));
		}

		return array();
	}

}
