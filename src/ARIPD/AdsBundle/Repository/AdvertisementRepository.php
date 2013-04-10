<?php
namespace ARIPD\AdsBundle\Repository;
use SaadTazi\GChartBundle\DataTable\DataTable;

use Doctrine\ORM\Query\ResultSetMapping;

use Doctrine\ORM\EntityRepository;

class AdvertisementRepository extends EntityRepository {
	
	public function getViewReportData($id) {
	
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('date', 'date');
		$rsm->addScalarResult('nofviews', 'views');
		$rsm->addScalarResult('nofuniqueviews', 'uniqueviews');
	
		$em = $this->getEntityManager();
		$objects = $em
		->createNativeQuery('
				SELECT
				DATE(v.createdAt) as date,
				COUNT(v.id) as nofviews,
				COUNT(DISTINCT v.sessionId) as nofuniqueviews
				FROM
				ads_view v
				WHERE
				v.advertisement_id = :id
				GROUP BY DATE(v.createdAt)
				', $rsm)->setParameter('id', $id)->getResult();
	
		$dataTable = new DataTable();
		$dataTable->addColumn('id1', 'Tarih', 'string');
		$dataTable->addColumn('id2', 'Views', 'number');
		$dataTable->addColumn('id2', 'Unique views', 'number');
		foreach ($objects as $object) {
			$dataTable->addRow(
					array(
							array('v' => $object['date']),
							array('v' => intval($object['views']), 'f' => $object['views'] . ' adet gösterim'),
							array('v' => intval($object['uniqueviews']), 'f' => $object['uniqueviews'] . ' adet tek gösterim'),
					)
			);
		}
		
		return $dataTable->toArray();
	}
	
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
				ads_hit h
				WHERE
				h.advertisement_id = :id
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
