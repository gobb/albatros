<?php
namespace ARIPD\CMSBundle\DataFixtures\ORM;
use ARIPD\CMSBundle\Entity\Comment;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\UserBundle\DataFixtures\ORM\UserFixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public function load(ObjectManager $manager) {
		foreach (range(1, 300) as $value) {

			$entity = new Comment();
			$entity->setApproved(rand(0, 1));
			$entity->setContent(ARIPDString::generateLoremIpsum());
			$entity->setPost($manager->merge($this->getReference('aripdcms_post-' . rand(1, PostFixtures::$NOFRecords))));
			$entity->setUser($manager->merge($this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)))));
			
			$manager->persist($entity);

		}

		$manager->flush();
	}

	public function getDependencies() {
		return array(
				'ARIPD\CMSBundle\DataFixtures\ORM\PostFixtures',
				'ARIPD\UserBundle\DataFixtures\ORM\UserFixtures',
		);
	}
	
}
