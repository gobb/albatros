<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\StoreBundle\Entity\Point;
use ARIPD\StoreBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that permits authenticated users to manage grading store products
 *
 * @Route("/store/point")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class StorePointController extends Controller {

	/**
	 * Sets a grade for a product
	 * 
	 * @param number $id
	 * @param number $grade
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/product/{id}/{grade}/set", name="aripd_user_store_point_set")
	 * @Template()
	 */
	public function setAction($id, $grade) {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();

		$point = $em->getRepository('ARIPDStoreBundle:Point')
				->findOneBy(array('user' => $user->getId(), 'product' => $id));

		$product = $em->getRepository('ARIPDStoreBundle:Product')->find($id);

		if ($point) {
			$this->get('session')->getFlashBag()
					->add('global-notice',
							$this->get('translator')
									->trans('You have already graded before', array(), 'ARIPDStoreBundle'));
		} else {
			$point = new Point();
			$point->setPoint($grade);
			$point->setProduct($product);
			$point->setUser($user);

			$em->persist($point);
			$em->flush();

			$this->get('session')->getFlashBag()
					->add('global-notice',
							$this->get('translator')
									->trans('Thanks for grading', array(), 'ARIPDStoreBundle'));
		}

		return $this
				->redirect(
						$this
								->generateUrl('aripd_store_model_show',
										array(
												'id' => $product->getModel()
														->getId(),
												'code' => $product->getModel()
														->getCode(),
												'slug' => $product->getSlug())));
	}
}
