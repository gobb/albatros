<?php
namespace ARIPD\MobileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/loyalty")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class LoyaltyController extends Controller {
	
	/**
	 * @Route("/index", name="aripd_mobile_loyalty_index")
	 * @Template()
	 */
	public function indexAction() {
		return $this->render('ARIPDMobileBundle:Loyalty:index.html.twig');
	}
	
	/**
	 * @Route("/code", name="aripd_mobile_loyalty_code")
	 * @Template()
	 */
	public function codeAction() {
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$code = $user->getConfirmationToken();
		
		return $this->render('ARIPDMobileBundle:Loyalty:code.html.twig', compact('code'));
	}
	
	/**
	 * @Route("/qrcode", name="aripd_mobile_loyalty_qrcode")
	 * @Template()
	 */
	public function qrcodeAction() {
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		if (null === $user->getConfirmationToken()) {
			/** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
			$tokenGenerator = $this->container->get('fos_user.util.token_generator');
			$user->setConfirmationToken($tokenGenerator->generateToken());
			$this->container->get('fos_user.user_manager')->updateUser($user);
		}
		
		$code = $user->getConfirmationToken();
		
		return $this->render('ARIPDMobileBundle:Loyalty:qrcode.html.twig', compact('code'));
	}
	
	/**
	 * @Route("/points", name="aripd_mobile_loyalty_points")
	 * @Template()
	 */
	public function pointsAction() {
		$entities = $this->get('aripduser.log_service')->getBonusIsNotNull();
		
		return $this->render('ARIPDMobileBundle:Loyalty:points.html.twig', compact('entities'));
	}
	
	/**
	 * @Route("/transfer", name="aripd_mobile_loyalty_transfer")
	 * @Template()
	 */
	public function transferAction() {
		return $this->render('ARIPDMobileBundle:Loyalty:transfer.html.twig');
	}
	
}
