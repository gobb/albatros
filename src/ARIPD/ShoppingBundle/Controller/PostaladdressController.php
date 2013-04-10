<?php
namespace ARIPD\ShoppingBundle\Controller;
use ARIPD\ShoppingBundle\Form\Type\PostaladdressFormType;
use ARIPD\UserBundle\Entity\Postaladdress;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDShoppingBundle:Postaladdress
 * 
 * @Route("/postaladdress")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class PostaladdressController extends Controller {
	
	public function formAction() {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDUserBundle:Postaladdress')
				->findBy(array('user' => $user->getId()));
		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDShoppingBundle/Postaladdress/form.html.twig',
						compact('entities'));
	}

	/**
	 * 
	 * @throws AccessDeniedException
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/list", name="aripd_shopping_postaladdress_list")
	 * @Template()
	 */
	public function listAction() {
		$session = $this->get('session');
		$transportation = $session->get('transportation');
		if (empty($transportation)) {
			return $this->redirect($this->generateUrl('aripd_shopping_transportation_list'));
		}

		if (false === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDUserBundle:Postaladdress')
				->findBy(array('user' => $user->getId()));
		
		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDShoppingBundle/Postaladdress/list.html.twig',
						compact('entities'));
	}

	public function jsonAction() {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDUserBundle:Postaladdress')
				->findBy(array('user' => $user->getId()));

		$serializer = $this->container->get('serializer');
		return new Response($serializer->serialize($entities, 'json'), 200,
				array('Content-Type' => 'application/json'));
	}

	/**
	 * 
	 * @Route("/set", name="aripd_shopping_postaladdress_set")
	 * @Method("POST")
	 * @Template()
	 */
	public function setAction() {
		$request = $this->getRequest();
		$session = $this->container->get('session');
		$session->set('invoiceaddress', $request->request->get('invoiceaddress'));
		$session->set('deliveryaddress', $request->request->get('deliveryaddress'));
		
		return $this
				->redirect(
						$this
						->generateUrl(
								'aripd_shopping_payment_checkout'));
	}
	
	public function createAction() {
		$request = $this->getRequest();
		$models = $request->query->get('models');
		$a = json_decode($models, true);
		//print_r($a);exit;

		$user = $this->get("security.context")->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();
		foreach ($a as $model) {
			$postaladdress = new Postaladdress();
			$postaladdress->setCity($model["city"]);
			$postaladdress->setContent($model["content"]);
			$postaladdress->setCountry($model["country"]);
			$postaladdress->setCounty($model["county"]);
			$postaladdress->setLatitude($model["latitude"]);
			$postaladdress->setLongitude($model["longitude"]);
			$postaladdress->setName($model["name"]);
			$postaladdress->setPostalcode($model["postalcode"]);
			$postaladdress->setUser($user);
			$em->persist($postaladdress);
		}
		$em->flush();
		return new Response();
	}

	public function updateAction() {
		$request = $this->getRequest();
		$models = $request->query->get('models');
		$a = json_decode($models, true);
		//print_r($a);exit;
		/*
		$serializer = $this->container->get('serializer');
		$object = $serializer->deserialize($models, 'ARIPD\ShoppingBundle\Entity\Postaladdress', 'json');
		var_dump($object);exit;
		 */
		$user = $this->get("security.context")->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();
		foreach ($a as $model) {
			$postaladdress = $em
					->getRepository('ARIPDUserBundle:Postaladdress')
					->findOneBy(
							array('user' => $user->getId(),
									'id' => $model["id"]));
			$postaladdress->setName($model["name"]);
			$postaladdress->setContent($model["content"]);
			$postaladdress->setCounty($model["county"]);
			$postaladdress->setCity($model["city"]);
			$postaladdress->setPostalcode($model["postalcode"]);
			$postaladdress->setCountry($model["country"]);
			$em->persist($postaladdress);
		}
		$em->flush();
		return new Response();
	}

	public function destroyAction() {
		$request = $this->getRequest();
		$models = $request->query->get('models');
		$a = json_decode($models, true);
		//print_r($a);exit;

		$user = $this->get("security.context")->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();
		foreach ($a as $model) {
			$postaladdress = $em
					->getRepository('ARIPDUserBundle:Postaladdress')
					->findOneBy(
							array('user' => $user->getId(),
									'id' => $model["id"]));
			$em->remove($postaladdress);
		}
		$em->flush();
		return new Response();
	}
}
