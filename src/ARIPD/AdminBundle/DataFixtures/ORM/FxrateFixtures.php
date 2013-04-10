<?php
namespace ARIPD\AdminBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use ARIPD\AdminBundle\Entity\Fxrate;

class FxrateFixtures extends AbstractFixture implements DependentFixtureInterface {
	public function load(ObjectManager $manager) {

		$entity = new Fxrate();
		$entity->setDate(new \DateTime());
		$entity->setIso4217($manager->merge($this->getReference('aripdadmin_iso4217-trl')));
		$entity->setBanknoteBuying(1);
		$entity->setBanknoteSelling(1);
		$entity->setForexBuying(1);
		$entity->setForexSelling(1);
		$manager->persist($entity);

		$manager->flush();
	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso4217Fixtures',
		);
	}
	
}
