<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\SurveyBundle\Entity\Result;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDSurveyBundle:Survey
 * 
 * @Route("/survey/survey")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class SurveySurveyController extends Controller {

	/**
	 * Finds and displays an entity
	 * TODO view takibi yap eğer login girişine zorlarsan
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_user_survey_survey_show")
	 * @Template()
	 */
	public function ________showAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDSurveyBundle:Survey')->find($id);
		return $this->container->get('templating')
				->renderResponse('::ARIPDSurveyBundle/Survey/show.html.twig',
						array('entity' => $entity,));
	}

	/**
	 * Creates vote for a user
	 * TODO hit takibi yap
	 * 
	 * @param number $id
	 * 
	 * @Route("/vote", requirements={"id" = "\d+"}, name="aripd_user_survey_survey_vote")
	 * @Method("POST")
	 * @Template()
	 */
	public function voteAction() {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$request = $this->getRequest();
		$result = $request->request->get('result');

		$em = $this->getDoctrine()->getManager();

		foreach ($result as $question_id => $answer_id) {
			$question = $em->getRepository('ARIPDSurveyBundle:Question')
					->find($question_id);
			$answer = $em->getRepository('ARIPDSurveyBundle:Answer')
					->find($answer_id);
			$survey = $question->getSurvey();
			$entity = new Result();
			$entity->setUser($user);
			$entity->setSurvey($survey);
			$entity->setQuestion($question);
			$entity->setAnswer($answer);
			$em->persist($entity);
		}
		$em->flush();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans(
										'flash.survey.vote.completed'));

		return new RedirectResponse($request->headers->get('referer'));

	}

}
