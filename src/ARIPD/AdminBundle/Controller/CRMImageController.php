<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\CRMBundle\Entity\Image;
use ARIPD\AdminBundle\Form\Type\CRMImageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDCRMBundle:Image
 * 
 * @Route("/crm/image")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class CRMImageController extends Controller {

	/**
	 * Displays a form to create a new entity.
	 * 
	 * @param number $individual_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/{individual_id}/show", requirements={"individual_id" = "\d+"}, name="aripd_admin_crm_image_new")
	 * @Template()
	 */
	public function newAction($individual_id) {
		$em = $this->getDoctrine()->getManager();

		$individual = $em->getRepository('ARIPDCRMBundle:Individual')
				->find($individual_id);

		if (!$individual) {
			throw $this->createNotFoundException('Unable to find');
		}

		$form = $this->createForm(new CRMImageFormType(), new Image());

		return $this
				->render('ARIPDAdminBundle:CRMImage:new.html.twig',
						array('individual' => $individual,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 * 
	 * @param number $individual_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/{individual_id}/create", requirements={"individual_id" = "\d+"}, name="aripd_admin_crm_image_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction($individual_id) {
		$em = $this->getDoctrine()->getManager();

		$individual = $em->getRepository('ARIPDCRMBundle:Individual')
				->find($individual_id);

		if (!$individual) {
			throw $this->createNotFoundException('Unable to find');
		}

		$image = new Image();
		$image->setIndividual($individual);

		$form = $this->createForm(new CRMImageFormType(), $image);

		$request = $this->getRequest();
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($image);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_crm_individual_show',
											array('id' => $individual->getId(),))
									. '#image-' . $image->getId());
		}

		return $this
				->render('ARIPDAdminBundle:CRMImage:new.html.twig',
						array('individual' => $individual,
								'form' => $form->createView(),));
	}

	/**
	 * Deletes an entity
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_crm_image_delete")
	 * @Template()
	 */
	public function deleteAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCRMBundle:Image')
				->createQueryBuilder('i')->where('i.id = ?1')
				->setParameter(1, $id)->getQuery()->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$em->remove($entity);
		$em->flush();

		$this->get('session')->getFlashBag()
				->add('global-notice',
						$this->get('translator')
								->trans('flash.crm.image.delete.completed'));

		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_admin_crm_individual_show',
										array(
												'id' => $entity
														->getIndividual()
														->getId(),)));
	}

	/**
	 * Sets an entity as default
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/set", requirements={"id" = "\d+"}, name="aripd_admin_crm_image_set")
	 * @Template()
	 */
	public function setAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCRMBundle:Image')
				->createQueryBuilder('i')->where('i.id = ?1')
				->setParameter(1, $id)->getQuery()->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity->getIndividual()->setDefaultimage($entity);
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_admin_crm_individual_show',
										array(
												'id' => $entity
														->getIndividual()
														->getId(),)));
	}

}
