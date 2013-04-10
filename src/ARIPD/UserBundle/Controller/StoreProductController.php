<?php
namespace ARIPD\UserBundle\Controller;

use ARIPD\UserBundle\Form\Type\StoreProductFormType;
use ARIPD\StoreBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/store/product")
 * @PreAuthorize("hasRole('ROLE_WRITER')")
 */
class StoreProductController extends Controller {
	
	/**
	 * @Route("/index", name="aripd_user_store_product_index")
	 * @Template()
	 */
	public function indexAction() {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDStoreBundle:Product')
				->findByUser($user->getId());

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 5);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/StoreProduct/index.html.twig',
						compact('pagination'));
	}

	/**
	 * @Route("/{id}/show", name="aripd_user_store_product_show")
	 * @Template()
	 * 
	 * @param number $id
	 */
	public function showAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Product')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/StoreProduct/show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/new", name="aripd_user_store_product_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Product();
		$form = $this->createForm(new StoreProductFormType(), $entity);
		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/StoreProduct/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/create", name="aripd_user_store_product_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Product();
		$request = $this->getRequest();
		$form = $this->createForm(new StoreProductFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$user = $this->container->get('security.context')->getToken()
					->getUser();
			$entity->setUser($user);
			$entity->setApproved(false);

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_user_store_product_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/StoreProduct/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/{id}/edit", name="aripd_user_store_product_edit")
	 * @Template()
	 * 
	 * @param number $id
	 */
	public function editAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Product')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new StoreProductFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/StoreProduct/edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/update", name="aripd_user_store_product_update")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @param number $id
	 */
	public function updateAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Product')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new StoreProductFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->bind($request);

		if ($editForm->isValid()) {
			$entity->setUser($user);
			$entity->setApproved(false);

			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_user_store_product_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/StoreProduct/edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/delete", name="aripd_user_store_product_delete")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @param number $id
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$user = $this->container->get('security.context')->getToken()
					->getUser();

			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDStoreBundle:Product')
					->findOneBy(array('user' => $user->getId(), 'id' => $id));

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect(
						$this->generateUrl('aripd_user_store_product_index'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}

}
