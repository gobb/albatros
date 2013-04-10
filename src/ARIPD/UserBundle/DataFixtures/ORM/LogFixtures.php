<?php
namespace ARIPD\UserBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\DataFixtures\ORM\LogtypeFixtures;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use ARIPD\AdminBundle\Util\ARIPDString;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ARIPD\UserBundle\Entity\Log;

class LogFixtures extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface {
	
	private $container;

	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}

	public function load(ObjectManager $manager) {
		foreach (range(1, 300) as $value) {
			$logtype = $this->getReference('aripdadmin_logtype-' . rand(1, count(LogtypeFixtures::$initials)));
			$entity = new Log();
			$entity->setLogtype($logtype);
			$entity->setUser($manager->merge($this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)))));
			$entity->setBonus($logtype->getBonus());

			$manager->persist($entity);

			$this->addReference('aripduser_log-' . $value, $entity);
		}

		$manager->flush();
	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\LogtypeFixtures',
				'ARIPD\UserBundle\DataFixtures\ORM\UserFixtures',
		);
	}
		
}
