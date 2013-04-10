<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\AdminBundle\Form\Type\StoreModelFormType;
use ARIPD\StoreBundle\Entity\Model;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDStoreBundle:Model
 * 
 * @Route("/store/model")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class StoreModelController extends Controller {

	/**
	 * Sets entity as prime
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/prime", requirements={"id" = "\d+"}, name="aripd_admin_store_model_prime")
	 * @Template()
	 */
	public function primeAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Model')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}
		
		foreach ($entity->getProduct()->getModels() as $model) {
			$model->setPrime(false);
			$em->persist($model);
		}
		
		$entity->setPrime($entity->getPrime() ^ true);
		$em->persist($entity);
		$em->flush();
		
		return $this
				->redirect(
						$this
								->generateUrl('aripd_admin_storeproduct_show',
										array('id' => $entity->getProduct()->getId())));
	}

	/**
	 * 
	 * @Route("/{id}/attribute", requirements={"id" = "\d+"}, name="aripd_admin_store_model_attribute")
	 * @Method("POST")
	 * @Template()
	 */
	public function attributeAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Model')->find($id);

		//$entity = new Model();
		foreach ($entity->getAttributevalues() as $attributevalue) {
			$entity->removeAttributevalue($attributevalue);
			$em->persist($entity);
		}
		//$em->flush();

		$request = $this->getRequest();
		$attribute = $request->request->get('attribute');

		foreach ($attribute as $attributekey_id => $attributevalue_id) {
			$attributevalue = $em
					->getRepository('ARIPDStoreBundle:Attributevalue')
					->find($attributevalue_id);
			if ($attributevalue) {
				$entity->addAttributevalue($attributevalue);
				$em->persist($entity);
			}
		}
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl('aripd_admin_store_model_show',
										array('id' => $entity->getId())));

	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_store_model_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Model')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:StoreModel:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 *
	 * @Route("/{product_id}/new", name="aripd_admin_store_model_new")
	 * @Template()
	 */
	public function newAction($product_id) {
		$em = $this->getDoctrine()->getManager();

		$product = $em->getRepository('ARIPDStoreBundle:Product')
				->find($product_id);

		$entity = new Model();
		$form = $this
				->createForm(
						new StoreModelFormType(
								$this->get('request')->getLocale()), $entity);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:StoreModel:new.html.twig',
						array('entity' => $entity, 'product' => $product,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @Route("/{product_id}/create", name="aripd_admin_store_model_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction($product_id) {
		$em = $this->getDoctrine()->getManager();

		$product = $em->getRepository('ARIPDStoreBundle:Product')
				->find($product_id);

		$entity = new Model();
		$entity->setProduct($product);
		$request = $this->getRequest();
		$form = $this
				->createForm(
						new StoreModelFormType(
								$this->get('request')->getLocale()), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_store_model_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:StoreModel:new.html.twig',
						array('entity' => $entity, 'product' => $product,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_store_model_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Model')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this
				->createForm(
						new StoreModelFormType(
								$this->get('request')->getLocale()), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:StoreModel:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_store_model_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Model')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this
				->createForm(
						new StoreModelFormType(
								$this->get('request')->getLocale()), $entity);
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
											'aripd_admin_store_model_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:StoreModel:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_store_model_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDStoreBundle:Model')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect(
						$this
								->generateUrl('aripd_admin_storeproduct_show',
										array(
												'id' => $entity->getProduct()
														->getId())));
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
