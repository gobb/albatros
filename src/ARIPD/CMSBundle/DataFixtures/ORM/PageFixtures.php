<?php
namespace ARIPD\CMSBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\CMSBundle\Entity\Page;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PageFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public static $initials = array(
			array(1, null, 'Hakkımızda'),
			array(2, 1, 'Uzgörüş'),
			array(3, 1, 'Özgörev'),
			array(4, null, 'Haberler'),
			array(5, null, 'Yayınlar'),
			array(6, null, 'Ürünler'),
			array(7, null, 'Hizmetler'),
			array(8, null, 'İletişim'),
	);
	
	public function load(ObjectManager $manager) {
		foreach ( self::$initials as $k=>$initial) {
			$entity = new Page();
			$entity->setIso639($manager->merge($this->getReference('aripdadmin_iso639-'. rand(1, count(Iso639Fixtures::$iso639s)))));
			$entity->setSorting($initial[0]);
			$entity->setName($initial[2]);
			$entity->setDescription(ARIPDString::generateLoremIpsum());
			$entity->setContent(ARIPDString::generateLoremIpsum(rand(5,8)));
			if ($k == 0)
				$entity->setInitial(true);
			if ($initial[1] != null) {
				$entity->setParent($manager->merge($this->getReference('aripdcms_page-' . $initial[1])));
				$entity->setIso639($entity->getParent()->getIso639());
			}
			
			$manager->persist($entity);
			$this->addReference('aripdcms_page-' . $initial[0], $entity);
		}

		$manager->flush();
	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures',
		);
	}
	
}
