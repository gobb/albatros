<?php
namespace ARIPD\StoreBundle\Service;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;

class CategoryService {
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container,
			EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}

	public function serialized($parent_id = null) {
		$entities = $this->em->getRepository('ARIPDStoreBundle:Category')
				->findBy(array('parent' => $parent_id));

		$serializer = $this->container->get('serializer');
		return $serializer->serialize($entities, 'json');
	}

}
