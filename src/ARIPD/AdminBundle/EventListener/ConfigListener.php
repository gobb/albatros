<?php
namespace ARIPD\AdminBundle\EventListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * http://symfony.com/doc/master/reference/configuration/swiftmailer.html#full-default-configuration
 */
class ConfigListener implements EventSubscriberInterface {
	/**
	 * @var Swift_Transport_EsmtpTransport
	 */
	private $transport;

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	private $em;

	public function __construct($transport, $em) {
		$this->transport = $transport;
		$this->em = $em;
	}

	public function onKernelRequest(GetResponseEvent $event) {
		$config = array();
		
		$entities = $this->em->getRepository('ARIPDAdminBundle:Config')->findAll();
		foreach ($entities as $entity) {
			$config[$entity->getName()] = $entity->getValue();
		}
		
		$this->transport->setHost($config['mailer_host']);
		$this->transport->setPort($config['mailer_port']);
		$this->transport->setUserName($config['mailer_user']);
		$this->transport->setPassword($config['mailer_password']);
		
	}

	public static function getSubscribedEvents() {
		return array(KernelEvents::REQUEST => array('onKernelRequest', 0));
	}
}
