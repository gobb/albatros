<?php
namespace ARIPD\CMSBundle\DataFixtures\ORM;

use ARIPD\SurveyBundle\DataFixtures\ORM\SurveyFixtures;
use ARIPD\UserBundle\DataFixtures\ORM\UserFixtures;
use ARIPD\CMSBundle\Entity\Image;
use ARIPD\CMSBundle\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use ARIPD\CMSBundle\Entity\Post;
use ARIPD\CMSBundle\Entity\Topic;
use ARIPD\AdminBundle\Util\ARIPDString;

class PostFixtures extends AbstractFixture implements DependentFixtureInterface {
		
	public static $NOFRecords = 100;
	
	public static $NOFImages = 15;
	
	public static $tags = array(
			'hayat',
			'moda',
			'demode',
			'otomobil',
			'spor',
			'yaşam',
			'hayat',
			'etiler',
			'nişantaşı',
			'marka',
			'giyim',
	);
	
	public static $videos = array(
			null,
			'http://www.youtube.com/watch?v=R55e-uHQna0',
			'http://www.youtube.com/watch?v=_V8ZAdyEBBw',
			'http://www.youtube.com/watch?v=5tAIcwuoIcI',
			'http://www.youtube.com/watch?v=4N-KpsFyV9Y',
			'http://www.youtube.com/watch?v=Bg04-utuC0s',
			'http://vimeo.com/37612765',
			'http://vimeo.com/17188504',
			'http://vimeo.com/3114617',
			'http://vimeo.com/24492485',
			'http://vimeo.com/16285298',
			'http://vimeo.com/7235817',
			'http://vimeo.com/24515353',
	);
	
	public function load(ObjectManager $manager) {
		
		$videos = self::$videos;
		
		foreach (range(1, self::$NOFRecords) as $value) {

			$date = new \DateTime();
			
			$entity = new Post();
			$entity->setPublishedAt($date->modify('-'.($value*rand(1,5)*rand(1,60*60*60)).' seconds'));
			$entity->setApproved(rand(0, 1));
			$entity->setName('CMS Post ' . $value);
			$entity->setDescription(substr(ARIPDString::generateLoremIpsum(), 0, 50));
			$entity->setContent(ARIPDString::generateLoremIpsum(rand(5, 8)));
			$entity->setSurvey($manager->merge($this->getReference('aripdsurvey_survey-' . rand(1, SurveyFixtures::$NOFRecords))));
			$entity->setVideo($videos[rand(0, count($videos)-1)]);
			$entity->setUser($manager->merge($this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)))));
			
			$tags = self::$tags;
			foreach (range(1, rand(1, count($tags))) as $v_tag) {
				$tag = new Tag();
				$tag->setName($tags[$v_tag-1]);
				
				$entity->addTag($tag);
			}
			
			$topic = TopicFixtures::getRandom();
			$entity->setTopic($manager->merge($this->getReference('aripdcms_topic-' . ARIPDString::slugify($topic[0]))));
			
			$subtopic = $topic[5][array_rand($topic[5])];
			$entity->setSubtopic($manager->merge($this->getReference('aripdcms_subtopic-' . ARIPDString::slugify($subtopic[0]))));
			
			$manager->persist($entity);
			$this->addReference('aripdcms_post-' . $value, $entity);

			foreach (range(1, rand(1, self::$NOFImages)) as $i=>$v_image) {
				$image = new Image();
				$image->setName('image'.$v_image);
				$image->setDescription(substr(ARIPDString::generateLoremIpsum(), 0, 50));
				$image->setPath('cms_image_'.$v_image.'.jpg');
				$image->setPost($entity);
				$manager->persist($image);
				
				if ($i == rand(1, self::$NOFImages)) {
					$entity->setDefaultimage($image);
					$manager->persist($entity);
				}
				
			}
			
		}
		
		$manager->flush();

	}
	
	public function getDependencies() {
		return array(
				'ARIPD\SurveyBundle\DataFixtures\ORM\SurveyFixtures',
				'ARIPD\UserBundle\DataFixtures\ORM\UserFixtures',
				'ARIPD\CMSBundle\DataFixtures\ORM\TopicFixtures',
		);
	}
	
}
