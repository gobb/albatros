<?php
namespace ARIPD\SCMBundle\DataFixtures\ORM;
use ARIPD\SCMBundle\Entity\Supplier;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class SupplierFixtures extends AbstractFixture {
	
	public static $initials= array(
			array(1, 'S1', 'Supplier1'),
			array(2, 'S2', 'Supplier2'),
			array(3, 'S3', 'Supplier3'),
			array(4, 'S4', 'Supplier4'),
			array(5, 'S6', 'Supplier5'),
			array(6, 'S7', 'Supplier6'),
			array(7, 'S8', 'Supplier7'),
			array(8, 'S9', 'Supplier8'),
	);
		
	public function load(ObjectManager $manager) {
		foreach ( self::$initials as $initial ) {
			$entity = new Supplier();
			$entity->setCode($initial[1]);
			$entity->setName($initial[2]);
			$manager->persist($entity);
			$this->addReference('aripdscm_supplier-' . $initial[0], $entity);
		}

		$manager->flush();
	}

}
