<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\AdminBundle\Form\Type\CMSPostFormType;
use ARIPD\CMSBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDCMSBundle:Post
 * 
 * @Route("/cms/post")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class CMSPostController extends Controller {

	/**
	 * Changes status of entity
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/status", requirements={"id" = "\d+"}, name="aripd_admin_cmspost_status")
	 * @Template()
	 */
	public function statusAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Post')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity->setApproved($entity->getApproved() ^ 1);
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl('aripd_admin_cmspost_show',
										array('id' => $entity->getId(),)));
	}

	/**
	 * Method that gets data in json format to use in datatable
	 * 
	 * @return Ambigous <\LanKit\DatatablesBundle\Datatables\mixed, mixed>
	 * 
	 * @Route("/data", name="aripd_admin_cmspost_data")
	 */
	public function dataAction() {
		$datatable = $this->get('lankit_datatables')->getDatatable('ARIPDCMSBundle:Post');
		return $datatable->getSearchResults();
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_admin_cmspost_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$topics = $this->liableTopics();

		$entities = array();
		if ($topics) {
			$entities = $em->getRepository('ARIPDCMSBundle:Post')
					->createQueryBuilder('p')->select('p')
					->where('p.topic IN (' . implode(',', $topics) . ')')
					->orderBy('p.approved', 'DESC')->getQuery()->getResult();
		}

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 5);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSPost:index.html.twig',
						compact('pagination'));

	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, options={"expose" = true}), name="aripd_admin_cmspost_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Post')
				->createQueryBuilder('p')->select('p')
				->where(
						'p.topic IN (' . implode(',', $this->liableTopics())
								. ')')->andWhere('p.id = ?1')
				->setParameter(1, $id)->getQuery()->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$hit = $em->getRepository('ARIPDCMSBundle:Post')->getHitReportData($id);

		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSPost:show.html.twig',
						array('entity' => $entity, 'hit' => $hit,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 *
	 * @Route("/new", options={"expose" = true}), name="aripd_admin_cmspost_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Post();
		$form = $this
				->createForm(
						new CMSPostFormType($this->get('request')->getLocale()),
						$entity);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSPost:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @Route("/create", name="aripd_admin_cmspost_create")
	 * @Method("POST")
	 * @Template()
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
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('aripd_admin_cmspost_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSPost:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_cmspost_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Post')
				->createQueryBuilder('p')->select('p')
				->where(
						'p.topic IN (' . implode(',', $this->liableTopics())
								. ')')->andWhere('p.id = ?1')
				->setParameter(1, $id)->getQuery()->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this
				->createForm(
						new CMSPostFormType($this->get('request')->getLocale()),
						$entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSPost:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_cmspost_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Post')
				->createQueryBuilder('p')->select('p')
				->where(
						'p.topic IN (' . implode(',', $this->liableTopics())
								. ')')->andWhere('p.id = ?1')
				->setParameter(1, $id)->getQuery()->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		// Create an array of the current Tag objects in the database
		$originalTags = array();
		foreach ($entity->getTags() as $tag)
			$originalTags[] = $tag;

		$editForm = $this
				->createForm(
						new CMSPostFormType($this->get('request')->getLocale()),
						$entity);
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
				//$tag->getPosts()->removeElement($entity);

				// if it were a ManyToOne relationship, remove the relationship like this
				// $tag->setPost(null);

				//$em->persist($tag);

				// if you wanted to delete the Tag entirely, you can also do that
				$em->remove($tag);
			}

			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('aripd_admin_cmspost_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSPost:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_cmspost_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDCMSBundle:Post')
					->createQueryBuilder('p')->select('p')
					->where(
							'p.topic IN ('
									. implode(',', $this->liableTopics()) . ')')
					->andWhere('p.id = ?1')->setParameter(1, $id)->getQuery()
					->getOneOrNullResult();

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('aripd_admin_cmspost_index'));
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
	 * Returns liable topics of user as unique sorted array
	 * 
	 * @return multitype:
	 */
	private function liableTopics() {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$arr = array();
		$oGroups = $user->getGroups();
		foreach ($oGroups as $oGroup) {
			foreach (($oGroup->getTopics()) as $oTopic) {
				$arr[] = $oTopic->getId();
			}
		}
		return array_unique($arr, SORT_REGULAR);
	}

}
