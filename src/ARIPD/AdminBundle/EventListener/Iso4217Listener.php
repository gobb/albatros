<?php
namespace ARIPD\AdminBundle\EventListener;

use ARIPD\AdminBundle\Entity\Iso4217;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Iso4217Listener {
	
	protected $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}
	
	public function onKernelRequest(GetResponseEvent $event) {
		$request = $event->getRequest();
		$session = $request->getSession();
		//var_dump($session->get('iso4217'));exit;
		
		if (null == $session->get('iso4217') || null == $session->get('iso4217')->getCode()) {
			$iso639 = $this->container->get('doctrine.orm.entity_manager')
					->getRepository('ARIPDAdminBundle:Iso639')
					->findOneByA2($request->getLocale());
			
			$session->set('iso4217', $iso639->getIso4217());
		}
		
	}
	
}
