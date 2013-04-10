<?php
namespace ARIPD\AdminBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use ARIPD\AdminBundle\Entity\Bank;

class BankFixtures extends AbstractFixture {
	
	public function load(ObjectManager $manager) {
		
		if (($handle = fopen(__DIR__ . DIRECTORY_SEPARATOR . "Bank.csv", "r")) !== FALSE) {
			
			while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE) {
		
				$entity = new Bank();
				$entity->setCode($data[0]);
				$entity->setName($data[1]);
				
				$manager->persist($entity);
				
			}
			
			$manager->flush();
			
			fclose($handle);
			
		}
		
	}

}
