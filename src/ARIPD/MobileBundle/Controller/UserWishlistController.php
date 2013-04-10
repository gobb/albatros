<?php
namespace ARIPD\MobileBundle\Controller;
use ARIPD\UserBundle\Entity\Wishlist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/user/wishlist")
 */
class UserWishlistController extends Controller {

	/**
	 * @Route("/index", name="aripd_mobile_user_wishlist_index")
	 * @Template()
	 * @Secure(roles="ROLE_USER")
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDUserBundle:Wishlist')->findAll();

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDMobileBundle:UserWishlist:index.html.twig',
						compact('entities'));
	}

	/**
	 * @Route("/{id}/add", requirements={"id" = "\d+"}, name="aripd_mobile_user_wishlist_add")
	 * @Template()
	 * @Secure(roles="ROLE_USER")
	 * 
	 * Adds model to wishlist
	 *
	 * @param number $id - Model Id
	 */
	public function addAction($id) {
		$request = $this->getRequest();

		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		if ($em->getRepository('ARIPDUserBundle:Wishlist')
				->findOneBy(array('user' => $user->getId(), 'model' => $id))) {
			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans(
											'Product is already in your wish list', array(), 'ARIPDShoppingBundle'));
		} else {
			$model = $em->getRepository('ARIPDStoreBundle:Model')->find($id);

			$entity = new Wishlist();
			$entity->setModel($model);
			$entity->setUser($user);

			$em->persist($entity);
			$em->flush();

			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans(
											'Added to your wish list', array(), 'ARIPDShoppingBundle'));
		}

		return $this
				->redirect(
						$this->generateUrl('aripd_mobile_user_wishlist_index'));

		//return new RedirectResponse($request->headers->get('referer'));
		//return $this->redirect($request->headers->get('referer'));
	}

	/**
	 * @Route("/{id}/remove", requirements={"id" = "\d+"}, name="aripd_mobile_user_wishlist_remove")
	 * @Template()
	 * @Secure(roles="ROLE_USER")
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function removeAction($id) {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDUserBundle:Wishlist')
				->findOneBy(array('user' => $user->getId(), 'model' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$em->remove($entity);
		$em->flush();

		return $this
				->redirect(
						$this->generateUrl('aripd_mobile_user_wishlist_index'));

	}

	/**
	 * @Route("/clear", name="aripd_mobile_user_wishlist_clear")
	 * @Template()
	 * @Secure(roles="ROLE_USER")
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function clearAction() {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$em->getRepository('ARIPDUserBundle:Wishlist')->createQueryBuilder('w')
				->delete()->where('w.user = ?1')
				->setParameter(1, $user->getId())->getQuery()->getResult();

		return $this
				->redirect(
						$this->generateUrl('aripd_mobile_user_wishlist_index'));
	}

}
