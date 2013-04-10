<?php
namespace ARIPD\ForumBundle\DataFixtures\ORM;
use ARIPD\UserBundle\DataFixtures\ORM\UserFixtures;
use ARIPD\ForumBundle\Entity\Thread;
use ARIPD\ForumBundle\Entity\Tag;
use ARIPD\ForumBundle\Entity\Post;
use ARIPD\ForumBundle\Entity\Topic;
use ARIPD\AdminBundle\Util\ARIPDString;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public static $NOFRecords = 30;
	
	public function load(ObjectManager $manager) {
		foreach (range(1, self::$NOFRecords) as $t_value) {

			$thread = new Thread();
			$thread->setName('Thread'.$t_value);
			$thread->setUser($manager->merge($this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)))));
			
			// parent!=null olan topic ler
			$descendant = TopicFixtures::getRandomDescendant();
			$thread->setTopic($manager->merge($this->getReference('aripdforum_topic-' . $descendant[0])));
			$manager->persist($thread);
			
			foreach (range(1, rand(10, 40)) as $value) {
				
				$entity = new Post();
				$entity->setTopic($thread->getTopic());
				$entity->setThread($thread);
				$entity->setContent(ARIPDString::generateLoremIpsum(rand(1, 8)));
				if ($value == 1) {
					$entity->setUser($thread->getUser());
				}
				else {
					$entity->setUser($manager->merge($this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)))));
				}
				
				$manager->persist($entity);
				
			}

		}
		
		$manager->flush();

	}

	public function getDependencies() {
		return array(
				'ARIPD\UserBundle\DataFixtures\ORM\UserFixtures',
				'ARIPD\ForumBundle\DataFixtures\ORM\TopicFixtures',
		);
	}
	
}
