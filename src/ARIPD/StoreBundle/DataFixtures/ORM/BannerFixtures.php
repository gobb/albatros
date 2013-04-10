<?php
namespace ARIPD\StoreBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures;
use ARIPD\AdminBundle\Util\ARIPDString;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use ARIPD\StoreBundle\Entity\Banner;

class BannerFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	private $initials = array(
			array('Tüm Ürünlerde Açılışa Özel %20 İndirim', 'Şimdi satın alın indirimlerimizden yararlanın', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/banner/store_banner_1.png', null),
			array('Tüm Ürünlerde Açılışa Özel %20 İndirim', 'Şimdi satın alın indirimlerimizden yararlanın', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/banner/store_banner_2.png', null),
			array('Tüm Ürünlerde Açılışa Özel %20 İndirim', 'Şimdi satın alın indirimlerimizden yararlanın', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/banner/store_banner_3.png', null),
			array('Tüm Ürünlerde Açılışa Özel %20 İndirim', 'Şimdi satın alın indirimlerimizden yararlanın', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/banner/store_banner_4.png', null),
	);
	
	public function load(ObjectManager $manager) {
		foreach ( $this->initials as $initial) {
			
			$start = new \DateTime();
			$end = new \DateTime();
			
			$entity = new Banner();
			$entity->setIso639($manager->merge($this->getReference('aripdadmin_iso639-' . rand(1, count(Iso639Fixtures::$iso639s)))));
			$entity->setName($initial[0]);
			$entity->setDescription($initial[1]);
			$entity->setUrl($initial[2]);
			$entity->setHref($initial[3]);
			$entity->setStartingAt($start);
			$entity->setEndingAt($end->modify('+45 days'));
			$manager->persist($entity);
		}

		$manager->flush();
	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures',
		);
	}

}
