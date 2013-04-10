<?php
namespace ARIPD\AdminBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use ARIPD\AdminBundle\Entity\Mime;

class MimeFixtures extends AbstractFixture {
	
	public function load(ObjectManager $manager) {
		
		if (($handle = fopen(__DIR__ . DIRECTORY_SEPARATOR . "Mime.csv", "r")) !== FALSE) {
			
			while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE) {
		
				$entity = new Mime();
				$entity->setExtension($data[0]);
				$entity->setType($data[1]);
				$entity->setName($data[2]);
				
				$manager->persist($entity);
				
			}
			
			$manager->flush();
			
			fclose($handle);
			
		}
		
	}

}
