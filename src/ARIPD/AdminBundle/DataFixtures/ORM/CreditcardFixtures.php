<?php
namespace ARIPD\AdminBundle\DataFixtures\ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use ARIPD\AdminBundle\Entity\Creditcard;

class CreditcardFixtures extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface {
	
	private $container;

	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
	
	public function load(ObjectManager $manager) {
		
		if (($handle = fopen(__DIR__ . DIRECTORY_SEPARATOR . "Creditcard.csv", "r")) !== FALSE) {
			
			while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE) {
				$bank = $this->container->get('doctrine')->getManager()->getRepository('ARIPDAdminBundle:Bank')->findOneByCode($data[1]);
				
				$entity = new Creditcard();
				$entity->setBin($data[0]);
				$entity->setBank($bank);
				$entity->setIso3166($manager->merge($this->getReference('aripdadmin_iso3166-TR')));
				$entity->setProduct($data[3]);
				$entity->setOrganization($data[4]);
				$entity->setT($data[5]);
				
				$manager->persist($entity);
				
			}
			
			$manager->flush();
			
			fclose($handle);
			
		}
		
	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso3166Fixtures',
		);
	}

}
