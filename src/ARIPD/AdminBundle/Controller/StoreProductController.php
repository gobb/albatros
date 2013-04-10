<?php
namespace ARIPD\AdminBundle\Controller;

use ARIPD\StoreBundle\Entity\Product;
use ARIPD\AdminBundle\Form\Type\StoreProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDStoreBundle:Product
 * 
 * @Route("/store/product")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class StoreProductController extends Controller {

	/**
	 * @Route("/search", name="aripd_admin_storeproduct_search")
	 * @Template()
	 */
	public function searchAction() {
		$request = $this->getRequest();
		$q = $request->query->get('q');
		
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')
				->createQueryBuilder('p')->select(array('p'))
				->where('p.name LIKE ?1')->setParameter(1, "%$q%")->getQuery()
				->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 5);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:StoreProduct:index.html.twig',
						array('entities' => $entities,
								'pagination' => $pagination, 'q' => $q,));
	}
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_admin_storeproduct_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')->findAll();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 5);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:StoreProduct:index.html.twig',
						compact('pagination'));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_storeproduct_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Product')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$hit = $em->getRepository('ARIPDStoreBundle:Product')->getHitReportData($id);
		
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:StoreProduct:show.html.twig',
						array('entity' => $entity, 'hit' => $hit,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 *
	 * @Route("/new", name="aripd_admin_storeproduct_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Product();
		$form = $this
				->createForm(
						new StoreProductFormType($this->get('request')->getLocale(), $entity),
						$entity);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:StoreProduct:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @Route("/create", name="aripd_admin_storeproduct_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Product();
		$request = $this->getRequest();
		$form = $this
				->createForm(
						new StoreProductFormType($this->get('request')->getLocale(), $entity),
						$entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_storeproduct_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:StoreProduct:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_storeproduct_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Product')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new StoreProductFormType($this->get('request')->getLocale(), $entity), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:StoreProduct:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_storeproduct_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Product')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		// Create an array of the current Tag objects in the database
		$originalTags = array();
		foreach ($entity->getTags() as $tag)
			$originalTags[] = $tag;

		$editForm = $this->createForm(new StoreProductFormType($this->get('request')->getLocale(), $entity), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->bind($request);

		if ($editForm->isValid()) {

			// filter $originalTags to contain tags no longer present
			foreach ($entity->getTags() as $tag) {
				foreach ($originalTags as $key => $toDel) {
					if ($toDel->getId() === $tag->getId()) {
						unset($originalTags[$key]);
					}
				}
			}

			// remove the relationship between the tag and the entity
			foreach ($originalTags as $tag) {
				// remove the entity from the Tag
				//$tag->getProducts()->removeElement($entity);

				// if it were a ManyToOne relationship, remove the relationship like this
				// $tag->setProduct(null);

				//$em->persist($tag);

				// if you wanted to delete the Tag entirely, you can also do that
				$em->remove($tag);
			}

			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_storeproduct_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:StoreProduct:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_storeproduct_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDStoreBundle:Product')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect(
						$this->generateUrl('aripd_admin_storeproduct_index'));
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

	/**
	 * @Route("/clear", name="aripd_admin_storeproduct_clear")
	 * @Template()
	 */
	public function clearAction() {
		$em = $this->getDoctrine()->getManager();

		$em->getRepository('ARIPDStoreBundle:Product')->createQueryBuilder('p')
				->delete()
				->getQuery()->getResult();
		
		return $this->redirect($this->generateUrl('aripd_admin_storeproduct_index'));
	}
	
}
