<?php
namespace ARIPD\AdsBundle\Service;

use ARIPD\AdsBundle\Entity\View;
use ARIPD\AdsBundle\Entity\Hit;
use ARIPD\AdsBundle\Entity\Advertisement;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class AdsService {
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container,
			EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}

	public function saveHit(Advertisement $advertisement) {
		$entity = new Hit();
		$entity->setIp($this->container->get('request')->getClientIp());
		$entity->setAdvertisement($advertisement);
		$entity->setSessionId($this->container->get('session')->getId());
		
		if (true === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
			$user = $this->container->get('security.context')->getToken()->getUser();
			$entity->setUser($user);
		}
		
		$this->em->persist($entity);
		$this->em->flush();
	}

	public function saveView(Advertisement $advertisement) {
		$entity = new View();
		$entity->setIp($this->container->get('request')->getClientIp());
		$entity->setAdvertisement($advertisement);
		$entity->setSessionId($this->container->get('session')->getId());

		if (true === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
			$user = $this->container->get('security.context')->getToken()->getUser();
			$entity->setUser($user);
		}
		
		$this->em->persist($entity);
		$this->em->flush();
	}

}
