<?php
namespace ARIPD\AdminBundle\Controller;

use ARIPD\AdminBundle\Form\Type\AdsAdvertisementType;
use ARIPD\AdsBundle\Entity\Advertisement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDAdsBundle:Advertisement
 * 
 * @Route("/ads/advertisement")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class AdsAdvertisementController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_admin_ads_advertisement_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDAdsBundle:Advertisement')
				->findAll();

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:AdsAdvertisement:index.html.twig',
						compact('entities'));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_ads_advertisement_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDAdsBundle:Advertisement')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$view = $em->getRepository('ARIPDAdsBundle:Advertisement')->getViewReportData($id);
		$hit = $em->getRepository('ARIPDAdsBundle:Advertisement')->getHitReportData($id);
		
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:AdsAdvertisement:show.html.twig',
						array('entity' => $entity, 'view' => $view, 'hit' => $hit,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new entity.
	 *
	 * @Route("/new", name="aripd_admin_ads_advertisement_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Advertisement();
		$form = $this->createForm(new AdsAdvertisementType(), $entity);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:AdsAdvertisement:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @Route("/create", name="aripd_admin_ads_advertisement_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Advertisement();
		$request = $this->getRequest();
		$form = $this->createForm(new AdsAdvertisementType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_ads_advertisement_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:AdsAdvertisement:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_ads_advertisement_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDAdsBundle:Advertisement')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new AdsAdvertisementType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:AdsAdvertisement:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_ads_advertisement_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDAdsBundle:Advertisement')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		// Create an array of the current Tag objects in the database
		$originalTags = array();
		foreach ($entity->getTags() as $tag)
			$originalTags[] = $tag;

		$editForm = $this->createForm(new AdsAdvertisementType(), $entity);
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
											'aripd_admin_ads_advertisement_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:AdsAdvertisement:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_ads_advertisement_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDAdsBundle:Advertisement')
					->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_admin_ads_advertisement_index'));
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
