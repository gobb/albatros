<?php
namespace ARIPD\ForumBundle\Service;

use ARIPD\ForumBundle\Entity\Hit;
use ARIPD\ForumBundle\Entity\Thread;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class ThreadService {
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container,
			EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}

	public function saveHit(Thread $thread) {
		$entity = new Hit();
		$entity->setIp($this->container->get('request')->getClientIp());
		$entity->setThread($thread);
		$entity->setSessionId($this->container->get('session')->getId());

		if (true === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
			$user = $this->container->get('security.context')->getToken()->getUser();
			$entity->setUser($user);
		}
		
		$this->em->persist($entity);
		$this->em->flush();
	}

}
