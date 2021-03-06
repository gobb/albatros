<?php
namespace ARIPD\CMSBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

use ARIPD\CMSBundle\Entity\Post;

class PostListener {
	protected $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	public function postLoad(LifecycleEventArgs $args) {
		$entity = $args->getEntity();

		$em = $args->getEntityManager();

		if ($entity instanceof Post) {
			//$this->container->get('session')->remove('aripdads_tags');
			//$this->container->get('session')->set('aripdads_tags', $entity->getTags());
			//$this->container->get('aripdcms.post_service')->saveHit($entity);
		}
	}

}
