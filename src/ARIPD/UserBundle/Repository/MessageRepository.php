<?php
namespace ARIPD\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository {
	public function getMessagesByConsumerExceptC3($consumer_id) {
		return $this->createQueryBuilder('m')->select('m')
				->where('m.consumer = ?1')->andWhere('m.statusConsumer != ?2')
				->setParameter(1, $consumer_id)->setParameter(2, 'C3')
				->addOrderBy('m.createdAt', 'DESC')->getQuery()->getResult();
	}

	public function updateStatusConsumer($consumer_id, $id, $status) {
		return $this->getManager()
				->createQuery(
						"UPDATE ARIPD\UserBundle\Entity\Message m SET m.statusConsumer= ?3 WHERE m.consumer =?1 AND m.id = ?2")
				->setParameter(1, $consumer_id,
						\Doctrine\DBAL\Types\Type::INTEGER)
				->setParameter(2, $id, \Doctrine\DBAL\Types\Type::INTEGER)
				->setParameter(3, $status, \Doctrine\DBAL\Types\Type::STRING)
				->execute();
	}

	public function updateStatusProducer($producer_id, $id, $status) {
		return $this->getManager()
				->createQuery(
						"UPDATE ARIPD\UserBundle\Entity\Message m SET m.statusProducer= ?3 WHERE m.producer =?1 AND m.id = ?2")
				->setParameter(1, $producer_id,
						\Doctrine\DBAL\Types\Type::INTEGER)
				->setParameter(2, $id, \Doctrine\DBAL\Types\Type::INTEGER)
				->setParameter(3, $status, \Doctrine\DBAL\Types\Type::STRING)
				->execute();
	}

	public function setAsRead($consumer_id, $id) {
		$messsage = $this->find($id);
		if ($messsage->getStatusConsumer() == 'C1')
			$this->updateStatusConsumer($consumer_id, $id, 'C2');
	}

	public function deleteFake($mode, $user_id, $id) {
		if ($mode == 'consumer') {
			return $this->updateStatusConsumer($user_id, $id, 'C3');
		} elseif ($mode == 'producer') {
			return $this->updateStatusProducer($user_id, $id, 'P3');
		}
	}

}
