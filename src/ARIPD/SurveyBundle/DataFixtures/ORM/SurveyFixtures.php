<?php
namespace ARIPD\SurveyBundle\DataFixtures\ORM;
use ARIPD\SurveyBundle\Entity\Answer;
use ARIPD\SurveyBundle\Entity\Question;
use ARIPD\SurveyBundle\Entity\Survey;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class SurveyFixtures extends AbstractFixture {
	
	public static $NOFRecords = 5;
	
	public function load(ObjectManager $manager) {
		
		foreach (range(1, self::$NOFRecords) as $vals) {
			
			$start = new \DateTime();
			$end = new \DateTime();
			
			$entity = new Survey();
			$entity->setName('survey' . $vals);
			$entity->setDescription('surveydescription' . $vals);
			$entity->setStartingAt($start);
			$entity->setEndingAt($end->modify('+30 days'));
			$manager->persist($entity);
			$this->addReference('aripdsurvey_survey-' . $vals, $entity);

			foreach (range(1, rand(1,5)) as $valq) {
				$question = new Question();
				$question->setName('question' . $vals . $valq);
				$question->setSurvey($entity);
				$manager->persist($question);

				foreach (range(1, rand(1,5)) as $vala) {
					$answer = new Answer();
					$answer->setName('answer' . $vals . $valq . $vala);
					$answer->setQuestion($question);
					$manager->persist($answer);
				}
			}
		}

		$manager->flush();
	}

}
