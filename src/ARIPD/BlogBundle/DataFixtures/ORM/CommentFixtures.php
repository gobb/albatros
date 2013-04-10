<?php
namespace ARIPD\BlogBundle\DataFixtures\ORM;
use ARIPD\UserBundle\DataFixtures\ORM\UserFixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use ARIPD\BlogBundle\Entity\Comment;
use ARIPD\AdminBundle\Util\ARIPDString;

class CommentFixtures extends AbstractFixture implements DependentFixtureInterface {
	public function load(ObjectManager $manager) {
		foreach (range(1, 900) as $value) {

			$post = $this->getReference('aripdblog_post-' . rand(1, PostFixtures::$NOFRecords));
			$user = $this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)));

			$entity = new Comment();
			$entity->setApproved(rand(0, 1));
			$entity->setContent(ARIPDString::generateLoremIpsum());
			$entity->setPost($manager->merge($post));
			$entity->setUser($manager->merge($user));

			$manager->persist($entity);

		}

		$manager->flush();
	}

	public function getDependencies() {
		return array(
				'ARIPD\BlogBundle\DataFixtures\ORM\PostFixtures',
				'ARIPD\UserBundle\DataFixtures\ORM\UserFixtures',
		);
	}
	
}
