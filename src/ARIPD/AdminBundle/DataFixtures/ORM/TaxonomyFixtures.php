<?php
namespace ARIPD\AdminBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

use ARIPD\AdminBundle\Entity\Taxonomy;

class TaxonomyFixtures extends AbstractFixture {
	
	public function load(ObjectManager $manager) {
		$arrs = array(array('%8', .08), array('%18', .18), array('%20', .20),);

		foreach ($arrs as $arr) {
			$entity = new Taxonomy();
			$entity->setName($arr[0]);
			$entity->setRate($arr[1]);
			$manager->persist($entity);
			$this
					->addReference('aripdadmin_taxonomy-' . ($arr[1] * 100),
							$entity);
		}

		$manager->flush();
	}

}
