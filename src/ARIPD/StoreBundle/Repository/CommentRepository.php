<?php
namespace ARIPD\StoreBundle\Repository;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository {
	public function getCommentsForProduct($productId, $approved = true) {
		$qb = $this->createQueryBuilder('c')->select('c')
				->where('c.product = :product_id')->addOrderBy('c.created')
				->setParameter('product_id', $productId);

		if (false === is_null($approved))
			$qb->andWhere('c.approved = :approved')
					->setParameter('approved', $approved);

		return $qb->getQuery()->getResult();
	}
}
