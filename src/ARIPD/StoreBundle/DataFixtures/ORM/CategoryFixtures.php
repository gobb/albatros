<?php
namespace ARIPD\StoreBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use ARIPD\StoreBundle\Entity\Category;

class CategoryFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public static $initials = array(
			
			array(1, 'Apple', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/brand/apple.png', array(
					array(2, 'iPhone 3G/3GS'),
					array(3, 'iPhone 4/4G/4S'),
					array(4, 'iPhone 5/5G'),
					array(5, 'iPad 2'),
					array(6, 'New iPad/iPad 3'),
					array(7, 'Mini iPad'),
					array(8, 'iPod Touch'),
			)),
			array(9, 'Samsung', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/brand/samsung.png', array(
					array(10, 'Samsung Galaxy Tab 10.1'),
					array(11, 'Samsung Galaxy S I9000'),
					array(12, 'Samsung Galaxy S2'),
					array(13, 'Samsung Galaxy S3'),
					array(14, 'Samsung Galaxy S3 Mini'),
					array(15, 'Samsung Note 10.1'),
					array(16, 'Samsung Note 2'),
			)),
			array(17, 'Nokia', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/brand/nokia.png', array(
					array(18, 'Nokia Lumia 920'),
			)),
			
	);
	
	public static function getRandom() {
		$rand = self::$initials;
		return $rand[array_rand($rand)];
	}
	
	public static function getRandomSubs() {
		$rand = self::getRandom();
		$subs = $rand[3];
		return $subs[array_rand($subs)];
	}
	
	public function load(ObjectManager $manager) {
		foreach ( self::$initials as $initial) {
			$entity = new Category();
			$entity->setIso639($manager->merge($this->getReference('aripdadmin_iso639-1'/* . rand(1, count(Iso639Fixtures::$iso639s))*/)));
			$entity->setName($initial[1]);
			$entity->setUrl($initial[2]);
			$entity->setDescription(substr(ARIPDString::generateLoremIpsum(), 0, 50));
			$entity->setContent(ARIPDString::generateLoremIpsum());
			//if ($value > 1 && $value < 5) {
				//$entity->setParent($manager->merge($this->getReference('aripdstore_category-1')));
			//}
			
			//$entity->setTemplateCategory('show_t'.rand(1,2).'.html.twig');
			$entity->setTemplateCategory('show.html.twig');
			$entity->setTemplateProduct('show.html.twig');
				
			$manager->persist($entity);
			$this->addReference('aripdstore_category-' . $initial[0], $entity);
			
			foreach ( $initial[3] as $v_sub) {
				$sub = new Category();
				$sub->setIso639($entity->getIso639());
				$sub->setName($v_sub[1]);
				$sub->setTemplateCategory($entity->getTemplateCategory());
				$sub->setTemplateProduct($entity->getTemplateProduct());
				$sub->setParent($entity);
				$manager->persist($sub);
				$this->addReference('aripdstore_category-' . $v_sub[0], $sub);
			}
		}

		$manager->flush();
	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures',
		);
	}
		
}