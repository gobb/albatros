<?php
namespace ARIPD\UserBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\UserBundle\Entity\Image;
use ARIPD\UserBundle\Entity\Postaladdress;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use ARIPD\UserBundle\Entity\User;
use ARIPD\UserBundle\Entity\Group;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class UserFixtures extends AbstractFixture implements DependentFixtureInterface {
	public static $initials = array(
			array(
					'username'=>'cem', 
					'email'=>'cem@aripd.com', 
					'password'=>'cem', 
					'firstname'=>'Cem', 
					'lastname'=>'S.', 
					'mobile'=>'5337421059', 
					'gender'=>'M', 
					'dateofbirth'=>'1975-06-08', 
					'latitude'=>40.1553, 
					'longitude'=>26.4142, 
					'city'=>'ÇANAKKALE',
					'google'=>'116208106044128145116',
					'linkedin'=>null,
					'twitter'=>null,
					'facebook'=>null,
					'enabled'=>true,
			),
			array(
					'username'=>'bilgi', 
					'email'=>'bilgi@aripd.com', 
					'password'=>'bilgi', 
					'firstname'=>'ARI', 
					'lastname'=>'PD', 
					'mobile'=>'1112223344', 
					'gender'=>'M', 
					'dateofbirth'=>'1975-06-08', 
					'latitude'=>36.8927, 
					'longitude'=>30.7095, 
					'city'=>'ANTALYA',
					'google'=>null,
					'linkedin'=>null,
					'twitter'=>null,
					'facebook'=>null,
					'enabled'=>true,
			),
			array('username'=>'user1', 'email'=>'user1@domain.tld', 'password'=>'user1', 'firstname'=>null, 'lastname'=>null, 'mobile'=>null, 'gender'=>null, 'dateofbirth'=>null, 'latitude'=>0, 'longitude'=>0, 'city'=>null, 'google'=>null, 'linkedin'=>null, 'twitter'=>null, 'facebook'=>null, 'enabled'=>true,),
			array('username'=>'user2', 'email'=>'user2@domain.tld', 'password'=>'user2', 'firstname'=>null, 'lastname'=>null, 'mobile'=>null, 'gender'=>null, 'dateofbirth'=>null, 'latitude'=>0, 'longitude'=>0, 'city'=>null, 'google'=>null, 'linkedin'=>null, 'twitter'=>null, 'facebook'=>null, 'enabled'=>true,),
			array('username'=>'user3', 'email'=>'user3@domain.tld', 'password'=>'user3', 'firstname'=>null, 'lastname'=>null, 'mobile'=>null, 'gender'=>null, 'dateofbirth'=>null, 'latitude'=>0, 'longitude'=>0, 'city'=>null, 'google'=>null, 'linkedin'=>null, 'twitter'=>null, 'facebook'=>null, 'enabled'=>true,),
			array('username'=>'user4', 'email'=>'user4@domain.tld', 'password'=>'user4', 'firstname'=>null, 'lastname'=>null, 'mobile'=>null, 'gender'=>null, 'dateofbirth'=>null, 'latitude'=>0, 'longitude'=>0, 'city'=>null, 'google'=>null, 'linkedin'=>null, 'twitter'=>null, 'facebook'=>null, 'enabled'=>true,),
			array('username'=>'user5', 'email'=>'user5@domain.tld', 'password'=>'user5', 'firstname'=>null, 'lastname'=>null, 'mobile'=>null, 'gender'=>null, 'dateofbirth'=>null, 'latitude'=>0, 'longitude'=>0, 'city'=>null, 'google'=>null, 'linkedin'=>null, 'twitter'=>null, 'facebook'=>null, 'enabled'=>true,),
			array('username'=>'user6', 'email'=>'user6@domain.tld', 'password'=>'user6', 'firstname'=>null, 'lastname'=>null, 'mobile'=>null, 'gender'=>null, 'dateofbirth'=>null, 'latitude'=>0, 'longitude'=>0, 'city'=>null, 'google'=>null, 'linkedin'=>null, 'twitter'=>null, 'facebook'=>null, 'enabled'=>true,),
			array('username'=>'user7', 'email'=>'user7@domain.tld', 'password'=>'user7', 'firstname'=>null, 'lastname'=>null, 'mobile'=>null, 'gender'=>null, 'dateofbirth'=>null, 'latitude'=>0, 'longitude'=>0, 'city'=>null, 'google'=>null, 'linkedin'=>null, 'twitter'=>null, 'facebook'=>null, 'enabled'=>true,),
			array('username'=>'user8', 'email'=>'user8@domain.tld', 'password'=>'user8', 'firstname'=>null, 'lastname'=>null, 'mobile'=>null, 'gender'=>null, 'dateofbirth'=>null, 'latitude'=>0, 'longitude'=>0, 'city'=>null, 'google'=>null, 'linkedin'=>null, 'twitter'=>null, 'facebook'=>null, 'enabled'=>true,),
	);
	
	public function load(ObjectManager $manager) {
		
		foreach (self::$initials as $k=>$initial) {
			$entity = new User();
			$entity->setUsername($initial['username']);
			$entity->setEmail($initial['email']);
			$entity->setPlainPassword($initial['password']);
			$entity->setFirstname($initial['firstname']);
			$entity->setLastname($initial['lastname']);
			$entity->setMobile($initial['mobile']);
			$entity->setGender($initial['gender']);
			$entity->setDateofbirth(new \DateTime($initial['dateofbirth']));
			
			$entity->setMotto(ARIPDString::motto());
			$entity->setLinkedin($initial['linkedin']);
			$entity->setGoogle($initial['google']);
			$entity->setTwitterUsername($initial['twitter']);
			$entity->setFacebookId($initial['facebook']);
			$entity->setLatitude($initial['latitude']);
			$entity->setLongitude($initial['longitude']);
			$entity->setEnabled($initial['enabled']);
			
			$encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
			$password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
			$entity->setPassword($password);
			
			if (in_array($entity->getUsername(), array('cem'))) {
				$entity->addGroup($manager->merge($this->getReference('aripduser_group-L1')));
			}
			elseif (in_array($entity->getUsername(), array('bilgi'))) {
				$entity->addGroup($manager->merge($this->getReference('aripduser_group-L2')));
			}
			else {
				$entity->addGroup($manager->merge($this->getReference('aripduser_group-L5')));
			}
			
			$manager->persist($entity);
			$this->addReference('aripduser_user-' . ($k+1), $entity);
			
			
			foreach (range(1, rand(1, count(UserFixtures::$initials))) as $i=>$v_image) {
				$image = new Image();
				$image->setName('image');
				$image->setPath('user_image_'.$v_image.'.jpg');
				$image->setUser($entity);
				$manager->persist($image);
			
				if ($i == rand(1,count(UserFixtures::$initials))) {
					$entity->setDefaultimage($image);
					$manager->persist($entity);
				}
			
			}
			
			foreach ( range(1, rand(1, ($k+1))) as $v_friend ) {
				$entity->addMyFriend($manager->merge($this->getReference('aripduser_user-'.$v_friend)));
			}
			$manager->persist($entity);
			
		}
		
		$manager->flush();
		
	}

	public function getDependencies() {
		return array(
				'ARIPD\UserBundle\DataFixtures\ORM\GroupFixtures',
		);
	}
	
}
