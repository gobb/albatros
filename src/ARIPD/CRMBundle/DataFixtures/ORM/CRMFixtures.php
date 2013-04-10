<?php
namespace ARIPD\CRMBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\CRMBundle\Entity\Image;
use ARIPD\CRMBundle\Entity\Tag;
use ARIPD\CRMBundle\Entity\Individual;
use ARIPD\CRMBundle\Entity\Tagkey;
use ARIPD\CRMBundle\Entity\Taggroup;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class CRMFixtures extends AbstractFixture {
	
	public static $initials = array(
			array('Sosyal Ağlar', array('Google', 'Facebook', 'Twitter', 'Linkedin')),
			array('Adres Bilgileri', array('Ev Adresi', 'İş Adresi')),
			array('Telefon Bilgileri', array('İş Telefonu', 'Ev Telefonu', 'Cep Telefonu', 'İş Faks')),
	);
	
	public function load(ObjectManager $manager) {

		foreach (self::$initials as $k_taggroup => $v_taggroup) {
			$taggroup = new Taggroup();
			$taggroup->setName($v_taggroup[0]);
			$manager->persist($taggroup);
			//$this->addReference('aripdcrm_taggroup-' . ($k_taggroup + 1), $taggroup);
			
			foreach ($v_taggroup[1] as $k_tagkey => $v_tagkey) {
				$tagkey = new Tagkey();
				$tagkey->setName($v_tagkey);
				$tagkey->setTaggroup($taggroup);
				$manager->persist($tagkey);
				
				foreach (range(1, 10) as $value) {
					$entity = new Individual();
					$entity->setFirstname('firstname-' . $value);
					$entity->setLastname('lastname-' . $value);
					$entity->setMiddlename('middlename-' . $value);
					foreach (range(1, 3) as $v_tag) {
						$tag = new Tag();
						$tag->setName('Tag Value' . $v_tag);
						$tag->setTagkey($tagkey);
						$entity->addTag($tag);
					}
					$manager->persist($entity);
					
					$image = new Image();
					$image->setName('image'.$value);
					$image->setDescription(ARIPDString::generateLoremIpsum());
					$image->setPath('crm_image_'.$value.'.jpg');
					$image->setIndividual($entity);
					$manager->persist($image);
					
					$entity->setDefaultimage($image);
					$manager->persist($entity);
					
				}
				
			}
			
		}

		$manager->flush();
	}

}
