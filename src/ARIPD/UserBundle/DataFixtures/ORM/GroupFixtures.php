<?php
namespace ARIPD\UserBundle\DataFixtures\ORM;
use ARIPD\UserBundle\Entity\Group;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class GroupFixtures extends AbstractFixture {
	
	public static $initials = array(
			array('L1', 'Süper Yönetici', array('ROLE_SUPERADMIN', 'ROLE_ADMIN', 'ROLE_EDITOR', 'ROLE_WRITER', 'ROLE_USER'), false, true, false),
			array('L2', 'Yönetici', array('ROLE_ADMIN', 'ROLE_EDITOR', 'ROLE_WRITER', 'ROLE_USER'), false, true, false),
			array('L3', 'Editor', array('ROLE_EDITOR', 'ROLE_WRITER', 'ROLE_USER'), false, true, false),
			array('L4', 'Yazar', array('ROLE_WRITER', 'ROLE_USER'), false, false, true),
			array('L5', 'Kullanıcı, Okuyucu', array('ROLE_USER'), true, false, false),
	);

	/**
	 * L3 ve L4 olan gruplar
	 */
	public static function getL3L4Groups() {
		return array_filter( self::$initials, function($initial){
			return($initial[0] == 'L3' || $initial[0] == 'L4');
		} );
	}
	
	/**
	 * Approver=true olan gruplar
	 */
	public static function getApprovers() {
		return array_filter( self::$initials, function($initial){
			return($initial[4]);
		} );
	}
	
	public function load(ObjectManager $manager) {
		foreach (self::$initials as $initial) {
			$entity = new Group($initial[1], $initial[2]);
			$entity->setRegistrable($initial[3]);
			$entity->setApprover($initial[4]);
			$entity->setWriter($initial[5]);
			
			$manager->persist($entity);
			$this->addReference('aripduser_group-'.$initial[0], $entity);
		}
		$manager->flush();
	}
	
}
