<?php
namespace ARIPD\DMSBundle\Controller;

use ARIPD\DMSBundle\Entity\Group;
use ARIPD\DMSBundle\Form\GroupType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDDMSBundle:Group
 * 
 * @Route("/group")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class GroupController extends Controller {

	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_dms_group_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDDMSBundle:Group')->findAll();

		return $this
				->render('ARIPDDMSBundle:Group:index.html.twig',
						array('entities' => $entities,));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_dms_group_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDDMSBundle:Group')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Group entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDDMSBundle:Group:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 *
	 * @Route("/new", name="aripd_dms_group_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Group();
		$form = $this->createForm(new GroupType(), $entity);

		return $this
				->render('ARIPDDMSBundle:Group:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @Route("/create", name="aripd_dms_group_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction(Request $request) {
		$entity = new Group();
		$form = $this->createForm(new GroupType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('group_show',
											array('id' => $entity->getId())));
		}

		return $this
				->render('ARIPDDMSBundle:Group:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_dms_group_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDDMSBundle:Group')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Group entity.');
		}

		$editForm = $this->createForm(new GroupType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDDMSBundle:Group:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_dms_group_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDDMSBundle:Group')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Group entity.');
		}

		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createForm(new GroupType(), $entity);
		$editForm->bind($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('group_edit',
											array('id' => $id)));
		}

		return $this
				->render('ARIPDDMSBundle:Group:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_dms_group_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id) {
		$form = $this->createDeleteForm($id);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDDMSBundle:Group')->find($id);

			if (!$entity) {
				throw $this
						->createNotFoundException(
								'Unable to find Group entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('group'));
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
