<?php
namespace ARIPD\DMSBundle\DataFixtures\ORM;
use ARIPD\DMSBundle\Entity\Dealer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DealerFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public static $NOFRecords = 50;

	public function load(ObjectManager $manager) {
		foreach (range(1, self::$NOFRecords) as $v) {
			$entity = new Dealer();
			$entity->setName('Bayi'.$v);
			$entity->setGroup($manager->merge($this->getReference('aripddms_group-' . rand(1, GroupFixtures::$NOFRecords))));
			
			$manager->persist($entity);
			$this->addReference('aripddms_dealer-'.$v, $entity);
		}
		$manager->flush();
	}
	
	public function getDependencies() {
		return array(
				'ARIPD\DMSBundle\DataFixtures\ORM\GroupFixtures',
		);
	}
		
}
