<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\UserBundle\Entity\Basket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/basket")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class BasketController extends Controller {

	/**
	 * @Route("/index", name="aripd_user_basket_index")
	 * @Template()
	 */
	public function indexAction() {
		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/Basket/index.html.twig');
	}

	/**
	 * Adds a product to basket via form
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/create", name="aripd_user_basket_create")
	 * @Template()
	 */
	public function createAction($id) {
		$quantity = $this->getRequest()->get('quantity');

		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:Basket')
				->findOneBy(array('model' => $id, 'user' => $user->getId()));

		if (!$entity) {
			$model = $em->getRepository('ARIPDStoreBundle:Model')->find($id);
			$entity = new Basket();
			$entity->setModel($model);
			$entity->setUser($user);
			$entity->setQuantity($quantity);
		} else {
			$entity->setQuantity($entity->getQuantity() + $quantity);
		}

		$em->persist($entity);
		$em->flush();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans(
										'Your shopping basket has been updated successfully',
										array(), 'ARIPDShoppingBundle'));

		return $this->redirect($this->generateUrl('aripd_user_basket_index'));
	}

	/**
	 * Adds a product to basket via link
	 * 
	 * @param number $id
	 * @param number $quantity
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/{quantity}/add", name="aripd_user_basket_add")
	 * @Template()
	 */
	public function addAction($id, $quantity) {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:Basket')
				->findOneBy(array('model' => $id, 'user' => $user->getId()));

		if (!$entity) {
			$model = $em->getRepository('ARIPDStoreBundle:Model')->find($id);
			$entity = new Basket();
			$entity->setModel($model);
			$entity->setUser($user);
			$entity->setQuantity($quantity);
		} else {
			$entity->setQuantity($entity->getQuantity() + $quantity);
		}

		$em->persist($entity);
		$em->flush();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans(
										'"%productName%" has been added to your shopping cart',
										array(
												'%productName%' => $entity
														->getModel()
														->getProduct()
														->getName()),
										'ARIPDShoppingBundle'));

		return $this->redirect($this->generateUrl('aripd_user_basket_index'));
	}

	/**
	 * @Route("/update", name="aripd_user_basket_update")
	 * @Method("POST")
	 * @Template()
	 * 
	 * Updates all products in basket
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function updateAction() {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();

		foreach ($this->getRequest()->request->get('shoppingbasket') as $id => $quantity) {
			$entity = $em->getRepository('ARIPDUserBundle:Basket')
					->findOneBy(array('model' => $id, 'user' => $user->getId()));
			$entity->setQuantity($quantity);

			$em->merge($entity);
		}
		$em->flush();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans(
										'Your shopping basket has been updated successfully',
										array(), 'ARIPDShoppingBundle'));

		return $this->redirect($this->generateUrl('aripd_user_basket_index'));
	}

	/**
	 * @Route("/{id}/remove", name="aripd_user_basket_remove")
	 * @Template()
	 * 
	 * Removes one product from basket
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function removeAction($id) {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDUserBundle:Basket')
				->findOneBy(array('user' => $user->getId(), 'model' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$em->remove($entity);
		$em->flush();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans(
										'"%productName%" has been removed from your shopping basket',
										array(
												'%productName%' => $entity
														->getModel()
														->getProduct()
														->getName()),
										'ARIPDShoppingBundle'));

		return $this->redirect($this->generateUrl('aripd_user_basket_index'));

	}

	/**
	 * @Route("/clear", name="aripd_user_basket_clear")
	 * @Template()
	 * 
	 * Removes all products from basket
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function clearAction() {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$em->getRepository('ARIPDUserBundle:Basket')->createQueryBuilder('b')
				->delete()->where('b.user = ?1')
				->setParameter(1, $user->getId())->getQuery()->getResult();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans(
										'All products in your shopping basket has been removed',
										array(), 'ARIPDShoppingBundle'));

		return $this->redirect($this->generateUrl('aripd_user_basket_index'));
	}

	/**
	 * Lists products in basket
	 */
	public function formAction() {
		$entities = $this->get('aripduser.basket_service')->getBasket();

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/Basket/form.html.twig',
						compact('entities'));
	}

}
