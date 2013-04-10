<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\AdminBundle\Entity\Iso639;
use ARIPD\AdminBundle\Form\Type\Iso639FormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDAdminBundle:Iso639
 * 
 * @Route("/iso639")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class Iso639Controller extends Controller {

	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_admin_iso639_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDAdminBundle:Iso639')->findAll();

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:Iso639:list.html.twig',
						compact('entities'));
	}
	
	/**
	 * Lists entities
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/index", name="aripd_admin_iso639_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDAdminBundle:Iso639')->findAll();

		return $this
				->render('ARIPDAdminBundle:Iso639:index.html.twig',
						array('entities' => $entities,));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_iso639_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDAdminBundle:Iso639')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Iso639 entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDAdminBundle:Iso639:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 *
	 * @Route("/new", name="aripd_admin_iso639_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Iso639();
		$form = $this->createForm(new Iso639FormType(), $entity);

		return $this
				->render('ARIPDAdminBundle:Iso639:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @Route("/create", name="aripd_admin_iso639_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Iso639();
		$request = $this->getRequest();
		$form = $this->createForm(new Iso639FormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('iso639_show',
											array('id' => $entity->getId())));
		}

		return $this
				->render('ARIPDAdminBundle:Iso639:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_iso639_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDAdminBundle:Iso639')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Iso639 entity.');
		}

		$editForm = $this->createForm(new Iso639FormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDAdminBundle:Iso639:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_iso639_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDAdminBundle:Iso639')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Iso639 entity.');
		}

		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createForm(new Iso639FormType(), $entity);
		$editForm->bind($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('iso639_edit',
											array('id' => $id)));
		}

		return $this
				->render('ARIPDAdminBundle:Iso639:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_iso639_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDAdminBundle:Iso639')->find($id);

			if (!$entity) {
				throw $this
						->createNotFoundException(
								'Unable to find Iso639 entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('iso639'));
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
