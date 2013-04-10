<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\UserBundle\Form\Type\StoreCommentFormType;
use ARIPD\StoreBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/store/comment")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class StoreCommentController extends Controller {
	
	/**
	 * @Route("/index", name="aripd_user_store_comment_index")
	 * @Template()
	 */
	public function indexAction() {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Comment')
				->findByUser($user->getId());

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 10);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/StoreComment/index.html.twig',
						compact('pagination'));
	}

	/**
	 * @Route("/{id}/show", name="aripd_user_store_comment_show")
	 * @Template()
	 * 
	 * @param number $id
	 */
	public function showAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Comment')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/StoreComment/show.html.twig',
						array('entity' => $entity,));
	}

	/**
	 * @Route("/{product_id}/new", name="aripd_user_store_comment_new")
	 * @Template()
	 * 
	 * @param number $product_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function newAction($product_id) {
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('ARIPDStoreBundle:Product')
				->find($product_id);

		if (!$product) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity = new Comment();
		$entity->setProduct($product);
		$form = $this->createForm(new StoreCommentFormType(), $entity);

		return $this
				->render('::ARIPDUserBundle/StoreComment/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView()));
	}

	/**
	 * @Route("/{product_id}/create", name="aripd_user_store_comment_create")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @param number $product_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function createAction($product_id) {

		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('ARIPDStoreBundle:Product')
				->find($product_id);

		if (!$product) {
			throw $this->createNotFoundException('Unable to find');
		}

		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$entity = new Comment();
		$entity->setProduct($product);
		$entity->setUser($user);

		$request = $this->getRequest();
		$form = $this->createForm(new StoreCommentFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($entity);
			$em->flush();

			/*****************************************************
			 * Log to the database
			 *****************************************************/
			$this->container->get('aripduser.log_service')
					->saveLog($user, 'logtype___store_comment_request');

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_store_model_show',
											array(
													'id' => $entity
															->getProduct()->getModel()
															->getId(),
													'code' => $entity
															->getProduct()->getModel()
															->getCode(),
													'slug' => $entity
															->getProduct()
															->getSlug()))
									. '#comment-' . $entity->getId());
		}

		return $this
				->render('::ARIPDUserBundle/StoreComment/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

}
