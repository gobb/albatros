<?php
namespace ARIPD\DMSBundle\DataFixtures\ORM;
use ARIPD\DMSBundle\Entity\SalesorderStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class SalesorderStatusFixtures extends AbstractFixture {
	
	public static $initials = array(
			array('salesorder.step1', 'Ödeme bekleniyor', 'Waiting for payment'),//Order Placed
			array('salesorder.step2', 'Ödeme yapıldı', 'Payment done'),
	);
	
	public function load(ObjectManager $manager) {

		foreach ( self::$initials as $k => $initial) {
			$entity = new SalesorderStatus();
			$entity->setCode($initial[0]);
			$entity->setName($initial[1]);
			$this->addReference('aripddms_salesorderstatus-' . ($k + 1), $entity);
			$manager->persist($entity);
		}

		$manager->flush();

	}

}
