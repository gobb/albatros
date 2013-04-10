<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\AdminBundle\Form\Type\SurveySurveyType;
use ARIPD\SurveyBundle\Entity\Survey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/survey/survey")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class SurveySurveyController extends Controller {
	
	/**
	 * @Route("/index", name="aripd_admin_survey_survey_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDSurveyBundle:Survey')->findAll();

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:SurveySurvey:index.html.twig',
						array('entities' => $entities,));
	}

	/**
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_survey_survey_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDSurveyBundle:Survey')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$data = $em->getRepository('ARIPDSurveyBundle:Survey')->getReportData($id);
		
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:SurveySurvey:show.html.twig',
						array('entity' => $entity, 'data' => $data,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/new", name="aripd_admin_survey_survey_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Survey();
		$form = $this->createForm(new SurveySurveyType(), $entity);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:SurveySurvey:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/create", name="aripd_admin_survey_survey_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Survey();
		$request = $this->getRequest();
		$form = $this->createForm(new SurveySurveyType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_survey_survey_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:SurveySurvey:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_survey_survey_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDSurveyBundle:Survey')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new SurveySurveyType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:SurveySurvey:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_survey_survey_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDSurveyBundle:Survey')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new SurveySurveyType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->bind($request);

		if ($editForm->isValid()) {

			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_survey_survey_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:SurveySurvey:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_survey_survey_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDSurveyBundle:Survey')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_admin_survey_survey_index'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}

}
