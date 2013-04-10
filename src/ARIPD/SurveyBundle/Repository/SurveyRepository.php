<?php
namespace ARIPD\SurveyBundle\Repository;
use SaadTazi\GChartBundle\DataTable\DataTable;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;

class SurveyRepository extends EntityRepository {

	public function getReportData($id) {

		$em = $this->getManager();

		$entity = $em->getRepository('ARIPDSurveyBundle:Survey')->find($id);

		$arr = array();
		foreach ($entity->getQuestions() as $question) {
			$data = $this->getData($question->getId());

			$dataTable = new DataTable();
			$dataTable->addColumn('id1', 'Cevap', 'string');
			$dataTable->addColumn('id2', 'Oy adedi', 'number');
			foreach ($data as $v) {
				$dataTable
						->addRow(
								array(array('v' => $v['answer']),
										array('v' => intval($v['result']),
												'f' => $v['result'] . ' oy'),));
			}

			$arr['dataTable' . $question->getId()] = $dataTable->toArray();
		}

		return $arr;
	}

	private function getData($id) {
		$em = $this->getManager();

		return $em->getRepository('ARIPDSurveyBundle:Result')
				->createQueryBuilder('r')
				->select(array('a.name answer', 'COUNT(r.id) result'))
				->where('r.question = ?1')->setParameter(1, $id)
				->leftJoin('r.answer', 'a')->groupBy('r.answer')->getQuery()
				->getResult();
	}

}
