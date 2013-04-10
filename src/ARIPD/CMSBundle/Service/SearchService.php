<?php
namespace ARIPD\CMSBundle\Service;
use ARIPD\CMSBundle\Entity\Search;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class SearchService {
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container,
			EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}

	public function saveSearch($query) {
		$entity = new Search();
		$entity->setIp($this->container->get('request')->getClientIp());
		$entity->setQuery($query);
		$entity->setSessionId($this->container->get('session')->getId());

		if (true === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
			$user = $this->container->get('security.context')->getToken()->getUser();
			$entity->setUser($user);
		}
		
		$this->em->persist($entity);
		$this->em->flush();
	}

}
