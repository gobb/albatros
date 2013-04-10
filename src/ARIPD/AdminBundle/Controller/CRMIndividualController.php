<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\AdminBundle\Form\Type\CRMIndividualSearchType;
use ARIPD\AdminBundle\Form\Model\CRMIndividualSearchForm;
use ARIPD\CRMBundle\Entity\Individual;
use ARIPD\AdminBundle\Form\Type\CRMIndividualType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDCRMBundle:Individual
 * 
 * @Route("/crm/individual")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class CRMIndividualController extends Controller {
	
	/**
	 * @Route("/json", name="aripd_admin_crm_individual_json")
	 * @Template()
	 */
	public function jsonAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDCRMBundle:Tagkey')->findAll();

		$serializer = $this->container->get('serializer');
		return new Response($serializer->serialize($entities, 'json'), 200,
				array('Content-Type' => 'application/json'));
	}

	public function formAction() {
		$entity = new CRMIndividualSearchForm();
		$form = $this->createForm(new CRMIndividualSearchType(), $entity);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:CRMIndividual:form.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/{q}/result", requirements={"q" = "\w+"}, name="aripd_admin_crm_individual_result")
	 * @Template()
	 */
	public function resultAction($q) {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDCRMBundle:Individual')
				->createQueryBuilder('i')->select(array('i'))
				->where(
						'i.firstname LIKE ?1 OR i.middlename LIKE ?2 OR i.lastname LIKE ?3')
				->setParameter(1, "%$q%")->setParameter(2, "%$q%")
				->setParameter(3, "%$q%")->getQuery()->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 12);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:CRMIndividual:result.html.twig',
						array('entities' => $entities,
								'pagination' => $pagination, 'q' => $q,));
	}

	/**
	 * Lists entities
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/index", name="aripd_admin_crm_individual_index")
	 * @Template()
	 */
	public function indexAction() {
		$entity = new CRMIndividualSearchForm();
		$form = $this->createForm(new CRMIndividualSearchType(), $entity);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
				//$data = $form->getData();
				$postData = $request->request
						->get('aripdadmin_crmindividualsearchtype');
				return $this
						->redirect(
								$this
										->generateUrl(
												'aripd_admin_crm_individual_result',
												array('q' => $postData['name'])));
			}
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:CRMIndividual:index.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_crm_individual_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCRMBundle:Individual')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$deleteForm = $this->createDeleteForm($id);

		//$taggroups = $em->getRepository('ARIPDCRMBundle:Taggroup')->findAll();
		$taggroups = $em->getRepository('ARIPDCRMBundle:Taggroup')->createQueryBuilder('t')
		->orderBy('t.sorting')->getQuery()->getResult();
		
		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:CRMIndividual:show.html.twig',
						array('taggroups' => $taggroups, 'entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 * 
	 * @Route("/new", name="aripd_admin_crm_individual_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Individual();
		$form = $this->createForm(new CRMIndividualType(), $entity);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:CRMIndividual:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/create", name="aripd_admin_crm_individual_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Individual();
		$request = $this->getRequest();
		$form = $this->createForm(new CRMIndividualType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_crm_individual_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:CRMIndividual:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_crm_individual_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCRMBundle:Individual')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new CRMIndividualType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:CRMIndividual:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_crm_individual_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCRMBundle:Individual')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		// Create an array of the current Tag objects in the database
		$originalTags = array();
		foreach ($entity->getTags() as $tag)
			$originalTags[] = $tag;

		$editForm = $this->createForm(new CRMIndividualType(), $entity);
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
				//$tag->getAdvertisements()->removeElement($entity);

				// if it were a ManyToOne relationship, remove the relationship like this
				// $tag->setAdvertisement(null);

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
											'aripd_admin_crm_individual_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:CRMIndividual:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_crm_individual_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDCRMBundle:Individual')
					->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect(
						$this->generateUrl('aripd_admin_crm_individual_index'));
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
