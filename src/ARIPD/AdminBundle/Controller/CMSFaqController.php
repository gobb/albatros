<?php
namespace ARIPD\AdminBundle\Controller;

use ARIPD\AdminBundle\Form\Type\CMSFaqFormType;
use ARIPD\CMSBundle\Entity\Faq;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDCMSBundle:Faq
 * 
 * @Route("/cms/faq")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class CMSFaqController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_admin_cms_faq_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		
		$entities = $em->getRepository('ARIPDCMSBundle:Faq')->findAll();
		
		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSFaq:index.html.twig',
						compact('entities'));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_cms_faq_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Faq')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSFaq:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 * 
	 * @Route("/new", name="aripd_admin_cms_faq_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Faq();
		$form = $this->createForm(new CMSFaqFormType(), $entity);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSFaq:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/create", name="aripd_admin_cms_faq_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Faq();
		$request = $this->getRequest();
		$form = $this->createForm(new CMSFaqFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_cms_faq_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSFaq:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}
	
	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_cms_faq_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Faq')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new CMSFaqFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSFaq:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_cms_faq_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Faq')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new CMSFaqFormType(), $entity);
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
											'aripd_admin_cms_faq_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSFaq:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_cms_faq_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDCMSBundle:Faq')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect($this->generateUrl('aripd_admin_cms_faq_index'));
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
