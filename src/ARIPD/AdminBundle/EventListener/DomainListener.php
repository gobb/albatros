<?php
namespace ARIPD\AdminBundle\EventListener;

use ARIPD\AdminBundle\Util\ARIPDString;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

class DomainListener {
	public function onDomainParse(Event $event) {
		$request = $event->getRequest();
		$session = $request->getSession();

		// TODO: parsing domain. Ref: http://stackoverflow.com/questions/5366234/symfony2-routing-route-subdomains/7056270#7056270
		$session->set('___domain', $request->getHost());
		$session->set('___domain_slugged', ARIPDString::slugify($session->get('___domain')));
		
	}
}
