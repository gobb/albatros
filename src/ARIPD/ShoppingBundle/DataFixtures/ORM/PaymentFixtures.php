<?php
namespace ARIPD\ShoppingBundle\DataFixtures\ORM;
use ARIPD\ShoppingBundle\Entity\Paymentgrouptype;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\ShoppingBundle\Entity\Paymentgroup;
use ARIPD\ShoppingBundle\Entity\Payment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PaymentFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public static $initials = array(
			array(
					'mt',
					'Para Havalesi',
					'shopping_paymentgrouptype_mt.png',
					array(
							array(
									true,
									'Akbank',
									'
<p>Havale ile ödemenizden dolayı %5 indirimden yararlanacaksınız.</p>
<p>AKBANK HASIRCILAR ŞUBESİ, Şube Kodu: 0065, Hesap Numarası: 0031514, IBAN: TR38 0004 6000 6588 8000 0315 14</p>
<p>Siparişinizi tamamlamak için lütfen devam tuşuna basıp sistem tarafından oluşturulan sipariş numaranızı kaydediniz. Para havalesini gerçekleştirirken siparişinizin daha kolay takibi için açıklama kısmına bu sipariş numarasını yazmayı unutmayın.</p>
									',
									'shopping_paymentgroup_mt_akbank.png',
									'',//gate1
									'',//gate2
									'',//mid
									'',//tid
									'',//posid
									'',//clientid
									'',//storetype
									'',//storekey
									array(
											array('Tek ödeme', 1, '', -.05,),
									),
							),
							array(
									true,
									'Garanti Bankası',
									'
<p>Havale ile ödemenizden dolayı %5 indirimden yararlanacaksınız.</p>
<p>GARANTİ BANKASI BAHÇEKAPI ŞUBESİ, Şube Kodu: 0086, Hesap Numarası: 6297589, IBAN: TR83 0006 2000 0860 0006 2975 89</p>
<p>Siparişinizi tamamlamak için lütfen devam tuşuna basıp sistem tarafından oluşturulan sipariş numaranızı kaydediniz. Para havalesini gerçekleştirirken siparişinizin daha kolay takibi için açıklama kısmına bu sipariş numarasını yazmayı unutmayın.</p>
									',
									'shopping_paymentgroup_mt_garanti.png',
									'',//gate1
									'',//gate2
									'',//mid
									'',//tid
									'',//posid
									'',//clientid
									'',//storetype
									'',//storekey
									array(
											array('Tek ödeme', 1, '', -.05,),
									),
							),
					),
			),
			array(
					'pd',
					'Teslimatta Ödeme',
					'shopping_paymentgrouptype_pd.png',
					array(
							array(
									true,
									'Kapıda Ödeme',
//								'Size sunduğumuz diğer ödeme seçeneklerinin yanı sıra; dilerseniz 100 TL – 5.000 TL arasında vereceğiniz siparişlerde kapıda nakit ödemeyi tercih edebilir; Türkiye’nin neresinde olursanız olun, evinizin rahatlığında alışverişinizi tamamlayabilirsiniz. Lütfen devam tuşuna basıp sipariş numaranızı kaydediniz. Ürününüzü teslim alırken sipariş numarasını kontrol etmeyi unutmayın.',
									'
<p>Kapıda nakit ödemeyi tercih ettiniz. Türkiye’nin neresinde olursanız olun, evinizin rahatlığında alışverişinizi tamamlayabilirsiniz. Lütfen devam tuşuna basıp sipariş numaranızı kaydediniz. Ürününüzü teslim alırken sipariş numarasını kontrol etmeyi unutmayın.</p>
									',
									'shopping_paymentgroup_pd_kapi.png',
									'',//gate1
									'',//gate2
									'',//mid
									'',//tid
									'',//posid
									'',//clientid
									'',//storetype
									'',//storekey
									array(
											array('Tek ödeme', 1, '', 0,),
									),
							),
					),
			),
			array(
					'cc',
					'Kredi Kartı',
					'shopping_paymentgrouptype_cc.png',
					array(
							array(
									false,
									'Yapı Kredi Worldcard',
									'Lütfen kredi kartı bilgilerini girip devam tuşuna basın.',
									'shopping_paymentgroup_cc_world.png',
									'https://www.posnet.ykb.com/PosnetWebService/XML',
									'https://www.posnet.ykb.com/3DSWebService/YKBPaymentService',
									'',//mid
									'',//tid
									'',//posid
									'',//clientid
									'',//storetype
									'',//storekey
									array(
											array('Tek ödeme', 1, '1', 0,),
											array('2 taksit', 2, '2', .02,),
											array('3 taksit', 3, '3', .03,),
											array('4 taksit', 4, '4', .04,),
											array('5 taksit', 5, '5', .05,),
											array('6 taksit', 6, '6', .06,),
									),
							),
							array(
									false,
									'Garanti Bonus',
									'Lütfen kredi kartı bilgilerini girip devam tuşuna basın.',
									'shopping_paymentgroup_cc_bonus.png',
									'',
									'https://sanalposprov.garanti.com.tr/servlet/gt3dengine',
									'',//mid
									'',//tid
									'',//posid
									'',//clientid
									'3d_pay',//storetype
									'',//storekey
									array(
											array('Tek ödeme', 1, '1', 0,),
											array('2 taksit', 2, '2', .02,),
											array('3 taksit', 3, '3', .03,),
											array('4 taksit', 4, '4', .04,),
											array('5 taksit', 5, '5', .05,),
											array('6 taksit', 6, '6', .06,),
									),
							),
							array(
									true,
									'Akbank Axess',
									'Lütfen kredi kartı bilgilerini girip devam tuşuna basın.',
									'shopping_paymentgroup_cc_axess.png',
									'',//gate1
									'https://testsanalpos.est.com.tr/servlet/est3Dgate',//gate2
									'',//mid
									'',//tid
									'',//posid
									'100200000',//clientid
									'3d_pay',//storetype
									'123456',//storekey
									array(
											array('Tek ödeme', 1, '', 0,),
											array('2 taksit', 2, '2', .0286,),
											array('3 taksit', 3, '3', .0150,),
											array('4 taksit', 4, '4', .0444,),
											array('5 taksit', 5, '5', .0523,),
											array('6 taksit', 6, '6', .0099,),
											array('7 taksit', 7, '7', .0680,),
											array('8 taksit', 8, '8', .0759,),
											array('9 taksit', 9, '9', .0838,),
											array('10 taksit', 10, '10', .0916,),
											array('11 taksit', 11, '11', .0995,),
											array('12 taksit', 12, '12', .0550,),
									),
							),
							array(
									true,
									'Diğer Kredi Kartları',
									'Lütfen kredi kartı bilgilerini girip devam tuşuna basın.',
									'shopping_paymentgroup_cc_others.png',
									'',//gate1
									'https://testsanalpos.est.com.tr/servlet/est3Dgate',//gate2
									'',//mid
									'',//tid
									'',//posid
									'100200000',//clientid
									'3d_pay',//storetype
									'123456',//storekey
									array(
											array('Tek ödeme', 1, '', 0,),
									),
							),
					),
			),
	);
	
	public function load(ObjectManager $manager) {
		
		foreach ( self::$initials as $initial ) {
			$entity = new Paymentgrouptype();
			$entity->setCode($initial[0]);
			$entity->setName($initial[1]);
			$entity->setPath($initial[2]);
			
			foreach ( $initial[3] as $k_paymentgroup => $v_paymentgroup ) {
				$paymentgroup = new Paymentgroup();
				$paymentgroup->setActive($v_paymentgroup[0]);
				$paymentgroup->setName($v_paymentgroup[1]);
				$paymentgroup->setIso4217($manager->merge($this->getReference('aripdadmin_iso4217-trl')));
				$paymentgroup->setContent($v_paymentgroup[2]);
				$paymentgroup->setPath($v_paymentgroup[3]);
				
				$paymentgroup->setGate1($v_paymentgroup[4]);
				$paymentgroup->setGate2($v_paymentgroup[5]);
				$paymentgroup->setMid($v_paymentgroup[6]);
				$paymentgroup->setTid($v_paymentgroup[7]);
				$paymentgroup->setPosid($v_paymentgroup[8]);
				$paymentgroup->setClientid($v_paymentgroup[9]);
				$paymentgroup->setStoretype($v_paymentgroup[10]);
				$paymentgroup->setStorekey($v_paymentgroup[11]);
				
				foreach ( $v_paymentgroup[12] as $v_payment ) {
					$payment = new Payment();
					$payment->setPaymentgroup($paymentgroup);
					$payment->setName($v_payment[0]);
					$payment->setPeriod($v_payment[1]);
					$payment->setParameter($v_payment[2]);
					$payment->setImpactRate($v_payment[3]);
					$payment->setImpactPrice(0);
					$payment->setIso4217($manager->merge($this->getReference('aripdadmin_iso4217-trl')));
					
					$manager->persist($payment);
				}
				
				$transportation = TransportationFixtures::getRandom();
				$paymentgroup->addTransportation($manager->merge($this->getReference('aripdshopping_transportation-' . ARIPDString::slugify($transportation[0]))));
				
				$paymentgroup->setPaymentgrouptype($entity);
				
				$manager->persist($paymentgroup);
			}
			
			$manager->persist($entity);
			
		}

		$manager->flush();

	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso4217Fixtures',
				'ARIPD\ShoppingBundle\DataFixtures\ORM\TransportationFixtures',
		);
	}
		
}
