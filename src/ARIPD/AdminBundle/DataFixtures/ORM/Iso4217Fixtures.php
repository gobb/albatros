<?php
namespace ARIPD\AdminBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Entity\Iso4217;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class Iso4217Fixtures extends AbstractFixture {

	public static $iso4217s = array(
			array(true, 'TRL', 949, 0, ',', '.', 'Türk Lirası', 'TL', 1),
			array(true, 'USD', 840, 3, '.', ',', 'US Dollar', '$', 1.78924),
			//array(false, 'SEK'),
			//array(false, 'SAR'),
			//array(false, 'NOK'),
			//array(false, 'KWD'),
			//array(false, 'JPY'),
			//array(false, 'GBP'),
			array(true, 'EUR', 978, 2, '.', ',', 'Euro', '€', 2.31068),
			//array(false, 'DKK'),
			//array(false, 'CHF'),
			//array(false, 'CAD', 2, '.', ',', 'Canadian Dollar', '$'),
			//array(false, 'AUD', 2, '.', ',', 'Australian Dollar', '$'),
	);
	
	public function load(ObjectManager $manager) {

		foreach (self::$iso4217s as $initial) {
			$entity = new Iso4217();
			$entity->setActive($initial[0]);
			$entity->setCode($initial[1]);
			$entity->setNo($initial[2]);
			$entity->setDecimalPlaces($initial[3]);
			$entity->setDecimalPoint($initial[4]);
			$entity->setThousandsSeperator($initial[5]);
			$entity->setName($initial[6]);
			$entity->setSymbol($initial[7]);
			$entity->setRate($initial[8]);
			$manager->persist($entity);
			$this->addReference('aripdadmin_iso4217-' . strtolower($entity->getCode()), $entity);
		}
		$manager->flush();
		
	}

}
