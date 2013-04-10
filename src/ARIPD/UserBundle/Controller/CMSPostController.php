<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\UserBundle\Form\Type\CMSPostFormType;
use ARIPD\CMSBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/cms/post")
 * @PreAuthorize("hasRole('ROLE_WRITER')")
 */
class CMSPostController extends Controller {

	/**
	 * @Route("/index", name="aripd_user_cms_post_index")
	 * @Template()
	 */
	public function indexAction() {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDCMSBundle:Post')
				->findByUser($user->getId());

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 10);

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/CMSPost/index.html.twig',
						compact('pagination'));
	}

	/**
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", name="aripd_user_cms_post_show")
	 * @Template()
	 */
	public function showAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Post')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/CMSPost/show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/new", name="aripd_user_cms_post_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Post();
		$form = $this
				->createForm(
						new CMSPostFormType($this->get('request')->getLocale()),
						$entity);
		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/CMSPost/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/create", name="aripd_user_cms_post_create")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function createAction() {
		$entity = new Post();
		$request = $this->getRequest();
		$form = $this
				->createForm(
						new CMSPostFormType($this->get('request')->getLocale()),
						$entity);
		$form->bind($request);

		if ($form->isValid()) {
			$user = $this->container->get('security.context')->getToken()
					->getUser();
			$entity->setUser($user);
			$entity->setApproved(false);

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('Submitted for new post approval', array(), 'ARIPDCMSBundle'));

			return $this
					->redirect(
							$this
									->generateUrl('aripd_user_cms_post_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/CMSPost/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/{id}/edit", name="aripd_user_cms_post_edit")
	 * @Template()
	 * 
	 * @param number $id
	 */
	public function editAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Post')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this
				->createForm(
						new CMSPostFormType($this->get('request')->getLocale()),
						$entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/CMSPost/edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/update", name="aripd_user_cms_post_update")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function updateAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Post')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this
				->createForm(
						new CMSPostFormType($this->get('request')->getLocale()),
						$entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->bind($request);

		if ($editForm->isValid()) {
			$entity->setUser($user);
			$entity->setApproved(false);

			$em->persist($entity);
			$em->flush();

			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('Submitted for update approval', array(), 'ARIPDCMSBundle'));

			return $this
					->redirect(
							$this
									->generateUrl('aripd_user_cms_post_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/CMSPost/edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/delete", name="aripd_user_cms_post_delete")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$user = $this->container->get('security.context')->getToken()
					->getUser();

			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDCMSBundle:Post')
					->findOneBy(array('user' => $user->getId(), 'id' => $id));

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('aripd_user_cms_post_index'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}

}
