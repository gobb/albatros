<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\UserBundle\Form\Type\MessageFormType;
use ARIPD\UserBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/message")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class MessageController extends Controller {
	
	/**
	 * @Route("/{status}/list", requirements={"status" = "\w+"}, defaults={"status" = "C1C2"}, name="aripd_user_message_list")
	 * @Template()
	 */
	public function listAction($status = 'C1C2') {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();
		if ($status == 'P2') {
			$mode = 'producer';
			$entities = $em->getRepository('ARIPDUserBundle:Message')
					->findBy(
							array('producer' => $user->getId(),
									'statusProducer' => $status));
		} else {
			$mode = 'consumer';
			//$entities = $em->getRepository('ARIPDUserBundle:Message')->findBy(array('consumer'=>$user->getId(), 'statusConsumer'=>'C1'));
			$entities = $em->getRepository('ARIPDUserBundle:Message')
					->getMessagesByConsumerExceptC3($user->getId());
		}

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 10);

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/Message/list.html.twig',
						compact('pagination', 'mode'));
	}

	/**
	 * @Route("/{mode}/{id}/show", requirements={"mode" = "\w+", "id" = "\d+"}, name="aripd_user_message_show")
	 * @Template()
	 */
	public function showAction($mode, $id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();
		if ($mode == 'producer') {
			$entity = $em->getRepository('ARIPDUserBundle:Message')
					->findOneBy(
							array('producer' => $user->getId(), 'id' => $id));
			if (!$entity || $entity->getStatusProducer() == 'P3')
				throw $this->createNotFoundException('Unable to find');
		} elseif ($mode == 'consumer') {
			$entity = $em->getRepository('ARIPDUserBundle:Message')
					->findOneBy(
							array('consumer' => $user->getId(), 'id' => $id));
			if (!$entity || $entity->getStatusConsumer() == 'C3')
				throw $this->createNotFoundException('Unable to find');
			$em->getRepository('ARIPDUserBundle:Message')
					->setAsRead($user->getId(), $id);
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/Message/show.html.twig',
						array('mode' => $mode, 'entity' => $entity,));
	}

	/**
	 * @Route("/{consumer_id}/new", requirements={"consumer_id" = "\d+"}, name="aripd_user_message_new")
	 * @Template()
	 */
	public function newAction($consumer_id) {
		$em = $this->getDoctrine()->getManager();
		$consumer = $em->getRepository('ARIPDUserBundle:User')
				->find($consumer_id);

		if (!$consumer) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity = new Message();
		$form = $this->createForm(new MessageFormType(), $entity);

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/Message/new.html.twig',
						array('consumer' => $consumer,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/{consumer_id}/new/post", requirements={"consumer_id" = "\d+"}, name="aripd_user_message_new_post")
	 * @Template()
	 */
	public function newPostAction($consumer_id) {
		$producer = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();
		$consumer = $em->getRepository('ARIPDUserBundle:User')
				->find($consumer_id);

		if (!$consumer) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity = new Message();
		$entity->setProducer($producer);
		$entity->setConsumer($consumer);
		$entity->setStatusProducer('P2');
		$entity->setStatusConsumer('C1');

		$request = $this->getRequest();
		$form = $this->createForm(new MessageFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this->generateUrl('aripd_user_message_list'));
		}

		return $this
				->render('::ARIPDUserBundle/Message/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));

	}

	/**
	 * @Route("/{consumer_id}/{parent_id}/reply", requirements={"consumer_id" = "\d+", "parent_id" = "\d+"}, name="aripd_user_message_reply")
	 * @Template()
	 */
	public function replyAction($consumer_id, $parent_id) {
		$em = $this->getDoctrine()->getManager();
		$consumer = $em->getRepository('ARIPDUserBundle:User')
				->find($consumer_id);

		if (!$consumer) {
			throw $this->createNotFoundException('Unable to find');
		}

		$parent = $em->getRepository('ARIPDUserBundle:Message')
				->find($parent_id);

		$entity = new Message();
		$form = $this->createForm(new MessageFormType(), $entity);

		return $this->container->get('templating')
				->renderResponse('::ARIPDUserBundle/Message/reply.html.twig',
						array('consumer' => $consumer, 'parent' => $parent,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/{consumer_id}/{parent_id}/reply/post", requirements={"consumer_id" = "\d+", "parent_id" = "\d+"}, name="aripd_user_message_reply_post")
	 * @Template()
	 */
	public function replyPostAction($consumer_id, $parent_id) {
		$producer = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();
		$consumer = $em->getRepository('ARIPDUserBundle:User')
				->find($consumer_id);

		if (!$consumer) {
			throw $this->createNotFoundException('Unable to find');
		}

		$parent = $em->getRepository('ARIPDUserBundle:Message')
				->find($parent_id);

		$entity = new Message();
		$entity->setProducer($producer);
		$entity->setConsumer($consumer);
		$entity->setParent($parent);
		$entity->setStatusProducer('P2');
		$entity->setStatusConsumer('C1');

		$request = $this->getRequest();
		$form = $this->createForm(new MessageFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this->generateUrl('aripd_user_message_list'));
		}

		return $this
				->render('::ARIPDUserBundle/Message/reply.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));

	}

	/**
	 * @Route("/{mode}/{id}/delete", requirements={"mode" = "\w+", "id" = "\d+"}, name="aripd_user_message_deleteFake")
	 * @Template()
	 */
	public function deleteFakeAction($mode, $id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();
		if ($mode == 'consumer') {
			$entity = $em->getRepository('ARIPDUserBundle:Message')
					->findOneBy(
							array('consumer' => $user->getId(), 'id' => $id));
		} elseif ($mode == 'producer') {
			$entity = $em->getRepository('ARIPDUserBundle:Message')
					->findOneBy(
							array('producer' => $user->getId(), 'id' => $id));
		}

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$em->getRepository('ARIPDUserBundle:Message')
				->deleteFake($mode, $user->getId(), $id);

		return $this
				->redirect($this->generateUrl('aripd_user_message_list'));
	}

	/**
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_user_message_delete")
	 * @Template()
	 */
	public function deleteAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDUserBundle:Message')->find($id);
		$em->remove($entity);
		$em->flush();
		return $this
				->redirect($this->generateUrl('aripd_user_message_list'));
	}

}
