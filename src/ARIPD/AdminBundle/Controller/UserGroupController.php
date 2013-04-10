<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\AdminBundle\Form\Type\UserGroupFormType;
use ARIPD\UserBundle\Entity\Group;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages user groups
 * 
 * @Route("/user/group")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class UserGroupController extends Controller {

	/**
	 * @Route("/index", name="aripd_admin_user_group_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDUserBundle:Group')->findAll();

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserGroup:index.html.twig',
						compact('entities'));
	}

	/**
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_user_group_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:Group')->findOneById($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserGroup:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));

	}

	/**
	 * @Route("/new", name="aripd_admin_user_group_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Group(null);
		$form = $this->createForm(new UserGroupFormType(), $entity);
	
		return $this->container->get('templating')
		->renderResponse('ARIPDAdminBundle:UserGroup:new.html.twig',
				array('entity' => $entity,
						'form' => $form->createView(),));
	}
	
	/**
	 * @Route("/create", name="aripd_admin_user_group_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Group(null);
		$request = $this->getRequest();
		$form = $this->createForm(new UserGroupFormType(), $entity);
		$form->bind($request);
	
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
	
			return $this
			->redirect(
					$this
					->generateUrl(
							'aripd_admin_user_group_show',
							array('id' => $entity->getId())));
		}
	
		return $this->container->get('templating')
		->renderResponse('ARIPDAdminBundle:UserGroup:new.html.twig',
				array('entity' => $entity,
						'form' => $form->createView(),));
	}
	
	/**
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_user_group_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();
	
		$entity = $em->getRepository('ARIPDUserBundle:Group')->find($id);
	
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}
	
		$editForm = $this->createForm(new UserGroupFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);
	
		return $this->container->get('templating')
		->renderResponse('ARIPDAdminBundle:UserGroup:edit.html.twig',
				array('entity' => $entity,
						'edit_form' => $editForm->createView(),
						'delete_form' => $deleteForm->createView(),));
	}
	
	/**
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_user_group_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();
	
		$entity = $em->getRepository('ARIPDUserBundle:Group')->find($id);
	
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}
	
		$editForm = $this->createForm(new UserGroupFormType(), $entity);
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
							'aripd_admin_user_group_edit',
							array('id' => $id)));
		}
	
		return $this->container->get('templating')
		->renderResponse('ARIPDAdminBundle:UserGroup:edit.html.twig',
				array('entity' => $entity,
						'edit_form' => $editForm->createView(),
						'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_user_group_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDUserBundle:Group')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect($this->generateUrl('aripd_admin_user_group_index'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}

}
