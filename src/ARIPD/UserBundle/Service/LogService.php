<?php
namespace ARIPD\UserBundle\Service;

use ARIPD\AdminBundle\Entity\Logtype;
use ARIPD\UserBundle\Entity\Bonus;
use ARIPD\UserBundle\Entity\User;
use ARIPD\UserBundle\Entity\Log;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class LogService {
	protected $container;
	protected $em;

	public function __construct(ContainerInterface $container,
			EntityManager $em) {
		$this->container = $container;
		$this->em = $em;
	}

	public function saveLog(User $user, $code) {
		$logtype = $this->em->getRepository('ARIPDAdminBundle:Logtype')->findOneByCode($code);

		$log = new Log();
		$log->setUser($user);
		$log->setSessionId($this->container->get('session')->getId());
		$log->setIp($this->container->get('request')->getClientIp());
		$log->setLogtype($logtype);
		$log->setBonus($logtype->getBonus());
		$this->em->persist($log);
		$this->em->flush();
		
		/**
		 * Log kaydını adminlere e-posta olarak gönder
		 */
		if ($logtype->getSendemail()) {
			$message = \Swift_Message::newInstance();
			$message->setFrom($this->container->getParameter('mail_sender_address'));
			$message->setTo($this->container->getParameter('administrators'));
			$message->setSubject($logtype->getName());
			$message->setBody($this->container->get('templating')->render(
					'ARIPDUserBundle:Log:email.txt.twig',
					compact('log')), 'text/html', 'utf-8');
			$this->container->get('mailer')->send($message);
		}
				
	}
	
	public function getAll() {
		$user = $this->container->get("security.context")->getToken()->getUser();
		return $this->em->getRepository('ARIPDUserBundle:Log')->findBy(array('user' => $user->getId()));
	}
	
	public function getBonusIsNotNull() {
		$user = $this->container->get("security.context")->getToken()->getUser();
		return $this->em->getRepository('ARIPDUserBundle:Log')->createQueryBuilder('l')
				->where('l.user = ?1')->setParameter(1, $user->getId())
				->andWhere('l.bonus IS NOT NULL')
				->getQuery()->getResult();
	}
	
}
