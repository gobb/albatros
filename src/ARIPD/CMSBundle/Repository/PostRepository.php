<?php
namespace ARIPD\CMSBundle\Repository;
use SaadTazi\GChartBundle\DataTable\DataTable;

use Doctrine\ORM\Query\ResultSetMapping;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository {
	
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
				cms_hit h
				WHERE h.post_id = :id
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

	public function getYears($locale) {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('year', 'year');
		
		$em = $this->getEntityManager();
		$objects = $em
		->createNativeQuery('
				SELECT
				YEAR(p.publishedAt) as year
				FROM cms_post p
				LEFT JOIN cms_topic t ON t.id = p.topic_id
				LEFT JOIN admin_iso639 i ON i.id = t.iso639_id
				WHERE p.approved = :approved
				AND i.a2 = :locale
				GROUP BY 1
				ORDER BY 1 DESC
				', $rsm)
		->setParameter('approved', true)
		->setParameter('locale', $locale)
		->getResult();
		
		return $objects;
	}
	
	public function getMonths($locale, $year) {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('month', 'month');
		
		$em = $this->getEntityManager();
		$objects = $em
		->createNativeQuery('
				SELECT
				MONTH(p.publishedAt) as month
				FROM cms_post p
				LEFT JOIN cms_topic t ON t.id = p.topic_id
				LEFT JOIN admin_iso639 i ON i.id = t.iso639_id
				WHERE p.approved = :approved
				AND i.a2 = :locale
				AND YEAR(p.publishedAt) = :year
				GROUP BY 1
				ORDER BY 1 DESC
				', $rsm)
		->setParameter('approved', true)
		->setParameter('locale', $locale)
		->setParameter('year', $year)
		->getResult();
		
		return $objects;
	}

	public function getPostsByYearAndMonth($locale, $year, $month) {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('id', 'id');
		$rsm->addScalarResult('slug', 'slug');
		$rsm->addScalarResult('name', 'name');
		$rsm->addScalarResult('publishedAt', 'publishedAt');
		
		$em = $this->getEntityManager();
		$objects = $em
		->createNativeQuery('
				SELECT
				p.id as id,
				p.slug as slug,
				p.name as name,
				p.publishedAt as publishedAt
				FROM cms_post p
				LEFT JOIN cms_topic t ON t.id = p.topic_id
				LEFT JOIN admin_iso639 i ON i.id = t.iso639_id
				WHERE p.approved = :approved
				AND i.a2 = :locale
				AND YEAR(p.publishedAt) = :year
				AND MONTH(p.publishedAt) = :month
				ORDER BY p.publishedAt DESC
				', $rsm)
		->setParameter('approved', true)
		->setParameter('locale', $locale)
		->setParameter('year', $year)
		->setParameter('month', $month)
		->getResult();
		
		return $objects;
	}

	public function getPostsByHistory($locale) {
		$history = array();
		foreach ($this->getYears($locale) as $year) {
			foreach ($this->getMonths($locale, $year['year']) as $month) {
				$history[$year['year']][$month['month']] = $this->getPostsByYearAndMonth($locale, $year['year'], $month['month']);
			}
		}
		return $history;
	}
}
