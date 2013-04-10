<?php
namespace ARIPD\ShoppingBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\ShoppingBundle\Entity\Transportation;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TransportationFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public static $initials = array(
			array(
					'Şirket Teslim', 
					'Ürünlerinizi firmamıza gelip teslim alabilirsiniz', 
					'shopping_transportation_company.png', 
					0
			),
			//array('Aras', 'Aras firması ile ürünlerinizi teslim alabilirsiniz','shopping_transportation_aras.png', 5),
			//array('DHL', 'DHL firması ile ürünlerinizi teslim alabilirsiniz','shopping_transportation_dhl.png', 5),
			//array('MNG', 'MNG firması ile ürünlerinizi teslim alabilirsiniz', 'shopping_transportation_mng.png', 5),
			array(
					'UPS', 
					'UPS firması ile ürünlerinizi teslim alabilirsiniz', 
					'shopping_transportation_ups.png', 
					5
			),
			//array('Yurtiçi Kargo', 'Yurtiçi Kargo firması ile ürünlerinizi teslim alabilirsiniz','shopping_transportation_yurtici.png', 5),
	);
	
	public static function getRandom() {
		$rand = self::$initials;
		return $rand[array_rand($rand)];
	}
	
	public function load(ObjectManager $manager) {
		
		foreach ( self::$initials as $initial ) {
			$entity = new Transportation();
			$entity->setName($initial[0]);
			$entity->setDescription($initial[1]);
			$entity->setPath($initial[2]);
			$entity->setImpactPrice($initial[3]);
			$entity->setIso4217($manager->merge($this->getReference('aripdadmin_iso4217-trl')));
			
			$this->addReference('aripdshopping_transportation-' . ARIPDString::slugify($initial[0]), $entity);
			$manager->persist($entity);
		}

		$manager->flush();

	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso4217Fixtures',
		);
	}
	
}
