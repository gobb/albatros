<?php
namespace ARIPD\ShoppingBundle\Repository;
use Doctrine\ORM\EntityRepository;

class VoucherRepository extends EntityRepository {
	
	public function isAvailable($code) {
		return $this->createQueryBuilder('v')
				->where('v.code = ?1')
				->setParameter(1, $code)
				->andWhere('?2 BETWEEN v.startingAt AND v.endingAt')
				->setParameter(2, new \DateTime())
				->getQuery()->getOneOrNullResult();
	}
	
}
