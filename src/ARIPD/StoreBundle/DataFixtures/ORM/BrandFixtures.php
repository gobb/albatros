<?php
namespace ARIPD\StoreBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use ARIPD\StoreBundle\Entity\Brand;

class BrandFixtures extends AbstractFixture {
	
	public static $initials= array(
			array(1, 'P', 'PDC', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/brand/pdc.png'),
			array(2, 'A', 'Apple', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/brand/apple.png'),
			array(3, 'S', 'Samsung', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/brand/samsung.png'),
			array(4, 'N', 'Nokia', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/brand/nokia.png'),
	);
	
	public function load(ObjectManager $manager) {
		foreach ( self::$initials as $initial ) {
			$entity = new Brand();
			$entity->setCode($initial[1]);
			$entity->setName($initial[2]);
			$entity->setUrl($initial[3]);
			
			$manager->persist($entity);
			$this->addReference('aripdstore_brand-' . $initial[0], $entity);
		}

		$manager->flush();
	}

}
