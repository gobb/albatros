<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\AdminBundle\Form\Type\CMSPageFormType;
use ARIPD\CMSBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDCMSBundle:Page
 * 
 * @Route("/cms/page")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class CMSPageController extends Controller {
	
	/**
	 * Generates tree data
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/data", name="aripd_admin_cms_page_data")
	 */
	public function dataAction() {
		$parent_id = $this->container->get('request')->query->get('id');
		return new Response(
				$this->get('aripdcms.page_service')->serialized($parent_id),
				200, array('Content-Type' => 'application/json'));
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_admin_cms_page_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDCMSBundle:Page')->findAll();

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSPage:index.html.twig',
						compact('entities'));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_cms_page_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDCMSBundle:Page')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSPage:show.html.twig',
						compact('entity'));
	}

	/**
	 * Displays a form to create a new entity.
	 * 
	 * @Route("/new", name="aripd_admin_cms_page_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Page();
		$form = $this->createForm(new CMSPageFormType(), $entity);
	
		return $this->container->get('templating')
		->renderResponse('ARIPDAdminBundle:CMSPage:new.html.twig',
				array('entity' => $entity,
						'form' => $form->createView(),));
	}
	
	/**
	 * Creates a new entity
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/create", name="aripd_admin_cms_page_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Page();
		$request = $this->getRequest();
		$form = $this->createForm(new CMSPageFormType(), $entity);
		$form->bind($request);
	
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
	
			return $this
			->redirect(
					$this
					->generateUrl(
							'aripd_admin_cms_page_show',
							array('id' => $entity->getId())));
		}
	
		return $this->container->get('templating')
		->renderResponse('ARIPDAdminBundle:CMSPage:new.html.twig',
				array('entity' => $entity,
						'form' => $form->createView(),));
	}
	
	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_cms_page_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();
	
		$entity = $em->getRepository('ARIPDCMSBundle:Page')->find($id);
	
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}
	
		$editForm = $this->createForm(new CMSPageFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);
	
		return $this->container->get('templating')
		->renderResponse('ARIPDAdminBundle:CMSPage:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_cms_page_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();
	
		$entity = $em->getRepository('ARIPDCMSBundle:Page')->find($id);
	
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}
	
		$editForm = $this->createForm(new CMSPageFormType(), $entity);
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
							'aripd_admin_cms_page_edit',
							array('id' => $id)));
		}
	
		return $this->container->get('templating')
		->renderResponse('ARIPDAdminBundle:CMSPage:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_cms_page_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();
	
		$form->bind($request);
	
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDCMSBundle:Page')->find($id);
	
			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}
	
			$em->remove($entity);
			$em->flush();
		}
	
		return $this
		->redirect($this->generateUrl('aripd_admin_cms_page_index'));
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
