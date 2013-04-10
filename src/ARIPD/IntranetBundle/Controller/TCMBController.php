<?php
namespace ARIPD\IntranetBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use ARIPD\IntranetBundle\Helper\TCMBHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ARIPD\IntranetBundle\Entity\TCMB;
use ARIPD\IntranetBundle\Form\TCMBType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * TCMB controller.
 *
 * @Route("/tcmb")
 */
class TCMBController extends Controller {
	
	/**
	 * Lists all TCMB entities.
	 *
	 * @Route("/", name="aripd_intranet_tcmb_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDIntranetBundle:TCMB')->findAll();

		return $this
				->render('ARIPDIntranetBundle:TCMB:index.html.twig',
						array('entities' => $entities,));
	}

	/**
	 * Finds and displays a TCMB entity.
	 *
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDIntranetBundle:TCMB')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find TCMB entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDIntranetBundle:TCMB:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new TCMB entity.
	 *
	 */
	public function newAction() {
		$entity = new TCMB();
		$form = $this->createForm(new TCMBType(), $entity);

		return $this
				->render('ARIPDIntranetBundle:TCMB:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new TCMB entity.
	 *
	 */
	public function createAction(Request $request) {
		$entity = new TCMB();
		$form = $this->createForm(new TCMBType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('tcmb_show',
											array('id' => $entity->getId())));
		}

		return $this
				->render('ARIPDIntranetBundle:TCMB:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing TCMB entity.
	 *
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDIntranetBundle:TCMB')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find TCMB entity.');
		}

		$editForm = $this->createForm(new TCMBType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDIntranetBundle:TCMB:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Edits an existing TCMB entity.
	 *
	 */
	public function updateAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDIntranetBundle:TCMB')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find TCMB entity.');
		}

		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createForm(new TCMBType(), $entity);
		$editForm->bind($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this->generateUrl('tcmb_edit', array('id' => $id)));
		}

		return $this
				->render('ARIPDIntranetBundle:TCMB:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Deletes a TCMB entity.
	 *
	 */
	public function deleteAction(Request $request, $id) {
		$form = $this->createDeleteForm($id);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDIntranetBundle:TCMB')->find($id);

			if (!$entity) {
				throw $this
						->createNotFoundException('Unable to find TCMB entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('tcmb'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}

	public function jsonAction() {
		//$this->get('aripd_intranet.tcmb')->populate();exit;
		
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDIntranetBundle:TCMB')->createQueryBuilder('t')
		->where('t.code = ?1')->setParameter(1, 'USD')
		->orderBy('t.date')
		->setMaxResults(121)
		->getQuery()->getResult();
		
		return new Response($this->get('aripd_intranet.tcmb')->retrieve($entities));
	}
}
