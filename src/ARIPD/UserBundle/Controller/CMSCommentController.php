<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\UserBundle\Form\Type\CMSCommentFormType;
use ARIPD\CMSBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDCMSBundle:Comment
 * 
 * @Route("/cms/comment")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class CMSCommentController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_user_cms_comment_index")
	 * @Template()
	 */
		public function indexAction() {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDCMSBundle:Comment')
				->findByUser($user->getId());

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 10);

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/CMSComment/index.html.twig',
						compact('pagination'));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_user_cms_comment_show")
	 * @Template()
	 */
	public function showAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Comment')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/CMSComment/show.html.twig',
						array('entity' => $entity,));
	}

	/**
	 * @Route("/post/{post_id}/new", name="aripd_user_cms_comment_new")
	 * @Template()
	 * 
	 * @param number $post_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function newAction($post_id) {
		$em = $this->getDoctrine()->getManager();
		$post = $em->getRepository('ARIPDCMSBundle:Post')->find($post_id);

		if (!$post) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity = new Comment();
		$entity->setPost($post);
		$form = $this->createForm(new CMSCommentFormType(), $entity);

		return $this
				->render('::ARIPDUserBundle/CMSComment/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView()));
	}

	/**
	 * @Route("/post/{post_id}/create", name="aripd_user_cms_comment_create")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @param number $post_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function createAction($post_id) {

		$em = $this->getDoctrine()->getManager();
		$post = $em->getRepository('ARIPDCMSBundle:Post')->find($post_id);

		if (!$post) {
			throw $this->createNotFoundException('Unable to find');
		}

		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$entity = new Comment();
		$entity->setPost($post);
		$entity->setUser($user);

		$request = $this->getRequest();
		$form = $this->createForm(new CMSCommentFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($entity);
			$em->flush();

			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('Your comment has been sent for approval. Thanks.', array(), 'ARIPDCMSBundle'));

			/*****************************************************
			 * Log to the database
			 *****************************************************/
			$this->container->get('aripduser.log_service')
					->saveLog($user, 'logtype___cms_comment_request');

			return $this
					->redirect(
							$this
									->generateUrl('aripd_cms_post_show',
											array(
													'id' => $entity->getPost()
															->getId(),
													'slug' => $entity
															->getPost()
															->getSlug())));
		}

		return $this
				->render('::ARIPDUserBundle/CMSComment/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

}
