<?php
namespace ARIPD\ShoppingBundle\DataFixtures\ORM;
use ARIPD\AdminBundle\Util\ARIPDString;
use ARIPD\ShoppingBundle\Entity\Vouchercode;
use ARIPD\ShoppingBundle\Entity\Voucher;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VoucherFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	public function load(ObjectManager $manager) {
		
		foreach (range(1, 9) as $value) {
			
			$start = new \DateTime();
			$end = new \DateTime();
			
			$entity = new Voucher();
			$entity->setCode('C' . ARIPDString::randomUniqueString()); // Common voucher code
			$entity->setName('Voucher' . $value);
			$entity->setStartingAt($start);
			$entity->setEndingAt($end->modify('+60 days'));
			$entity->setImpactRate(-($value / 10));
			$entity->setImpactPrice(-($value * 10));
			$entity->setIso4217($manager->merge($this->getReference('aripdadmin_iso4217-trl')));
			
			$manager->persist($entity);
			$this->addReference('aripdshopping_voucher-' . $value, $entity);

			foreach (range(1, 10) as $r) {
				$vouchercode = new Vouchercode();
				$vouchercode->setVoucher($manager->merge($this->getReference('aripdshopping_voucher-' . $value)));
				$vouchercode->setVouchercode('U' . ARIPDString::randomUniqueString()); // Unique voucher code
				$manager->persist($vouchercode);
			}

		}

		$manager->flush();

	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso4217Fixtures',
		);
	}
		
}
