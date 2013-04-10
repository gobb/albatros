<?php
namespace ARIPD\BlogBundle\DataFixtures\ORM;
use ARIPD\UserBundle\DataFixtures\ORM\UserFixtures;
use ARIPD\BlogBundle\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use ARIPD\BlogBundle\Entity\Post;
use ARIPD\BlogBundle\Entity\Topic;
use ARIPD\AdminBundle\Util\ARIPDString;

class PostFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public static $NOFRecords = 100;
	
	public function load(ObjectManager $manager) {
		foreach (range(1, self::$NOFRecords) as $value) {

			$entity = new Post();
			$entity->setApproved(rand(0, 1));
			$entity->setName('Blog Post' . $value);
			$entity->setContent(ARIPDString::generateLoremIpsum(rand(1, 8)));
			$entity->setUser($manager->merge($this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)))));
			foreach (range(1, 8) as $v_tag) {
				$tag = new Tag();
				$tag->setName('tag-' . $v_tag);
				
				$entity->addTag($tag);
			}
			
			$manager->persist($entity);
			$this->addReference('aripdblog_post-' . $value, $entity);

		}
		
		$manager->flush();

	}

	public function getDependencies() {
		return array(
				'ARIPD\UserBundle\DataFixtures\ORM\UserFixtures',
		);
	}
	
}
