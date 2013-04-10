<?php
namespace ARIPD\SurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDSurveyBundle:Survey
 * 
 * @Route("/survey")
 */
class SurveyController extends Controller {

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_survey_survey_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDSurveyBundle:Survey')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$data = $em->getRepository('ARIPDSurveyBundle:Survey')
				->getReportData($id);

		return $this->container->get('templating')
				->renderResponse('::ARIPDSurveyBundle/Survey/show.html.twig',
						compact('entity', 'data'));
	}

}
