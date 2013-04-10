<?php
namespace ARIPD\StoreBundle\DataFixtures\ORM;
use ARIPD\StoreBundle\Entity\Model;
use ARIPD\UserBundle\DataFixtures\ORM\UserFixtures;
use ARIPD\StoreBundle\Entity\Modelimage;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\StoreBundle\Entity\Tag;
use ARIPD\StoreBundle\Entity\Product;
use ARIPD\StoreBundle\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends AbstractFixture implements DependentFixtureInterface {

	public function getNOFModels() {
		$nof_models = 0;
		if (($handle = fopen(__DIR__ . DIRECTORY_SEPARATOR . "Product.csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE) {
				$nof_models += count(explode(",", $data[9]));
			}
		}
		return $nof_models;
	}
	
	public function load(ObjectManager $manager) {
		
		if (($handle = fopen(__DIR__ . DIRECTORY_SEPARATOR . "Product.csv", "r")) !== FALSE) {
			
			$kp = 1;
			$km = 1;
			while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE) {
		
				$entity = new Product();
				$entity->setPublishedAt(new \DateTime());
				$entity->setApproved(true);
				if ($kp < 3) {
					$entity->setVitrine(true);
				}
				if ($kp < 6) {
					$entity->setBrandnew(true);
				}
				$entity->setCode($data[0]);
				$entity->setBrand($manager->merge($this->getReference('aripdstore_brand-'.$data[1])));
				$entity->setName($data[3]);
				$entity->setDescription($data[11]);
				$entity->setContent($entity->getDescription());
				$entity->setTaxonomy($manager->merge($this->getReference('aripdadmin_taxonomy-18')));
				//$entity->setUser($manager->merge($this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)))));
				
				/**
				 * add categories
				 */
				foreach ( explode(",", $data[2]) as $k_category=>$v_category) {
					if ($k_category == 0) {
						$entity->setDefaultcategory($manager->merge($this->getReference('aripdstore_category-'.$v_category)));
					}
					$entity->addCategorie($manager->merge($this->getReference('aripdstore_category-'.$v_category)));
				}
				
				/**
				 * add models - manytoone
				 */
				foreach ( explode(",", $data[9]) as $k_model=>$v_model ) {
					$model = new Model();
					$model->setCode($v_model);
					$model->setPrice($data[6]);
					$model->setIso4217($manager->merge($this->getReference('aripdadmin_iso4217-'.strtolower($data[7]))));
					$model->setBulkPrice($data[4]);
					$model->setBulkIso4217($manager->merge($this->getReference('aripdadmin_iso4217-'.strtolower($data[5]))));
					if ($k_model == 0) {
						$model->setPrime(true);
					}
					$model->setProduct($entity);
					$manager->persist($model);
					
					$modelimage = new Modelimage();
					$modelimage->setModel($model);
					$modelimage->setPrime(true);
					$modelimage->setName($model->getCode());
					$modelimage->setDescription($model->getCode());
					$modelimage->setUrl(sprintf("http://dl.dropbox.com/u/36591931/pdc_com_tr/%s.png", $model->getCode()));
					$manager->persist($modelimage);
					
					$this->addReference('aripdstore_model-'.$km, $model);
					$km++;
				}
				
				/**
				 * add tags - manytomany
				 */
				foreach ( explode(",", $data[10]) as $v_tag ) {
					$tag = new Tag();
					$tag->setName($v_tag);
					$entity->addTag($tag);
				}
				
				/**
				 * add comments - manytoone
				 */
				foreach (range(1, 30) as $value) {
					$comment = new Comment();
					$comment->setApproved(rand(0, 1));
					$comment->setContent(ARIPDString::generateLoremIpsum());
					$comment->setProduct($entity);
					$comment->setUser($manager->merge($this->getReference('aripduser_user-' . rand(1, count(UserFixtures::$initials)))));
					$manager->persist($comment);
				}
				
				$manager->persist($entity);
				$this->addReference('aripdstore_product-'.$kp, $entity);
				
				$kp++;
			}
			
			$manager->flush();
			
			fclose($handle);
			
		}
		
	}
	
	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\TaxonomyFixtures',
				'ARIPD\StoreBundle\DataFixtures\ORM\CategoryFixtures',
				'ARIPD\StoreBundle\DataFixtures\ORM\BrandFixtures',
				'ARIPD\UserBundle\DataFixtures\ORM\UserFixtures',
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso4217Fixtures',
		);
	}
	
}
