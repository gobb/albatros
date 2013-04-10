<?php
namespace ARIPD\StoreBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\StoreBundle\Entity\Attributevalue;
use ARIPD\StoreBundle\Entity\Attributekey;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class AttributeFixtures extends AbstractFixture {

	public static $initials = array(
		array(1, 'MODEL', 'Model', array(
				array('IPHONE3', 'iPhone3/3G/3GS'),
				array('IPHONE4', 'iPhone4G/4S'),
				array('IPHONE5', 'iPhone5'),
				array('IPAD2', 'iPad 2'),
				array('IPAD3', 'New iPad/iPad 3'),
				array('IPADMINI', 'Mini iPad'),
				array('IPOD4', 'iPod Touch 4'),
				array('IPOD5', 'iPod Touch 5'),
				array('SAMA5830', 'Samsung Ace 5830'),
				array('SAMW8150', 'Samsung Wonder 8150'),
				array('SAMGAS2', 'Samsung Galaxy S2'),
				array('SAMGAS3', 'Samsung Galaxy S3'),
				array('SAMGAS3MINI', 'Samsung Galaxy S3 Mini'),
				array('SAMGAT6800', 'Samsung Galaxy Tab 6800'),
				array('SAMGATP3100', 'Samsung Galaxy Tab 2 P3100'),
				array('SAMGATP1000', 'Samsung Galaxy Tab 7 (P1000)'),
				array('SAMGAT7510', 'Samsung Galaxy Tab 10.1 (7510/5100)'),
				array('SAMNOTEN8000', 'Samsung Note 10.1 (N8000)'),
				array('SAMNOTE9220', 'Samsung Note 1 (9220)'),
				array('SAMNOTE7100', 'Samsung Note 2 (7100)'),
		)),
		array(2, 'RENK', 'Renk', array(
				array('KR', 'Karışık'),
				array('SI', 'Siyah'),
				array('BE', 'Beyaz'),
				array('PE', 'Pembe'),
				array('KI', 'Kırmızı'),
				array('MA', 'Mavi'),
				array('LA', 'Lacivert'),
				array('GM', 'Gök mavisi'),
				array('SA', 'Sarı'),
				array('MO', 'Mor'),
				array('FUS', 'Fuşya'),
				array('VI', 'Vişne'),
				array('KA', 'Kahverengi'),
				array('KK', 'Koyu Kahve'),
				array('AK', 'Açık Kahve'),
				array('BR', 'Gri'),
				array('FUM', 'Füme'),
				array('YE', 'Yeşil'),
				array('TU', 'Turkuaz'),
		)),
		array(3, 'DESEN', 'Desen', array(
				array('DUZ', 'Yok / Düz Kapak'),
				array('TAS', 'Taşlı Kapak'),
				array('DES', 'Desenli Kapak'),
				array('TAD', 'Taşlı Desenli Kapak'),
				array('BUM', 'Bumpers / Sadece Çerçeve'),
		)),
		array(4, 'MALZ', 'Malzeme', array(
				array('YU', 'Yumuşak Kasa / Soft Case'),
				array('SE', 'Sert Kasa / Hard Case'),
				array('ME', 'Metal'),
				array('DE', 'Deri'),
				array('SD', 'Suni Deri'),
		)),
	);
	
	public function load(ObjectManager $manager) {
		
		foreach ( self::$initials as $initial ) {
			
			$entity = new Attributekey();
			$entity->setCode($initial[1]);
			$entity->setName($initial[2]);
			$manager->persist($entity);

			foreach ($initial[3] as $v_attributevalue) {
				$attributevalue = new Attributevalue();
				$attributevalue->setCode($v_attributevalue[0]);
				$attributevalue->setName($v_attributevalue[1]);
				$attributevalue->setAttributekey($entity);
				$manager->persist($attributevalue);
			}
			
		}

		$manager->flush();
		
	}

}
