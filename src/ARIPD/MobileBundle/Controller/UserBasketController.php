<?php
namespace ARIPD\MobileBundle\Controller;
use ARIPD\UserBundle\Entity\Basket;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/user/basket")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class UserBasketController extends Controller {

	/**
	 * Index page
	 * 
	 * @Route("/index", name="aripd_mobile_user_basket_index")
	 * @Template()
	 */
	public function indexAction() {
		$entities = $this->get('aripduser.basket_service')->getBasket();

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDMobileBundle:UserBasket:index.html.twig',
						compact('entities'));
	}

	/**
	 * Adds a model to basket
	 * 
	 * @param number $id
	 * @param number $quantity
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/{quantity}/add", requirements={"id" = "\d+", "quantity" = "\d+"}, name="aripd_mobile_user_basket_add")
	 * @Template()
	 */
	public function addAction($id, $quantity) {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$model = $em->getRepository('ARIPDStoreBundle:Model')->find($id);

		$entity = new Basket();
		$entity->setModel($model);
		$entity->setUser($user);
		$entity->setQuantity($quantity);
		$em->persist($entity);
		$em->flush();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans(
										'Your shopping basket has been updated successfully',
										array(), 'ARIPDShoppingBundle'));

		return $this
				->redirect($this->generateUrl('aripd_mobile_user_basket_index'));
	}

	/**
	 * Updates a model in basket via ajax
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/{quantity}/ajaxupdate", requirements={"id" = "\d+", "quantity" = "\d+", "_locale" = "tr|en"}, defaults={"_locale" = "tr"}, options={"expose" = true}), name="aripd_mobile_userbasket_ajaxupdate")
	 * @Method("GET")
	 * @Template()
	 */
	public function ajaxupdateAction($id, $quantity) {
		$request = $this->getRequest();
		if ($request->isXmlHttpRequest()) {
			$user = $this->get("security.context")->getToken()->getUser();

			$em = $this->getDoctrine()->getManager();

			$entity = $em->getRepository('ARIPDUserBundle:Basket')
					->findOneBy(array('model' => $id, 'user' => $user->getId()));
			$entity->setQuantity($quantity);

			$em->merge($entity);
			$em->flush();

			$return = array('responseCode' => 200);

			//$serializer = $this->container->get('serializer');
			//return new Response($serializer->serialize($entity, 'json'), 200, array('Content-Type' => 'application/json'));
		} else {
			$return = array('responseCode' => 400);
		}

		return new Response(json_encode($return), 200,
				array('Content-Type' => 'application/json'));

	}

	/**
	 * Removes a model from basket
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/remove", requirements={"id" = "\d+"}, name="aripd_mobile_user_basket_remove")
	 * @Template()
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

		return $this
				->redirect($this->generateUrl('aripd_mobile_user_basket_index'));

	}

	/**
	 * Removes all models from basket
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/clear", name="aripd_mobile_user_basket_clear")
	 * @Template()
	 */
	public function clearAction() {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$em->getRepository('ARIPDUserBundle:Basket')->createQueryBuilder('w')
				->delete()->where('w.user = ?1')
				->setParameter(1, $user->getId())->getQuery()->getResult();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans(
										'All products in your shopping basket has been removed',
										array(), 'ARIPDShoppingBundle'));

		return $this
				->redirect($this->generateUrl('aripd_mobile_user_basket_index'));
	}

}
