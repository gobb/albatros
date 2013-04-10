<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\ForumBundle\Entity\Post;
use ARIPD\UserBundle\Form\Type\ForumPostFormType;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/forum/post")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class ForumPostController extends Controller {

	/**
	 * 
	 * @param number $thread_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/thread/{thread_id}/post/new", name="aripd_user_forum_post_new")
	 * @Template()
	 */
	public function newAction($thread_id) {
		$em = $this->getDoctrine()->getManager();

		$thread = $em->getRepository('ARIPDForumBundle:Thread')
				->find($thread_id);

		if (!$thread) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity = new Post();
		$entity->setThread($thread);
		$form = $this->createForm(new ForumPostFormType(), $entity);

		return $this
				->render('::ARIPDUserBundle/ForumPost/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView()));
	}

	/**
	 * 
	 * @param number $thread_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/thread/{thread_id}/post/create", name="aripd_user_forum_post_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction($thread_id) {
		$em = $this->getDoctrine()->getManager();

		$thread = $em->getRepository('ARIPDForumBundle:Thread')
				->find($thread_id);

		if (!$thread) {
			throw $this->createNotFoundException('Unable to find');
		}

		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$entity = new Post();
		$entity->setUser($user);
		$entity->setThread($thread);
		$entity->setTopic($thread->getTopic());

		$request = $this->getRequest();
		$form = $this->createForm(new ForumPostFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($entity);
			$em->flush();

			$this
					->sendMessageToFollowers(
							$entity->getThread()->getMembers(), $entity);

			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('Post has been published', array(), 'ARIPDForumBundle'));

			/*****************************************************
			 * Log to the database
			 *****************************************************/
			$this->container->get('aripduser.log_service')
					->saveLog($user, 'logtype___forum_post_request');

			return $this
					->redirect(
							$this
									->generateUrl('aripd_forum_thread_show',
											array(
													'id' => $entity
															->getThread()
															->getId(),
													'slug' => $entity
															->getThread()
															->getSlug(),))
									. '#' . $entity->getId());
		} else {
			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('Post could not be published', array(), 'ARIPDForumBundle'));
		}

		return $this
				->render('::ARIPDUserBundle/ForumPost/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	private function sendMessageToFollowers(Collection $followers, Post $post) {
		foreach ($followers as $follower) {
			$message = \Swift_Message::newInstance()
					->setSubject($post->getThread()->getName())
					->setFrom(
							$this->container
									->getParameter('mail_sender_address'))
					->setTo($follower->getEmail())
					->setBody(
							$this
									->renderView(
											'ARIPDForumBundle:Thread:followerEmail.txt.twig',
											array('post' => $post)));

			$this->get('mailer')->send($message);
		}
	}

}
