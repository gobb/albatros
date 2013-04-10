<?php
namespace ARIPD\UserBundle\Service;

use ARIPD\AdminBundle\Twig\Extension\IntlExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Twig_ExtensionInterface;

class BasketService extends IntlExtension {
	
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container, EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}
	
	/**
	 * Return basket content
	 */
	public function getBasket() {
		$user = $this->container->get("security.context")->getToken()->getUser();
		return $this->em->getRepository('ARIPDUserBundle:Basket')->findBy(array('user' => $user->getId()));
	}

	public function basketAmountTotal() {
		$shoppingbasket = $this->getBasket();
		
		$totalPriceConverted = 0;
		$totalPriceConvertedFormatted = null;
		if ($shoppingbasket) {
			foreach ($shoppingbasket as $basket) {
				$unitprice = $basket->getModel()->getPrice();
				$unitpriceConverted = $basket->getModel()->getPrice() * $basket->getModel()->getIso4217()->getRate() / $this->container->get('session')->get('iso4217')->getRate();
				$unitpriceConvertedFormatted = $this->twig_localized_currency_filter($unitpriceConverted, $this->container->get('session')->get('iso4217')->getCode());
				$quantity = $basket->getQuantity();
				$subtotal = $unitprice * $quantity;
				$subtotalConverted = $subtotal * $basket->getModel()->getIso4217()->getRate() / $this->container->get('session')->get('iso4217')->getRate();
				$subtotalConvertedFormatted = $this->twig_localized_currency_filter($subtotalConverted, $this->container->get('session')->get('iso4217')->getCode());
				$totalPriceConverted = $totalPriceConverted + $subtotalConverted;
				$totalPriceConvertedFormatted = $this->twig_localized_currency_filter($totalPriceConverted, $this->container->get('session')->get('iso4217')->getCode());
			}
		}
		
		return $totalPriceConverted;
	}

	public function basketAmountTotalAfterVouchercode() {
		$session = $this->container->get('session');

		$basketAmountTotal = $this->basketAmountTotal();

		if ($session->has('vouchercode')) {
			if ($oVoucher = $this->em->getRepository('ARIPDShoppingBundle:Voucher')->isAvailable($session->get('vouchercode'))) {
				$impactRate = $oVoucher->getImpactRate();
				$impactPrice = $oVoucher->getImpactPrice();
				$impactPriceConverted = $impactPrice * $oVoucher->getIso4217()->getRate() / $this->container->get('session')->get('iso4217')->getRate();
				return
							$basketAmountTotal
						+ $basketAmountTotal * $impactRate
						+ $impactPriceConverted
				;
			}
			elseif ($oVouchercode = $this->em->getRepository('ARIPDShoppingBundle:Vouchercode')->isAvailable($session->get('vouchercode'))) {
				$impactRate = $oVouchercode->getVoucher()->getImpactRate();
				$impactPrice = $oVouchercode->getVoucher()->getImpactPrice();
				$impactPriceConverted = $impactPrice * $oVouchercode->getVoucher()->getIso4217()->getRate() / $this->container->get('session')->get('iso4217')->getRate();
				return 
							$basketAmountTotal 
						+ $basketAmountTotal * $impactRate
						+ $impactPriceConverted
				;
			} else {
				$session->remove('vouchercode');
			}
		}
		
		return $basketAmountTotal;
	}

	public function basketAmountTotalAfterVouchercodeAfterPayment() {
		$session = $this->container->get('session');

		$payment = $this->em->getRepository('ARIPDShoppingBundle:Payment')->findOneById($session->get('payment'));

		$basketAmountTotalAfterVouchercode = $this->basketAmountTotalAfterVouchercode();

		$impactPrice = $payment->getImpactPrice();
		$impactPriceConverted = $impactPrice * $payment->getIso4217()->getRate() / $this->container->get('session')->get('iso4217')->getRate();
		
		return $basketAmountTotalAfterVouchercode
				+ $basketAmountTotalAfterVouchercode * $payment->getImpactRate()
				+ $impactPriceConverted
		;
	}

	/**
	 * Taşıma ücretini hesaplar.
	 * Ayarlarda belirtilen miktara göre ücretsiz taşımadan yararlandırır.
	 * 
	 * @return number
	 */
	public function basketAmountTotalAfterVouchercodeAfterPaymentAfterTransportation() {
		if ( $this->basketAmountTotalAfterVouchercode() >= $this->container->get('aripd_config')->get('shopping_transportation_foc_minimum_amount') ) {
			$transportationPrice = 0;
		}
		else {
			$session = $this->container->get('session');
			$transportation = $this->em
					->getRepository('ARIPDShoppingBundle:Transportation')
					->findOneById($session->get('transportation'));
			
			$impactPrice = $transportation->getImpactPrice();
			$impactPriceConverted = $impactPrice * $transportation->getIso4217()->getRate() / $this->container->get('session')->get('iso4217')->getRate();
			
			$transportationPrice = $impactPriceConverted;
		}
				
		$basketAmountTotalAfterVouchercodeAfterPayment = $this->basketAmountTotalAfterVouchercodeAfterPayment();

		return $basketAmountTotalAfterVouchercodeAfterPayment + $transportationPrice;
	}

}
