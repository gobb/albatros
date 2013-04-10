<?php
namespace ARIPD\CMSBundle\Service;

use ARIPD\CMSBundle\Entity\Hit;
use ARIPD\CMSBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class PostService {
	
	protected $container;
	protected $em;
	protected $request;
	
	public function __construct(ContainerInterface $container,
			EntityManager $em, Request $request) {
		$this->container = $container;
		$this->em = $em;
		$this->request = $request;
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

	public function getPosts() {
		$entities = $this->em->getRepository('ARIPDCMSBundle:Post')->createQueryBuilder('p')
				->leftJoin('p.topic', 't')
				->leftJoin('t.iso639', 'i')
				->where('p.approved = ?1')->setParameter(1, true)
				->andWhere('i.a2 = ?3')->setParameter(3, $this->request->getLocale())
				->getQuery()->getResult();
		
		return $entities;
	}
	
	public function getHistory() {
		$locale = $this->request->getLocale();
		
		$entities = $this->em->getRepository('ARIPDCMSBundle:Post')->getPostsByHistory($locale);
		
		//$entities = $this->em->getRepository('ARIPDCMSBundle:Post')->getYears($locale);
		//$entities = $this->em->getRepository('ARIPDCMSBundle:Post')->getMonths($locale, 2003);
		//$entities = $this->em->getRepository('ARIPDCMSBundle:Post')->getPostsByYearAndMonth($locale, 2012,11);
		
		/*
		$entities = $this->em->getRepository('ARIPDCMSBundle:Post')->createQueryBuilder('p')
				->select(array('p', 't'))
				->leftJoin('p.topic', 't')
				->leftJoin('t.iso639', 'i')
				->where('p.approved = ?1')->setParameter(1, true)
				->andWhere('i.a2 = ?3')->setParameter(3, $locale)
				->andWhere('p.publishedAt <= ?4')->setParameter(4, new \DateTime())
				->orderBy('p.publishedAt', 'DESC')
				->getQuery()->getResult();
		*/
		
		return $entities;
	}
	
}
