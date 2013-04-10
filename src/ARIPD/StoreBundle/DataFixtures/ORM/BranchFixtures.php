<?php
namespace ARIPD\StoreBundle\DataFixtures\ORM;
use ARIPD\StoreBundle\Entity\Branchimage;
use ARIPD\AdminBundle\Util\ARIPDString;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use ARIPD\StoreBundle\Entity\Branch;

class BranchFixtures extends AbstractFixture {
	
	private $initials = array(
			array(1, 36.8927, 30.7095, 'ANTALYA', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/branch/1.png'),
			array(2, 40.1553, 26.4142, 'ÇANAKKALE', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/branch/2.png'),
			array(3, 36.8927, 30.7095, 'ANTALYA', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/branch/3.png'),
			array(4, 40.1962, 29.0750, 'BURSA', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/branch/4.png'),
			array(5, 37.8560, 27.8416, 'AYDIN', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/branch/5.png'),
			array(6, 39.1253, 27.1779, 'BERGAMA', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/branch/6.png'),
			array(7, 38.3938, 42.1232, 'BİTLİS', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/branch/7.png'),
			array(8, 39.8269, 26.0604, 'BOZCAADA', 'http://dl.dropbox.com/u/36591931/pdc_com_tr/branch/8.png'),
	);

	public function load(ObjectManager $manager) {
		foreach ($this->initials as $initial) {

			$entity = new Branch();
			$entity->setAddress('address' . $initial[0]);
			$entity->setCity($initial[3]);
			$entity->setLatitude($initial[1]);
			$entity->setLongitude($initial[2]);
			$entity->setName('Şube ' . $initial[3]);
			$entity->setPhone('phone' . $initial[0]);
			$entity->setState('state' . $initial[0]);
			$entity->setZip('zip' . $initial[0]);
			$entity->setContent(ARIPDString::generateLoremIpsum());

			$image = new Branchimage();
			$image->setUrl($initial[4]);
			$image->setName($entity->getName());
			$image->setDescription(ARIPDString::generateLoremIpsum());
			$image->setBranch($entity);
			$image->setPrime(true);
			$manager->persist($image);
			
			$this->addReference('aripdstore_branch-' . $initial[0], $entity);
			$manager->persist($entity);
		}

		$manager->flush();
	}

}
