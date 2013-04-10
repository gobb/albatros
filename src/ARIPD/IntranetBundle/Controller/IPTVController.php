<?php
namespace ARIPD\IntranetBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ARIPD\IntranetBundle\Entity\IPTV;
use ARIPD\IntranetBundle\Form\IPTVType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * IPTV controller.
 *
 * @Route("/iptv")
 */
class IPTVController extends Controller {
	
	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/json", name="aripd_intranet_iptv_json")
	 */
	public function jsonAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDIntranetBundle:IPTV')->findAll();

		$serializer = $this->container->get('serializer');
		return new Response($serializer->serialize($entities, 'json'), 200,
				array('Content-Type' => 'application/json'));
	}
	
	/**
	 * Lists all IPTV entities.
	 *
	 * @Route("/", name="aripd_intranet_iptv_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDIntranetBundle:IPTV')->findAll();

		return $this
				->render('ARIPDIntranetBundle:IPTV:index.html.twig',
						array('entities' => $entities,));
	}

	/**
	 * Finds and displays a IPTV entity.
	 *
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, options={"expose" = true}), name="aripd_intranet_iptv_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDIntranetBundle:IPTV')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find IPTV entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDIntranetBundle:IPTV:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Displays a form to create a new IPTV entity.
	 *
	 * @Route("/new", name="aripd_intranet_iptv_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new IPTV();
		$form = $this->createForm(new IPTVType(), $entity);

		return $this
				->render('ARIPDIntranetBundle:IPTV:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new IPTV entity.
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @Route("/create", name="aripd_intranet_iptv_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction(Request $request) {
		$entity = new IPTV();
		$form = $this->createForm(new IPTVType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('iptv_show',
											array('id' => $entity->getId())));
		}

		return $this
				->render('ARIPDIntranetBundle:IPTV:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Displays a form to edit an existing IPTV entity.
	 *
	 * @param number $id
	 * 
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, options={"expose" = true}), name="aripd_intranet_iptv_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDIntranetBundle:IPTV')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find IPTV entity.');
		}

		$editForm = $this->createForm(new IPTVType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('ARIPDIntranetBundle:IPTV:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Edits an existing IPTV entity.
	 *
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_intranet_iptv_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDIntranetBundle:IPTV')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find IPTV entity.');
		}

		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createForm(new IPTVType(), $entity);
		$editForm->bind($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this->generateUrl('iptv_edit', array('id' => $id)));
		}

		return $this
				->render('ARIPDIntranetBundle:IPTV:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * Deletes a IPTV entity.
	 *
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_intranet_iptv_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id) {
		$form = $this->createDeleteForm($id);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDIntranetBundle:IPTV')->find($id);

			if (!$entity) {
				throw $this
						->createNotFoundException('Unable to find IPTV entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('iptv'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}

}
