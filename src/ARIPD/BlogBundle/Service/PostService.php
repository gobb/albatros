<?php
namespace ARIPD\BlogBundle\Service;

use ARIPD\BlogBundle\Entity\Hit;
use ARIPD\BlogBundle\Entity\Post;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class PostService {
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container,
			EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}

	public function saveHit(Post $post) {
		$entity = new Hit();
		$entity->setIp($this->container->get('request')->getClientIp());
		$entity->setPost($post);
		$entity->setSessionId($this->container->get('session')->getId());

		if (true === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
			$user = $this->container->get('security.context')->getToken()->getUser();
			$entity->setUser($user);
		}
		
		$this->em->persist($entity);
		$this->em->flush();
	}

}
