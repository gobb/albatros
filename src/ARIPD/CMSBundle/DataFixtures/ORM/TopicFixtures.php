<?php
namespace ARIPD\CMSBundle\DataFixtures\ORM;
use ARIPD\CMSBundle\Entity\Subtopic;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures;
use ARIPD\UserBundle\DataFixtures\ORM\GroupFixtures;
use ARIPD\CMSBundle\Entity\Topic;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TopicFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public static $initials = array(
			array('XL Moda', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Büyük Markalar'),
				array('Tasarım'),
				array('Trend'),
				array('Aksesuar'),
			)),
			array('Güncel', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Haber'),
				array('Röportaj'),
			)),
			array('XL Güzellik', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Cilt Bakımı'),
				array('Saç Bakımı'),
				array('Güzel Yaş Alanlar'),
				array('Makyaj'),
				array('Balonun Kraliçesi'),
			)),
			array('Sağlık', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Spor'),
				array('Sağlık'),
			)),
			array('Lezzet', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Mekanlar'),
				array('Kiler'),
				array('Raftakiler'),
				array('Gurme'),
			)),
			array('Kültür-Sanat', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Köşedeki Kitapçı'),
				array('Ne yapsak?'),
				array('Vizyondakiler'),
				array('Müzik'),
			)),
			array('Aşk-Meşk', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Aşk-Meşk'),
			)),
			array('Hayata Dair', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Ev & Dekorasyon'),
				array('Teknoloji'),
				array('Gez Toz'),
				array('Aktivistin Ajandası'),
				array('... dahası'),
				array('Anne & Çocuk'),
				array('Alternatif Düşünce'),
			)),
			array('Magazin', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Magazin'),
			)),
			array('Yazarlar', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Deniz Akgündüz'),
				array('Ekin Türkantos'),
				array('Esra Erdoğan'),
				array('Forever 30'),
				array('Gül Kireklo'),
				array('Hülya Ünlü Mavigözlü'),
				array('Melda Erçelikcan'),
				array('Melis Kori'),
				array('Nermin Gönen'),
				array('Neşe Sayles'),
				array('Nurcan Eroğluer'),
				array('Ömer Boran'),
				array('Sevinç Akyazılı'),
				array('Sibel Aral'),
				array('Songül Gümüş'),
				array('Yudum Karaboğa'),
			)),
			array('Astroloji', null, 'show_t1.html.twig', 'show_t1.html.twig', false, array(
				array('Koç'),
				array('Boğa'),
				array('İkizler'),
				array('Yengeç'),
				array('Aslan'),
				array('Başak'),
				array('Terazi'),
				array('Akrep'),
				array('Yay'),
				array('Oğlak'),
				array('Kova'),
				array('Balık'),
			)),
			array('Ne Giysek', 'cms_topic_ne-giysek.jpg', 'show_t2.html.twig', 'show_t2.html.twig', true, array(
					array('Ne Giysek'),
			)),
			array('Ne Pişirsek', 'cms_topic_ne-pisirsek.jpg', 'show_t2.html.twig', 'show_t2.html.twig', true, array(
					array('Ne Pişirsek'),
			)),
			array('Günün Videosu', 'cms_topic_gunun-videosu.jpg', 'show_t2.html.twig', 'show_t2.html.twig', true, array(
					array('Günün Videosu'),
			)),
			array('Günün Fotosu', 'cms_topic_gunun-fotosu.jpg', 'show_t2.html.twig', 'show_t2.html.twig', true, array(
					array('Günün Fotosu'),
			)),
	);

	public static function getRandom() {
		$rand = self::$initials;
		return $rand[array_rand($rand)];
	}
	
	public function load(ObjectManager $manager) {
		foreach ( self::$initials as $initial ) {
			$entity = new Topic();
			$entity->setIso639($manager->merge($this->getReference('aripdadmin_iso639-' . rand(1, count(Iso639Fixtures::$iso639s)))));
			$entity->setName($initial[0]);
			$entity->setPath($initial[1]);
			$entity->setTemplateTopic($initial[2]);
			$entity->setTemplatePost($initial[3]);
			$entity->setHidden($initial[4]);
			
			foreach ( GroupFixtures::getApprovers() as $gv ) {
				$entity->addGroup($manager->merge($this->getReference('aripduser_group-'.$gv[0])));
			}
			
			$manager->persist($entity);
			$this->addReference('aripdcms_topic-' . ARIPDString::slugify($initial[0]), $entity);
			
			if ($initial[5]) {
				foreach ( $initial[5] as $v_subtopic ) {
					$subtopic = new Subtopic();
					$subtopic->setTopic($entity);
					$subtopic->setName($v_subtopic[0]);
					$this->addReference('aripdcms_subtopic-' . ARIPDString::slugify($v_subtopic[0]), $subtopic);
					$manager->persist($subtopic);
					
					$entity->addSubtopic($subtopic);
					$manager->persist($entity);
				}
			}
						
		}
		
		$manager->flush();
	}
	
	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures',
				'ARIPD\UserBundle\DataFixtures\ORM\GroupFixtures',
		);
	}
	
}
