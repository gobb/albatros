<?php
namespace ARIPD\DMSBundle\DataFixtures\ORM;
use ARIPD\DMSBundle\Entity\Group;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GroupFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public static $NOFRecords = 3;
	
	public function load(ObjectManager $manager) {
		foreach (range(1, self::$NOFRecords) as $v) {
			$entity = new Group();
			$entity->setName('Bayi tipi '.$v);
			$entity->setImpactRate(-($v/10));
			$entity->setImpactPrice(-($v));
			$entity->setIso4217($manager->merge($this->getReference('aripdadmin_iso4217-trl')));
			
			$manager->persist($entity);
			$this->addReference('aripddms_group-'.$v, $entity);
		}
		$manager->flush();
	}
	
	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso4217Fixtures',
		);
	}
		
}
