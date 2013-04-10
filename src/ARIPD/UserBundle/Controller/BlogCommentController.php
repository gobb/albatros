<?php
namespace ARIPD\UserBundle\Controller;

use ARIPD\UserBundle\Form\Type\BlogCommentFormType;
use ARIPD\BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/blog/comment")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class BlogCommentController extends Controller {
	
	/**
	 * @Route("/index", name="aripd_user_blog_comment_index")
	 * @Template()
	 */
	public function indexAction() {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDBlogBundle:Comment')
				->findByUser($user->getId());

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 10);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDUserBundle:BlogComment:index.html.twig',
						compact('pagination'));
	}

	/**
	 * @Route("/{id}/show", name="aripd_user_blog_comment_show")
	 * @Template()
	 * 
	 * @param number $id
	 */
	public function showAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDBlogBundle:Comment')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/BlogComment/show.html.twig',
						array('entity' => $entity,));
	}

	/**
	 * @Route("/{post_id}/new", name="aripd_user_blog_comment_new")
	 * @Template()
	 * 
	 * @param number $post_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function newAction($post_id) {
		$em = $this->getDoctrine()->getManager();
		$post = $em->getRepository('ARIPDBlogBundle:Post')->find($post_id);

		if (!$post) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity = new Comment();
		$entity->setPost($post);
		$form = $this->createForm(new BlogCommentFormType(), $entity);

		return $this
				->render('::ARIPDUserBundle/BlogComment/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView()));
	}

	/**
	 * @Route("/{post_id}/create", name="aripd_user_blog_comment_create")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @param number $post_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function createAction($post_id) {
		$em = $this->getDoctrine()->getManager();
		$post = $em->getRepository('ARIPDBlogBundle:Post')->find($post_id);

		if (!$post) {
			throw $this->createNotFoundException('Unable to find');
		}

		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$entity = new Comment();
		$entity->setPost($post);
		$entity->setUser($user);

		$request = $this->getRequest();
		$form = $this->createForm(new BlogCommentFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($entity);
			$em->flush();

			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('Your comment has been sent for approval. Thanks.', array(), 'ARIPDBlogBundle'));

			/*****************************************************
			 * Log to the database
			 *****************************************************/
			$this->container->get('aripduser.log_service')
					->saveLog($user, 'logtype___blog_comment_request');

			return $this
					->redirect(
							$this
									->generateUrl('aripd_blog_post_show',
											array(
													'id' => $entity->getPost()
															->getId(),
													'slug' => $entity
															->getPost()
															->getSlug())));
		}

		return $this
				->render('::ARIPDUserBundle/BlogComment/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/{id}/status", name="aripd_user_blog_comment_status")
	 * @Template()
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function statusAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		//$entity = $em->getRepository('ARIPDBlogBundle:Comment')->find($id);
		$entity = $em->getRepository('ARIPDBlogBundle:Comment')
				->createQueryBuilder('c')->select(array('c'))
				->leftJoin('c.post', 'p')->where('c.id = ?1')
				->setParameter(1, $id)->andWhere('p.user = ?2')
				->setParameter(2, $user->getId())->getQuery()
				->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		if ($approval = $entity->getApproved() ^ 1) {
			$this->container->get('aripduser.log_service')
					->saveLog($entity->getUser(),
							'logtype___blog_comment_confirmed');
		} else {
			$this->container->get('aripduser.log_service')
					->saveLog($entity->getUser(),
							'logtype___blog_comment_confirmation_withdrawn');
		}
		$entity->setApproved($approval);
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_user_blog_post_show',
										array(
												'id' => $entity->getPost()
														->getId(),)) . '#comment-'
								. $entity->getId());
	}

}
