<?php
namespace ARIPD\AdminBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Entity\Iso639;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class Iso639Fixtures extends AbstractFixture implements DependentFixtureInterface {

	public static $iso639s = array(
			array(1, 'tr', 'tur', 'Turkish', 'Türkçe', true, 'TRL'),
			array(2, 'en', 'eng', 'English', 'English', true, 'USD'),
			array(3, 'de', 'ger', 'German', 'Deustch', false, 'EUR'),
	);
	
	public function load(ObjectManager $manager) {

		foreach (self::$iso639s as $iso639) {
			$entity = new Iso639();
			$entity->setA2($iso639[1]);
			$entity->setA3($iso639[2]);
			$entity->setName($iso639[3]);
			$entity->setNative($iso639[4]);
			$entity->setActive($iso639[5]);
			$entity->setIso4217($manager->merge($this->getReference('aripdadmin_iso4217-' . strtolower($iso639[6]))));
			
			$manager->persist($entity);
			$this->addReference('aripdadmin_iso639-' . $iso639[0], $entity);
		}
		$manager->flush();
		
	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso4217Fixtures',
		);
	}

}
