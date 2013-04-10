<?php
namespace ARIPD\StoreBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

use ARIPD\StoreBundle\Entity\Product;

class ProductListener {
	protected $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function postLoad(LifecycleEventArgs $args) {
		$entity = $args->getEntity();

		$em = $args->getEntityManager();

		if ($entity instanceof Product) {
			//echo get_class($entity);exit;

			//$entity->setHref($this->container->get('router')->generate('aripd_store_model_show', array('id'=>$entity->getModel()->getId(), 'code'=>$entity->getModel()->getCode(), 'slug'=>$entity->getSlug())));
			//$entity->setDefaultimageCached($this->container->get('image.handling')->open($entity->getDefaultImage()->getWebPath())->resize(150, 150)->jpeg());

			/*
			$user = $this->container->get('security.context')->getToken()->getUser();
			
			if (!is_object($user) || !$user instanceof User) {
			  throw new AccessDeniedException();
			}
			
			$entity->setUpdatedBy($user);
			
			$uow = $em->getUnitOfWork();
			$meta = $em->getClassMetadata(get_class($entity));
			$uow->recomputeSingleEntityChangeSet($meta, $entity);
			 */

			//$this->container->get('session')->remove('aripdads_tags');
			//$this->container->get('session')->set('aripdads_tags', $entity->getTags());
			//$this->container->get('aripdstore.product_service')->saveHit($entity);
			
		}
	}

}
