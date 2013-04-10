<?php
namespace ARIPD\UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use ARIPD\UserBundle\Entity\Wishlist;

/**
 * @Route("/wishlist")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class WishlistController extends Controller {

	/**
	 * Lists models in wishlist
	 * 
	 * @Route("/index", name="aripd_user_wishlist_index")
	 * @Template()
	 */
	public function indexAction() {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDUserBundle:Wishlist')
				->findBy(array('user' => $user->getId()));

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/Wishlist/index.html.twig',
						compact('entities'));
	}

	/**
	 * Adds a model to wishlist
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/add", name="aripd_user_wishlist_add")
	 * @Template()
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
			$model = $em->getRepository('ARIPDStoreBundle:Model')
					->find($id);

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

		return $this->redirect($this->generateUrl('aripd_user_wishlist_index'));

		//return new RedirectResponse($request->headers->get('referer'));
		//return $this->redirect($request->headers->get('referer'));
	}

	/**
	 * Remove a model in wishlist
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/remove", name="aripd_user_wishlist_remove")
	 * @Template()
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

		return $this->redirect($this->generateUrl('aripd_user_wishlist_index'));

	}

	/**
	 * Clear models in wishlist
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/clear", name="aripd_user_wishlist_clear")
	 * @Template()
	 */
	public function clearAction() {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$em->getRepository('ARIPDUserBundle:Wishlist')->createQueryBuilder('w')
				->delete()->where('w.user = ?1')
				->setParameter(1, $user->getId())->getQuery()->getResult();

		return $this->redirect($this->generateUrl('aripd_user_wishlist_index'));
	}

}
