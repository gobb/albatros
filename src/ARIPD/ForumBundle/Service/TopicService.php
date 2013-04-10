<?php
namespace ARIPD\ForumBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;

class TopicService {
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container, EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}

	public function getHierarchicalData($parent_id = null) {
		//$iso639 = $this->em->getRepository('ARIPDAdminBundle:Iso639')->findOneByA2($this->container->get('request')->getLocale());
		//$entities = $this->em->getRepository('ARIPDForumBundle:Topic')->findBy(array('iso639' => $iso639->getId(), 'parent' => $parent_id));
		
		$qb = $this->em->getRepository('ARIPDForumBundle:Topic')->createQueryBuilder('p');
		$qb->select(array('p', 'i'));
		$qb->leftJoin('p.iso639', 'i');
		if ($parent_id == null)
			$qb->where('p.parent IS NULL');
		else 
			$qb->where('p.parent = :parent')->setParameter('parent', $parent_id);
		$qb->andWhere('i.a2 = :a2')->setParameter('a2', $this->container->get('request')->getLocale());
		$entities = $qb->getQuery()->getResult();
		
		$serializer = $this->container->get('serializer');
		return $serializer->serialize($entities, 'json');
	}

}
