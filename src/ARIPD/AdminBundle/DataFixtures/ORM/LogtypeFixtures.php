<?php
namespace ARIPD\AdminBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Entity\Logtype;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LogtypeFixtures extends AbstractFixture {
	
	public static $initials = array(
			array('logtype___crm_loyalty_confirmed', 'crm loyalty confirmed', 1),
			array('logtype___forum_post_request', 'forum post request', null),
			array('logtype___forum_thread_request', 'forum thread request', null),
			array('logtype___blog_comment_request', 'blog comment request', null),
			array('logtype___blog_comment_confirmed', 'blog comment confirmed', null),
			array('logtype___blog_comment_confirmation_withdrawn', 'blog comment confirmation withdrawn', null),
			array('logtype___cms_comment_request', 'cms comment request', null),
			array('logtype___cms_comment_confirmed', 'cms comment confirmed', 2),
			array('logtype___cms_comment_confirmation_withdrawn', 'cms comment confirmation withdrawn', -2),
			array('logtype___store_comment_request', 'store comment request', null),
			array('logtype___store_comment_confirmed', 'store comment confirmed', 3),
			array('logtype___store_comment_confirmation_withdrawn', 'store comment confirmation withdrawn', -3),
			array('logtype___user_register_request', 'user register request', null),
			array('logtype___user_register_confirmed', 'user register confirmed', 10),
			array('logtype___user_resetting_request', 'user resetting request', null),
			array('logtype___user_resetting_confirmed', 'user resetting confirmed', null),
			array('logtype___user_login', 'user login', null),
			array('logtype___user_logout', 'user logout', null),);
	
	public function load(ObjectManager $manager) {
		foreach (self::$initials as $k => $initial) {
			$entity = new Logtype();
			$entity->setCode($initial[0]);
			$entity->setName($initial[1]);
			$entity->setBonus($initial[2]);

			$manager->persist($entity);

			$this->addReference('aripdadmin_logtype-' . ($k + 1), $entity);
		}

		$manager->flush();
	}

}
