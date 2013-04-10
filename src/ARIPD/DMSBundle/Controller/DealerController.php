<?php
namespace ARIPD\DMSBundle\Controller;
use ARIPD\DMSBundle\Entity\Dealer;
use ARIPD\DMSBundle\Form\DealerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDDMSBundle:Dealer
 * 
 * @Route("/dealer")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class DealerController extends Controller {

	/**
	 * Method that gets data in json format to use in datatable
	 * 
	 * @return Ambigous <\LanKit\DatatablesBundle\Datatables\mixed, mixed>
	 * 
	 * @Route("/data", name="aripd_admin_dmsdealer_data")
	 */
	public function dataAction() {
		$datatable = $this->get('lankit_datatables')->getDatatable('ARIPDDMSBundle:Dealer');
		return $datatable->getSearchResults();
	}
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_admin_dmsdealer_index")
	 * @Template()
	 */
	public function indexAction() {
		return array();
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_dmsdealer_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDDMSBundle:Dealer')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Dealer entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDDMSBundle:Dealer:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 *
	 * @Route("/new", name="aripd_admin_dmsdealer_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Dealer();
		$form = $this->createForm(new DealerType(), $entity);

		return $this
				->render('ARIPDDMSBundle:Dealer:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @Route("/create", name="aripd_admin_dmsdealer_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction(Request $request) {
		$entity = new Dealer();
		$form = $this->createForm(new DealerType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('dealer_show',
											array('id' => $entity->getId())));
		}

		return $this
				->render('ARIPDDMSBundle:Dealer:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_dmsdealer_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDDMSBundle:Dealer')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Dealer entity.');
		}

		$editForm = $this->createForm(new DealerType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDDMSBundle:Dealer:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_dmsdealer_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDDMSBundle:Dealer')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Dealer entity.');
		}

		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createForm(new DealerType(), $entity);
		$editForm->bind($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_dmsdealer_edit',
											array('id' => $id)));
		}

		return $this
				->render('ARIPDDMSBundle:Dealer:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_dmsdealer_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id) {
		$form = $this->createDeleteForm($id);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDDMSBundle:Dealer')->find($id);

			if (!$entity) {
				throw $this
						->createNotFoundException(
								'Unable to find Dealer entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('dealer'));
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
