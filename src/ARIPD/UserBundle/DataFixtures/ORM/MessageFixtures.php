<?php
namespace ARIPD\UserBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Util\ARIPDString;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ARIPD\UserBundle\Entity\Message;

class MessageFixtures extends AbstractFixture implements DependentFixtureInterface {

	public static $NOFRecords = 300;
	
	public function load(ObjectManager $manager) {
		$statusProducer = array('P2' => 'Sent', 'P3' => 'Deleted');
		$statusConsumer = array('C1' => 'Unread', 'C2' => 'Read', 'C3' => 'Deleted');

		foreach (range(1, self::$NOFRecords) as $k => $value) {
			$entity = new Message();
			if ($value > 1) {
				$entity->setParent($this->getReference('aripduser_message-' . rand(1, $value - 1)));
			}
			$entity->setSubject('Message Subject ' . $value);
			$entity->setBody(ARIPDString::generateLoremIpsum(rand(1, 5)));
			$entity->setProducer($manager->merge($this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)))));
			$entity->setConsumer($manager->merge($this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)))));
			$entity->setStatusProducer(array_rand($statusProducer));
			$entity->setStatusConsumer(array_rand($statusConsumer));
			$entity->setIp(ARIPDString::getIP());

			$manager->persist($entity);

			$this->addReference('aripduser_message-' . $value, $entity);
		}

		$manager->flush();
	}

	public function getDependencies() {
		return array(
				'ARIPD\UserBundle\DataFixtures\ORM\UserFixtures',
		);
	}
	
}
