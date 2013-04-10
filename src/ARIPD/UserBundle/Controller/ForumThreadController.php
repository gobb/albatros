<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\ForumBundle\Entity\Thread;
use ARIPD\UserBundle\Form\Type\ForumThreadFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/forum/thread")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class ForumThreadController extends Controller {
	
	/**
	 * 
	 * @param number $thread_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{thread_id}/subscription", name="aripd_user_forum_thread_subscription")
	 * @Template()
	 */
	public function subscriptionAction($thread_id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDForumBundle:Thread')->find($thread_id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		if ($entity->getMembers()->contains($user)) {
			$this->get('session')
			->getFlashBag()->add('global-notice',
					$this->get('translator')
					->trans('You are already follower of this thread', array(), 'ARIPDForumBundle'));
		} else {
			$entity->getMembers()->add($user);
			$this->get('session')
			->getFlashBag()->add('global-notice',
					$this->get('translator')
					->trans('You are now following this thread', array(), 'ARIPDForumBundle'));
		}
		
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl('aripd_forum_thread_show',
										array('id' => $entity->getId(),
												'slug' => $entity->getSlug(),)));
	}

	/**
	 * 
	 * @param number $topic_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/{topic_id}/new", name="aripd_user_forum_thread_new")
	 * @Template()
	 */
	public function newAction($topic_id) {
		$em = $this->getDoctrine()->getManager();

		$topic = $em->getRepository('ARIPDForumBundle:Topic')->find($topic_id);

		if (!$topic) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity = new Thread();
		$entity->setTopic($topic);
		$form = $this->createForm(new ForumThreadFormType(), $entity);

		return $this
				->render('::ARIPDUserBundle/ForumThread/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView()));
	}

	/**
	 * 
	 * @param number $topic_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/{topic_id}/thread/create", name="aripd_user_forum_thread_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction($topic_id) {
		$em = $this->getDoctrine()->getManager();

		$topic = $em->getRepository('ARIPDForumBundle:Topic')->find($topic_id);

		if (!$topic) {
			throw $this->createNotFoundException('Unable to find');
		}

		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$entity = new Thread();
		$entity->setUser($user);
		$entity->setTopic($topic);

		$request = $this->getRequest();
		$form = $this->createForm(new ForumThreadFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			// FIXME Thread ile birlikte eklenen Post için User ve Topic başka nasıl set edebiliriz?
			foreach ($entity->getPosts() as $post) {
				$post->setUser($user);
				$post->setTopic($topic);
				$post->setThread($entity);
			}

			$em->persist($entity);
			$em->flush();

			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('Thread has been published', array(), 'ARIPDForumBundle'));

			/*****************************************************
			 * Log to the database
			 *****************************************************/
			$this->container->get('aripduser.log_service')
					->saveLog($user, 'logtype___forum_thread_request');

			// FIXME Eklenen Post nesnesine ait Id değerini başka nasıl elde ederiz?
			$posts = $entity->getPosts();
			$post = $posts[0];

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_forum_thread_show',
											array('id' => $entity->getId(),
													'slug' => $entity
															->getSlug(),))
									. '#' . $post->getId());
		} else {
			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('Thread could not be published', array(), 'ARIPDForumBundle'));
		}

		return $this
				->render('::ARIPDUserBundle/ForumThread/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

}
