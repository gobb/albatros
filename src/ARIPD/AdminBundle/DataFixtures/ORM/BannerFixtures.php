<?php
namespace ARIPD\AdminBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\AdminBundle\Entity\Banner;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class BannerFixtures extends AbstractFixture {

	public static $initials = array(
			array('Standard Banner', 468, 60),
			array('Half Banner', 234, 60),
			array('Skyscraper', 120, 600),
			array('Wide Skyscraper', 160, 600),
			array('Button1', 120, 90),
			array('Button2', 120, 60),
			array('Square Button', 125, 125),
			array('Vertical Banner', 120, 240),
			array('Micro Bar', 88, 31),
			array('Rectangle', 180, 150),
			array('Square Pop-Up', 250, 250),
			array('Vertical Rectangle', 240, 400),
			array('Medium Rectangle', 300, 250),
			array('Large Rectangle', 336, 280),
			array('Leaderboard', 728, 90),
	);
	
	public static function getRandom() {
		$rand = self::$initials;
		return $rand[array_rand($rand)];
	}
	
	public function load(ObjectManager $manager) {
		foreach (self::$initials as $initial) {
			$entity = new Banner();
			$entity->setName($initial[0]);
			$entity->setWidth($initial[1]);
			$entity->setHeight($initial[2]);
			
			$manager->persist($entity);
			$this->addReference('aripdadmin_banner-' . ARIPDString::slugify($initial[0]), $entity);
		}
		
		$manager->flush();
	}
	
}
