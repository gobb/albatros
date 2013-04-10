<?php
namespace ARIPD\DMSBundle\Controller;
use ARIPD\DMSBundle\Entity\Salesorder;
use ARIPD\DMSBundle\Form\SalesorderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDDMSBundle:Salesorder
 * 
 * @Route("/salesorder")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class SalesorderController extends Controller {

	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_dms_salesorder_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDDMSBundle:Salesorder')->findAll();

		return $this
				->render('ARIPDDMSBundle:Salesorder:index.html.twig',
						array('entities' => $entities,));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_dms_salesorder_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDDMSBundle:Salesorder')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException(
							'Unable to find Salesorder entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDDMSBundle:Salesorder:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 *
	 * @Route("/new", name="aripd_dms_salesorder_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Salesorder();
		$form = $this->createForm(new SalesorderType(), $entity);

		return $this
				->render('ARIPDDMSBundle:Salesorder:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @Route("/create", name="aripd_dms_salesorder_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction(Request $request) {
		$entity = new Salesorder();
		$form = $this->createForm(new SalesorderType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('salesorder_show',
											array('id' => $entity->getId())));
		}

		return $this
				->render('ARIPDDMSBundle:Salesorder:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_dms_salesorder_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDDMSBundle:Salesorder')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException(
							'Unable to find Salesorder entity.');
		}

		$editForm = $this->createForm(new SalesorderType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDDMSBundle:Salesorder:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Edits an existing entity.
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_dms_salesorder_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDDMSBundle:Salesorder')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException(
							'Unable to find Salesorder entity.');
		}

		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createForm(new SalesorderType(), $entity);
		$editForm->bind($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('salesorder_edit',
											array('id' => $id)));
		}

		return $this
				->render('ARIPDDMSBundle:Salesorder:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Deletes an entity
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_dms_salesorder_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id) {
		$form = $this->createDeleteForm($id);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDDMSBundle:Salesorder')
					->find($id);

			if (!$entity) {
				throw $this
						->createNotFoundException(
								'Unable to find Salesorder entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('salesorder'));
	}

	/**
	 * Creates a delete form
	 * 
	 * @param number $id
	 * @return \Symfony\Component\Form\Form
	 */
	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}
}
