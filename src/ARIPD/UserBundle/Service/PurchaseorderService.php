<?php
namespace ARIPD\UserBundle\Service;

use ARIPD\StockBundle\Entity\Outgoing;
use ARIPD\UserBundle\Entity\Purchaseorder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class PurchaseorderService {
	
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container, EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}

	/**
	 * Returns all purchase orders of user
	 */
	public function getAll() {
		$user = $this->container->get("security.context")->getToken()->getUser();
		return $this->em->getRepository('ARIPDUserBundle:Purchaseorder')->findBy(array('user' => $user->getId()));
	}
	
	/**
	 * Creates purchase order for mobile
	 */
	public function saveForMobile() {
		$user = $this->container->get('security.context')->getToken()->getUser();

		$statuscode = 'purchaseorder.step1';
		$status = $this->em->getRepository('ARIPDShoppingBundle:Purchaseorderstatus')->findOneByCode($statuscode);

		$purchaseorder = new Purchaseorder();
		$purchaseorder->setStatus($status);
		$purchaseorder->setUser($user);
		
		$this->em->persist($purchaseorder);
		$this->em->flush();

		// TODO: sipariş içeriğini gir
		$shoppingbasket = $this->container->get('aripduser.basket_service')->getBasket();
		foreach ($shoppingbasket as $id => $quantity) {
			$model = $this->em->getRepository('ARIPDStoreBundle:Model')->find($id);
			$outgoing = new Outgoing();
			$outgoing->setModel($model);
			$outgoing->setPurchaseorder($purchaseorder);
			$outgoing->setQuantity($quantity);
			
			$this->em->persist($outgoing);
		}
		$this->em->flush();

		// TODO: return array of purchase order id, status name
		return $purchaseorder->getPoid();
	}
	
	/**
	 * Creates purchase order
	 */
	public function save() {
		$session = $this->container->get('session');

		$user = $this->container->get('security.context')->getToken()->getUser();

		$payment = $this->em->getRepository('ARIPDShoppingBundle:Payment')->findOneById($session->get('payment'));
		$transportation = $this->em->getRepository('ARIPDShoppingBundle:Transportation')->findOneById($session->get('transportation'));
		$deliveryaddress = $this->em->getRepository('ARIPDUserBundle:Postaladdress')->findOneById($session->get('deliveryaddress'));
		$invoiceaddress = $this->em->getRepository('ARIPDUserBundle:Postaladdress')->findOneById($session->get('invoiceaddress'));
		if ($session->has('vouchercode')) {
			if ($voucher = $this->em->getRepository('ARIPDShoppingBundle:Voucher')->isAvailable($session->get('vouchercode'))) {
			}
			elseif ($vouchercode = $this->em->getRepository('ARIPDShoppingBundle:Vouchercode')->isAvailable($session->get('vouchercode'))) {
			}
		}

		switch ($payment->getPaymentgroup()->getPaymentgrouptype()->getCode()) {
		case 'cc':
			$statuscode = 'purchaseorder.step2';
			break;
		case 'mt':
			$statuscode = 'purchaseorder.step1';
			break;
		case 'pd':
			$statuscode = 'purchaseorder.step3';
			break;
		}
		$status = $this->em->getRepository('ARIPDShoppingBundle:Purchaseorderstatus')->findOneByCode($statuscode);

		$purchaseorder = new Purchaseorder();
		$purchaseorder->setDeliveryaddress($deliveryaddress);
		$purchaseorder->setInvoiceaddress($invoiceaddress);
		$purchaseorder->setPayment($payment);
		$purchaseorder->setStatus($status);
		$purchaseorder->setTransportation($transportation);
		$purchaseorder->setUser($user);
		
		if ($session->has('vouchercode')) {
			if ($voucher) {
				$purchaseorder->setVoucher($voucher);
				$purchaseorder->setVouchercode(null);
			}
			elseif ($vouchercode) {
				$purchaseorder->setVoucher($vouchercode->getVoucher());
				$purchaseorder->setVouchercode($vouchercode);
			}
		}
				
		/**
		 * Sipariş içeriği
		 */
		$shoppingbasket = $this->container->get('aripduser.basket_service')->getBasket();
		foreach ($shoppingbasket as $basket) {
			$outgoing = new Outgoing();
			$outgoing->setModel($basket->getModel());
			$outgoing->setQuantity($basket->getQuantity());
			$outgoing->setPrice($basket->getModel()->getPrice());
			$outgoing->setIso4217($basket->getModel()->getIso4217());
			$purchaseorder->addOutgoing($outgoing);
			
			$this->em->persist($outgoing);
		}
		
		$this->em->persist($purchaseorder);
		$this->em->flush();

		/**
		 * Siparişi hem kullanıcıya hem de sistem yöneticisine e-posta yoluyla gönder
		 */
		$purchaseorderUrl = $this->container->get('router')->generate('aripd_user_purchaseorder_show', array('poid' => $purchaseorder->getPoid()), true);
		
		$to = array_merge(array($user->getEmail()), $this->container->getParameter('administrators'));
		
		$message = \Swift_Message::newInstance()
				->setSubject($this->container->get('translator')->trans('purchaseorder.email.subject', array(), 'ARIPDShoppingBundle'))
				->setFrom($this->container->getParameter('mail_sender_address'))
				->setTo($to)
				->setBody($this->container->get('templating')->render(
						'ARIPDUserBundle:Purchaseorder:email.txt.twig',
						compact('purchaseorder', 'purchaseorderUrl')), 'text/html', 'utf-8');
		
		$this->container->get('mailer')->send($message);
		
		
		/**
		 * Sepeti (veritabanından) ve session bilgisini (ödeme şekli, taşıma, teslimat ve fatura adresi) temizle
		 * Tümünü silersen iso4217 session da set edildiğinden sorun çıkıyor. Sadece shopping de kullanılan session ları sil
		 */
		//$session->clear();
		$session->remove('payment');
		$session->remove('transportation');
		$session->remove('deliveryaddress');
		$session->remove('invoiceaddress');
		if ($session->has('vouchercode')) {
			$session->remove('vouchercode');
		}
		$this->em->getRepository('ARIPDUserBundle:Basket')->createQueryBuilder('b')
				->delete()->where('b.user = ?1')
				->setParameter(1, $user->getId())->getQuery()->getResult();

		
		/**
		 * TODO return array of purchase order id, status name
		 */
		return $purchaseorder->getPoid();
	}
}
