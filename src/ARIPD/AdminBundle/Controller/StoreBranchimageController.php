<?php
namespace ARIPD\AdminBundle\Controller;

use ARIPD\StoreBundle\Entity\Branchimage;
use ARIPD\AdminBundle\Form\Type\StoreBranchimageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDStoreBundle:Branchimage
 * 
 * @Route("/store/branchimage")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class StoreBranchimageController extends Controller {

	/**
	 * Displays a form to create a new entity.
	 * 
	 * @Route("/{branch_id}/new", requirements={"branch_id" = "\d+"}, name="aripd_admin_store_branchimage_new")
	 * @Template()
	 */
	public function newAction($branch_id) {
		$em = $this->getDoctrine()->getManager();

		$branch = $em->getRepository('ARIPDStoreBundle:Branch')
				->find($branch_id);

		if (!$branch) {
			throw $this->createNotFoundException('Unable to find');
		}

		$form = $this->createForm(new StoreBranchimageFormType(), new Branchimage());

		return $this
				->render('ARIPDAdminBundle:StoreBranchimage:new.html.twig',
						array('branch' => $branch,
								'form' => $form->createView(),));
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{branch_id}/create", requirements={"branch_id" = "\d+"}, name="aripd_admin_store_branchimage_create")
	 * @Template()
	 */
	public function createAction($branch_id) {
		$em = $this->getDoctrine()->getManager();

		$branch = $em->getRepository('ARIPDStoreBundle:Branch')
				->find($branch_id);

		if (!$branch) {
			throw $this->createNotFoundException('Unable to find');
		}

		$branchimage = new Branchimage();
		$branchimage->setBranch($branch);

		$form = $this->createForm(new StoreBranchimageFormType(), $branchimage);

		$request = $this->getRequest();
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($branchimage);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_store_branch_show',
											array('id' => $branch->getId(),
													'slug' => $branch
															->getSlug()))
									. '#branchimage-' . $branchimage->getId());
		}

		return $this
				->render('ARIPDAdminBundle:StoreBranchimage:new.html.twig',
						array('branch' => $branch,
								'form' => $form->createView(),));
	}

	/**
	 * Deletes an entity
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_store_branchimage_delete")
	 * @Template()
	 */
	public function deleteAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Branchimage')
				->createQueryBuilder('i')->leftJoin('i.branch', 'p')
				->where('i.id = ?2')->setParameter(2, $id)->getQuery()
				->getOneOrNullResult();

		$em->remove($entity);
		$em->flush();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans('Deleted successfully', array(), 'ARIPDStoreBundle'));

		return $this
				->redirect(
						$this
								->generateUrl('aripd_admin_store_branch_show',
										array(
												'id' => $entity->getBranch()
														->getId(),
												'slug' => $entity->getBranch()
														->getSlug())));
	}

	/**
	 * Sets an entity as default
	 * 
	 * @Route("/{id}/set", requirements={"id" = "\d+"}, name="aripd_admin_store_branchimage_set")
	 * @Template()
	 */
	public function setAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Branchimage')
				->createQueryBuilder('i')->leftJoin('i.branch', 'p')
				->where('i.id = ?2')->setParameter(2, $id)->getQuery()
				->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity->getBranch()->setDefaultimage($entity);
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_admin_store_branch_show',
										array(
												'id' => $entity->getBranch()
														->getId(),)));
	}

}
