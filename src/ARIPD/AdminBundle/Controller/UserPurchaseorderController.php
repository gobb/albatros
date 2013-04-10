<?php
namespace ARIPD\AdminBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

use ARIPD\AdminBundle\Form\Type\UserPurchaseorderFormType;

use ARIPD\UserBundle\Entity\Purchaseorder;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/user/purchaseorder")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class UserPurchaseorderController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_admin_userpurchaseorder_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		
		$entities = $em->getRepository('ARIPDUserBundle:Purchaseorder')
				->findAll();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 5);
		
		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:UserPurchaseorder:index.html.twig',
						compact('entities', 'pagination'));
	}

	/**
	 * Finds and displays an entity for printing
	 * 
	 * @param string $id
	 * 
	 * @Route("/{id}/print", requirements={"id" = "\d+"}, name="aripd_admin_userpurchaseorder_print")
	 * @Template()
	 */
	public function printAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDUserBundle:Purchaseorder')->find($id);
		$html = $this->renderView('ARIPDAdminBundle:UserPurchaseorder:print.html.twig', compact('entity'));
		
		$pdf = $this->container->get("white_october.tcpdf")->create();
		$pdf->SetAuthor('ARI PD, aripd.com');
		$pdf->SetFont('dejavusans', '', 11, '', true); // dejavusans is a UTF-8 Unicode font
		$pdf->AddPage();
		//$pdf->writeHTML($html, true, 0, true, 0);
		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		$pdf->lastPage();
		$pdf->Output(sprintf('%s.pdf', $entity->getPoid()), 'I');
		//$pdf->Output(sprintf('%s%s.pdf', __DIR__ . '/../../../../web/pdfs/', $entity->getPoid()), 'F'); // Saves the file to a directory
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param string $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_userpurchaseorder_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();
		
		$entity = $em->getRepository('ARIPDUserBundle:Purchaseorder')->find($id);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:UserPurchaseorder:show.html.twig',
						compact('entity'));
	}

	/**
	 * @Route("/new", name="aripd_admin_userpurchaseorder_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Purchaseorder();
		$form = $this->createForm(new UserPurchaseorderFormType(), $entity);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserPurchaseorder:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/create", name="aripd_admin_userpurchaseorder_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Purchaseorder();
		$request = $this->getRequest();
		$form = $this->createForm(new UserPurchaseorderFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_userpurchaseorder_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserPurchaseorder:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing entity.
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_userpurchaseorder_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:Purchaseorder')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new UserPurchaseorderFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserPurchaseorder:edit.html.twig',
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
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_userpurchaseorder_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:Purchaseorder')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		// Create an array of the current Outgoing objects in the database
		$originalOutgoings = array();
		foreach ($entity->getOutgoings() as $outgoing)
			$originalOutgoings[] = $outgoing;

		$editForm = $this->createForm(new UserPurchaseorderFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->bind($request);

		if ($editForm->isValid()) {

			// filter $originalOutgoings to contain outgoings no longer present
			foreach ($entity->getOutgoings() as $outgoing) {
				foreach ($originalOutgoings as $key => $toDel) {
					if ($toDel->getId() === $outgoing->getId()) {
						unset($originalOutgoings[$key]);
					}
				}
			}

			// remove the relationship between the outgoing and the entity
			foreach ($originalOutgoings as $outgoing) {
				// remove the entity from the Outgoing
				//$outgoing->getPosts()->removeElement($entity);

				// if it were a ManyToOne relationship, remove the relationship like this
				// $outgoing->setPost(null);

				//$em->persist($outgoing);

				// if you wanted to delete the Outgoing entirely, you can also do that
				$em->remove($outgoing);
			}

			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_userpurchaseorder_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserPurchaseorder:edit.html.twig',
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
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_userpurchaseorder_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDUserBundle:Purchaseorder')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect($this->generateUrl('aripd_admin_userpurchaseorder_index'));
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
