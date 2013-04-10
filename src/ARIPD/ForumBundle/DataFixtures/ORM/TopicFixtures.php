<?php
namespace ARIPD\ForumBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures;
use ARIPD\ForumBundle\Entity\Topic;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TopicFixtures extends AbstractFixture implements DependentFixtureInterface {
	public static $initials = array(
			array(1, null, 'Forum1', null, null, false),
				array(2, 1, 'AltForum11', null, null, false),
				array(3, 1, 'AltForum12', null, null, false),
				array(4, 1, 'AltForum13', null, null, false),
				array(5, 1, 'AltForum14', null, null, false),
			array(6, null, 'Forum2', null, null, false),
				array(7, 6, 'AltForum21', null, null, false),
				array(8, 6, 'AltForum22', null, null, false),
			array(9, null, 'Forum3', null, null, false),
				array(10, 9, 'AltForum31', null, null, false),
				array(11, 9, 'AltForum32', null, null, false),
				array(12, 9, 'AltForum33', null, null, false),
				array(13, 9, 'AltForum34', null, null, false),
				array(14, 9, 'AltForum35', null, null, false),
	);

	/**
	 * parent_id != null olanlar
	 */
	public static function getDescendants() {
		return array_filter( self::$initials, function($initial){
			return($initial[1] != null);
		} );
	}
	
	/**
	 * parent_id != null olanlar arasından random bir tane
	 */
	public static function getRandomDescendant() {
		$descendant = self::getDescendants();
		return $descendant[array_rand($descendant)];
	}
	
	public function load(ObjectManager $manager) {
		foreach ( self::$initials as $initial ) {
			$entity = new Topic();
			$entity->setIso639($manager->merge($this->getReference('aripdadmin_iso639-' . rand(1, count(Iso639Fixtures::$iso639s)))));
			$entity->setSorting($initial[0]);
			$entity->setName($initial[2]);
			if ($initial[1] != null) {
				$entity->setParent($manager->merge($this->getReference('aripdforum_topic-' . $initial[1])));
				$entity->setIso639($entity->getParent()->getIso639());
			}
					
			$manager->persist($entity);
			$this->addReference('aripdforum_topic-' . $initial[0], $entity);
			
		}
		
		$manager->flush();
	}
	
	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures',
		);
	}
	
}
