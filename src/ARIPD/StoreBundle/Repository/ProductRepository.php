<?php
namespace ARIPD\StoreBundle\Repository;
use SaadTazi\GChartBundle\DataTable\DataTable;

use Doctrine\ORM\Query\ResultSetMapping;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository {
	
	public function getHitReportData($id) {
	
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('date', 'date');
		$rsm->addScalarResult('nofhits', 'hits');
		$rsm->addScalarResult('nofuniquehits', 'uniquehits');
		
		$em = $this->getEntityManager();
		$objects = $em
		->createNativeQuery('
				SELECT
				DATE(h.createdAt) as date,
				COUNT(h.id) as nofhits,
				COUNT(DISTINCT h.sessionId) as nofuniquehits
				FROM
				store_hit h
				WHERE
				h.product_id = :id
				GROUP BY DATE(h.createdAt)
				', $rsm)->setParameter('id', $id)->getResult();
	
		$dataTable = new DataTable();
		$dataTable->addColumn('id1', 'Tarih', 'string');
		$dataTable->addColumn('id2', 'Hits', 'number');
		$dataTable->addColumn('id2', 'Unique hits', 'number');
		foreach ($objects as $object) {
			$dataTable->addRow(
					array(
							array('v' => $object['date']),
							array('v' => intval($object['hits']), 'f' => $object['hits'] . ' adet tıklama'),
							array('v' => intval($object['uniquehits']), 'f' => $object['uniquehits'] . ' adet tek tıklama'),
					)
			);
		}
		
		return $dataTable->toArray();
	}

}
