<?php
namespace ARIPD\StoreBundle\Service;

use ARIPD\StoreBundle\Entity\Hit;
use ARIPD\StoreBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class ProductService {
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container,
			EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}

	public function saveHit(Product $product) {
		$entity = new Hit();
		$entity->setIp($this->container->get('request')->getClientIp());
		$entity->setProduct($product);
		$entity->setSessionId($this->container->get('session')->getId());

		if (true === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
			$user = $this->container->get('security.context')->getToken()->getUser();
			$entity->setUser($user);
		}
		
		$this->em->persist($entity);
		$this->em->flush();
	}

}
