<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\StoreBundle\Entity\Modelimage;
use ARIPD\UserBundle\Form\Type\StoreModelimageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/store/modelimage")
 * @PreAuthorize("hasRole('ROLE_WRITER')")
 */
class StoreModelimageController extends Controller {

	/**
	 * 
	 * @param number $product_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/product/{product_id}/image/new", name="aripd_user_store_modelimage_new")
	 * @Method("POST")
	 * @Template()
	 */
	public function newAction($product_id) {
		$em = $this->getDoctrine()->getManager();

		$product = $em->getRepository('ARIPDStoreBundle:Product')
				->find($product_id);

		if (!$product) {
			throw $this->createNotFoundException('Unable to find');
		}

		$form = $this->createForm(new StoreModelimageFormType(), new Image());

		return $this
				->render('::ARIPDUserBundle/StoreModelimage/new.html.twig',
						array('product' => $product,
								'form' => $form->createView(),));
	}

	/**
	 * 
	 * @param number $product_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/product/{product_id}/image/create", name="aripd_user_store_modelimage_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction($product_id) {
		$em = $this->getDoctrine()->getManager();

		$product = $em->getRepository('ARIPDStoreBundle:Product')
				->find($product_id);

		if (!$product) {
			throw $this->createNotFoundException('Unable to find');
		}

		$image = new Image();
		$image->setProduct($product);

		$form = $this->createForm(new StoreModelimageFormType(), $image);

		$request = $this->getRequest();
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($image);
			$em->flush();

			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans(
											'Upload completed', array(), 'ARIPDStoreBundle'));

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_user_store_product_show',
											array('id' => $product->getId(),
													'slug' => $product
															->getSlug()))
									. '#image-' . $image->getId());
		}

		return $this
				->render('::ARIPDUserBundle/StoreModelimage/new.html.twig',
						array('product' => $product,
								'form' => $form->createView(),));
	}

	/**
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/delete", name="aripd_user_store_modelimage_delete")
	 * @Template()
	 */
	public function deleteAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Modelimage')
				->createQueryBuilder('i')->leftJoin('i.product', 'p')
				->where('p.user = ?1')->setParameter(1, $user->getId())
				->andWhere('i.id = ?2')->setParameter(2, $id)->getQuery()
				->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$em->remove($entity);
		$em->flush();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans('Deleted successfully', array(), 'ARIPDStoreBundle'));

		return $this
				->redirect(
						$this
								->generateUrl('aripd_user_store_product_show',
										array(
												'id' => $entity->getProduct()
														->getId(),
												'slug' => $entity->getProduct()
														->getSlug())));
	}

	/**
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/set", name="aripd_user_store_modelimage_set")
	 * @Template()
	 */
	public function setAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Modelimage')
				->createQueryBuilder('i')->leftJoin('i.product', 'p')
				->where('p.user = ?1')->setParameter(1, $user->getId())
				->andWhere('i.id = ?2')->setParameter(2, $id)->getQuery()
				->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity->getProduct()->setDefaultimage($entity);
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl('aripd_user_store_product_show',
										array(
												'id' => $entity->getProduct()
														->getId(),)));
	}

}
