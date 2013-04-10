<?php
namespace ARIPD\AdsBundle\DataFixtures\ORM;
use ARIPD\DefaultBundle\DataFixtures\ORM\BannerFixtures;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\AdsBundle\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use ARIPD\AdsBundle\Entity\Advertisement;

class AdvertisementFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public static $initials = array(
			array('Leaderboard', 'Adidas', 'http://www.adidas.com', 'ads_advertisement_1.jpg', array('adidas', 'spor ayakkabı', 'tayt', 'çorap', 'body', 'futbol')),
			array('Square Pop-Up', 'Adidas Watch', 'http://www.adidas.com', 'ads_advertisement_2.jpg', array('adidas', 'watch')),
			array('Square Pop-Up', 'Audi', 'http://www.audi.com', 'ads_advertisement_3.jpg', array('audi', 'spor otomobil', 'binek otomobil', 'A5')),
			array('Square Pop-Up', 'BMW', 'http://www.bmw.com', 'ads_advertisement_4.jpg', array('bmw', 'spor otomobil', 'Z3', 'Z4')),
			array('Square Pop-Up', 'Mercedes', 'http://www.mercedes.com', 'ads_advertisement_5.jpg', array('mercedes', 'sedan otomobil', 'binek otomobil', 'S Serisi')),
			array('Leaderboard', 'Nike Football', 'http://www.nike.com', 'ads_advertisement_6.jpg', array('nike', 'spor ayakkabı', 'basketbol', 'body')),
			array('Square Pop-Up', 'Penti', 'http://www.penti.com', 'ads_advertisement_7.jpg', array('penti', 'çorap', 'ten rengi', 'den')),
			array('Square Pop-Up', 'Nike', 'http://www.nike.com', 'ads_advertisement_8.jpg', array('nike', 'spor ayakkabı', 'basketbol', 'body')),
			array('Leaderboard', 'Nike Just Do It', 'http://www.nike.com', 'ads_advertisement_9.jpg', array('nike', 'spor ayakkabı', 'basketbol', 'body')),
			array('Leaderboard', 'Nike Freestyle', 'http://www.nike.com', 'ads_advertisement_10.jpg', array('nike', 'spor ayakkabı', 'basketbol')),
			array('Square Pop-Up', 'Penti swf', 'http://www.penti.com', 'ads_advertisement_11.swf', array('penti', 'çorap', 'ten rengi', 'den')),
	);

	public function load(ObjectManager $manager) {
		
		$starting = new \DateTime();
		$ending = new \DateTime();
		
		foreach ( self::$initials as $k=>$initial ) {
			$entity = new Advertisement();
			$entity->setName($initial[1]);
			$entity->setHref($initial[2]);
			$entity->setPath($initial[3]);
			$entity->setStartingAt($starting);
			$entity->setEndingAt($ending->add(new \DateInterval("P1DT2H")));
			foreach ($initial[4] as $v_tag) {
				$tag = new Tag();
				$tag->setName($v_tag);
				
				$entity->addTag($tag);
			}
			
			$entity->setBanner($manager->merge($this->getReference('aripdadmin_banner-' . ARIPDString::slugify($initial[0]))));
			
			$manager->persist($entity);
			$this->addReference('aripdads_advertisement-'.($k+1), $entity);
			
		}

		$manager->flush();

	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\BannerFixtures',
		);
	}
	
}
