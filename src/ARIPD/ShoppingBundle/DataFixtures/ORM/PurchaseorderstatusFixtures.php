<?php
namespace ARIPD\ShoppingBundle\DataFixtures\ORM;
use ARIPD\ShoppingBundle\Entity\Purchaseorderstatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class PurchaseorderstatusFixtures extends AbstractFixture {
	
	public static $initials = array(
			array('purchaseorder.step1', 'Ödeme bekleniyor', 'Waiting for payment'),//Order Placed
			array('purchaseorder.step2', 'Ödeme yapıldı', 'Payment done'),
			array('purchaseorder.step3', 'Sipariş hazırlanıyor', 'Order Processing'),//Checking for availability
			array('purchaseorder.step4', 'Sipariş kargoda', 'Order Dispatched'),//Waiting for carrier company
			array('purchaseorder.step5', 'Sipariş teslim edildi', 'Delivered to carrier company'),//Send Shipment notification
			array('purchaseorder.step6', 'Sipariş iptal edildi', 'Cancelled'),//Cancelled
	);
	
	public function load(ObjectManager $manager) {

		foreach ( self::$initials as $k => $initial) {
			$entity = new Purchaseorderstatus();
			$entity->setCode($initial[0]);
			$entity->setName($initial[1]);
			$this->addReference('aripdshopping_purchaseorderstatus-' . ($k + 1), $entity);
			$manager->persist($entity);
		}

		$manager->flush();

	}

}
