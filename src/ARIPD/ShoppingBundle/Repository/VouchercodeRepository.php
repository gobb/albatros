<?php
namespace ARIPD\ShoppingBundle\Repository;
use Doctrine\ORM\EntityRepository;

class VouchercodeRepository extends EntityRepository {
	
	public function isAvailable($vouchercode) {
		return $this->createQueryBuilder('vc')
				->leftJoin('vc.voucher', 'v')
				->where('vc.vouchercode = ?1')
				->setParameter(1, $vouchercode)
				->andWhere('?2 BETWEEN v.startingAt AND v.endingAt')
				->setParameter(2, new \DateTime())
				->getQuery()->getOneOrNullResult();
	}
	
}
