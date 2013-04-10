<?php
namespace ARIPD\StockBundle\DataFixtures\ORM;
use ARIPD\SCMBundle\DataFixtures\ORM\SupplierFixtures;
use ARIPD\StoreBundle\DataFixtures\ORM\ProductFixtures;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\StockBundle\Entity\Incoming;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class IncomingFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public function load(ObjectManager $manager) {
		
		$start = new \DateTime();
		$end = new \DateTime();
		
		$pf = new ProductFixtures();
		foreach (range(1, $pf->getNOFModels()) as $value) {
			
			$model = $manager->merge($this->getReference('aripdstore_model-'.$value));
			
			$entity = new Incoming();
			$entity->setSupplier($manager->merge($this->getReference('aripdscm_supplier-'.rand(1,count(SupplierFixtures::$initials)))));
			$entity->setModel($model);
			$entity->setIso4217($manager->merge($this->getReference('aripdadmin_iso4217-trl')));
			$entity->setQuantity(100);
			$entity->setPrice($model->getPrice());
			$entity->setInvoicedAt($start);
			$entity->setCreatedAt($start);
			$entity->setUpdatedAt($end->modify('+60 days'));
			
			$manager->persist($entity);
			$this->addReference('aripdstock_incoming-' . $value, $entity);

		}

		$manager->flush();

	}

	public function getDependencies() {
		return array(
				'ARIPD\StoreBundle\DataFixtures\ORM\ProductFixtures',
				'ARIPD\SCMBundle\DataFixtures\ORM\SupplierFixtures',
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso4217Fixtures',
		);
	}
		
}
