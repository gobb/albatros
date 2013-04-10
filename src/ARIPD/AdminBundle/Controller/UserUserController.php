<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\AdminBundle\Form\Model\UserSearchFormModel;
use ARIPD\AdminBundle\Form\Type\UserUserFormType;
use ARIPD\AdminBundle\Form\Type\UserSearchFormType;
use ARIPD\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDUserBundle:User
 * 
 * @Route("/user/user")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class UserUserController extends Controller {

	/**
	 * Method that gets data in json format to use in datatable
	 * 
	 * @return Ambigous <\LanKit\DatatablesBundle\Datatables\mixed, mixed>
	 * 
	 * @Route("/data", name="aripd_admin_useruser_data")
	 */
	public function dataAction() {
		$datatable = $this->get('lankit_datatables')->getDatatable('ARIPDUserBundle:User');
		return $datatable->getSearchResults();
	}

	/**
	 * Creates form
	 */
	public function formAction() {
		$entity = new UserSearchFormModel();
		$form = $this->createForm(new UserSearchFormType(), $entity);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserUser:form.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Lists search results
	 * 
	 * @param string $q
	 * 
	 * @Route("/{q}/result", requirements={"q" = "[^/]+"}, name="aripd_admin_useruser_result")
	 * @Template()
	 */
	public function resultAction($q) {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDUserBundle:User')
				->createQueryBuilder('u')->select(array('u'))
				->where(
						'u.username LIKE ?1 OR u.firstname LIKE ?2 OR u.lastname LIKE ?3')
				->setParameter(1, "%$q%")->setParameter(2, "%$q%")
				->setParameter(3, "%$q%")->getQuery()->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 5);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserUser:index.html.twig',
						array('entities' => $entities,
								'pagination' => $pagination, 'q' => $q,));
	}

	/**
	 * Lists entities
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/index", name="aripd_admin_useruser_index")
	 * @Template()
	 */
	public function indexAction() {
		$entity = new UserSearchFormModel();
		$form = $this->createForm(new UserSearchFormType(), $entity);

		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDUserBundle:User')->findAll();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 5);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
				//$data = $form->getData();
				$postData = $request->request->get('aripdadmin_usersearchtype');
				return $this
						->redirect(
								$this
										->generateUrl(
												'aripd_admin_useruser_result',
												array('q' => $postData['name'])));
			}
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserUser:index.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),
								'pagination' => $pagination,));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, options={"expose" = true}), name="aripd_admin_useruser_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:User')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserUser:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));

	}

	/**
	 * @Route("/new", options={"expose" = true}), name="aripd_admin_useruser_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new User();
		$form = $this->createForm(new UserUserFormType(), $entity);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserUser:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/create", name="aripd_admin_useruser_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new User();
		$request = $this->getRequest();
		$form = $this->createForm(new UserUserFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_useruser_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserUser:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_useruser_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:User')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new UserUserFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserUser:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_useruser_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:User')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new UserUserFormType(), $entity);
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
											'aripd_admin_useruser_show',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserUser:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_useruser_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDUserBundle:User')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect($this->generateUrl('aripd_admin_useruser_index'));
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
