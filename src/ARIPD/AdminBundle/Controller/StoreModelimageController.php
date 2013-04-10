<?php
namespace ARIPD\AdminBundle\Controller;

use ARIPD\StoreBundle\Entity\Modelimage;
use ARIPD\AdminBundle\Form\Type\StoreModelimageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDStoreBundle:Modelimage
 * 
 * @Route("/store/modelimage")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class StoreModelimageController extends Controller {

	/**
	 * Displays a form to create a new entity.
	 * 
	 * @Route("/{model_id}/new", requirements={"model_id" = "\d+"}, name="aripd_admin_store_modelimage_new")
	 * @Template()
	 */
	public function newAction($model_id) {
		$em = $this->getDoctrine()->getManager();

		$model = $em->getRepository('ARIPDStoreBundle:Model')
				->find($model_id);

		if (!$model) {
			throw $this->createNotFoundException('Unable to find');
		}

		$form = $this->createForm(new StoreModelimageFormType(), new Modelimage());

		return $this
				->render('ARIPDAdminBundle:StoreModelimage:new.html.twig',
						array('model' => $model,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{model_id}/create", requirements={"model_id" = "\d+"}, name="aripd_admin_store_modelimage_create")
	 * @Template()
	 */
	public function createAction($model_id) {
		$em = $this->getDoctrine()->getManager();

		$model = $em->getRepository('ARIPDStoreBundle:Model')
				->find($model_id);

		if (!$model) {
			throw $this->createNotFoundException('Unable to find');
		}

		$modelimage = new Modelimage();
		$modelimage->setModel($model);

		$form = $this->createForm(new StoreModelimageFormType(), $modelimage);

		$request = $this->getRequest();
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($modelimage);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_store_model_show',
											array('id' => $model->getId(),
													'slug' => $model
															->getSlug()))
									. '#modelimage-' . $modelimage->getId());
		}

		return $this
				->render('ARIPDAdminBundle:StoreModelimage:new.html.twig',
						array('model' => $model,
								'form' => $form->createView(),));
	}

	/**
	 * Deletes an entity
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_store_modelimage_delete")
	 * @Template()
	 */
	public function deleteAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Modelimage')->find($id);

		$em->remove($entity);
		$em->flush();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans('Deleted successfully', array(), 'ARIPDStoreBundle'));

		return $this
				->redirect(
						$this
								->generateUrl('aripd_admin_store_model_show',
										array(
												'id' => $entity->getModel()
														->getId(),
												'slug' => $entity->getModel()
														->getSlug())));
	}

	/**
	 * Sets an entity as default
	 * 
	 * @Route("/{id}/set", requirements={"id" = "\d+"}, name="aripd_admin_store_modelimage_set")
	 * @Template()
	 */
	public function setAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Modelimage')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity->getModel()->setDefaultimage($entity);
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_admin_store_model_show',
										array(
												'id' => $entity->getModel()
														->getId(),)));
	}

}
