<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\CRMBundle\Entity\Taggroup;
use ARIPD\AdminBundle\Form\Type\CRMTaggroupType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDCRMBundle:Taggroup
 * 
 * @Route("/crm/taggroup")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class CRMTaggroupController extends Controller {

	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_admin_crm_taggroup_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		//$entities = $em->getRepository('ARIPDCRMBundle:Taggroup')->findAll();
		$entities = $em->getRepository('ARIPDCRMBundle:Taggroup')
				->createQueryBuilder('t')->orderBy('t.sorting')->getQuery()
				->getResult();

		return $this
				->render('ARIPDAdminBundle:CRMTaggroup:index.html.twig',
						array('entities' => $entities,));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_crm_taggroup_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCRMBundle:Taggroup')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Taggroup entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDAdminBundle:CRMTaggroup:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 * 
	 * @Route("/new", name="aripd_admin_crm_taggroup_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Taggroup();
		$form = $this->createForm(new CRMTaggroupType(), $entity);

		return $this
				->render('ARIPDAdminBundle:CRMTaggroup:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/create", name="aripd_admin_crm_taggroup_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction(Request $request) {
		$entity = new Taggroup();
		$form = $this->createForm(new CRMTaggroupType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_crm_taggroup_show',
											array('id' => $entity->getId())));
		}

		return $this
				->render('ARIPDAdminBundle:CRMTaggroup:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_crm_taggroup_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCRMBundle:Taggroup')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Taggroup entity.');
		}

		$editForm = $this->createForm(new CRMTaggroupType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDAdminBundle:CRMTaggroup:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_crm_taggroup_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCRMBundle:Taggroup')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Taggroup entity.');
		}

		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createForm(new CRMTaggroupType(), $entity);
		$editForm->bind($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_crm_taggroup_edit',
											array('id' => $id)));
		}

		return $this
				->render('ARIPDAdminBundle:CRMTaggroup:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_crm_taggroup_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id) {
		$form = $this->createDeleteForm($id);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDCRMBundle:Taggroup')->find($id);

			if (!$entity) {
				throw $this
						->createNotFoundException(
								'Unable to find Taggroup entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect($this->generateUrl('aripd_admin_crm_taggroup_index'));
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
