<?php
namespace ARIPD\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ARIPD\AdminBundle\Entity\Logtype;
use ARIPD\AdminBundle\Form\Type\LogtypeFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDAdminBundle:Logtype
 * 
 * @Route("/logtype")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class LogtypeController extends Controller {

	/**
	 * Lists entities
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/index", name="aripd_admin_logtype_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDAdminBundle:Logtype')->findAll();

		return $this
				->render('ARIPDAdminBundle:Logtype:index.html.twig',
						array('entities' => $entities,));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_logtype_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDAdminBundle:Logtype')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Logtype entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDAdminBundle:Logtype:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 *
	 * @Route("/new", name="aripd_admin_logtype_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Logtype();
		$form = $this->createForm(new LogtypeFormType(), $entity);

		return $this
				->render('ARIPDAdminBundle:Logtype:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @Route("/create", name="aripd_admin_logtype_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Logtype();
		$request = $this->getRequest();
		$form = $this->createForm(new LogtypeFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('logtype_show',
											array('id' => $entity->getId())));
		}

		return $this
				->render('ARIPDAdminBundle:Logtype:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_logtype_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDAdminBundle:Logtype')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Logtype entity.');
		}

		$editForm = $this->createForm(new LogtypeFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDAdminBundle:Logtype:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_logtype_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDAdminBundle:Logtype')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Logtype entity.');
		}

		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createForm(new LogtypeFormType(), $entity);
		
		$request = $this->getRequest();
		
		$editForm->bind($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('aripd_admin_logtype_edit',
											array('id' => $id)));
		}

		return $this
				->render('ARIPDAdminBundle:Logtype:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_logtype_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();
		
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDAdminBundle:Logtype')->find($id);

			if (!$entity) {
				throw $this
						->createNotFoundException(
								'Unable to find Logtype entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('logtype'));
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
