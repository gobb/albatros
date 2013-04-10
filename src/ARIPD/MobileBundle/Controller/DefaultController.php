<?php
namespace ARIPD\MobileBundle\Controller;

use ARIPD\MobileBundle\Form\Model\LoginFormModel;
use ARIPD\MobileBundle\Form\Type\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/default")
 */
class DefaultController extends Controller {
	
	/**
	 * @Route("/index", name="aripd_mobile_default_index")
	 * @Template()
	 */
	public function indexAction() {
		return $this->render('ARIPDMobileBundle:Default:index.html.twig');
	}

	/**
	 * @Route("/login", name="aripd_mobile_default_login")
	 * @Template()
	 */
	public function loginAction() {
		/*
		$entity = new LoginFormModel();
		$form = $this->createForm(new LoginFormType(), $entity);
		
		return $this
		    ->render('ARIPDMobileBundle:Default:login.html.twig',
		        array('form' => $form->createView(),));
		 */
		
		if (true === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('aripd_mobile_default_index'));
		}

		$csrfToken = $this->container->get('form.csrf_provider')
				->generateCsrfToken('authenticate');

		return $this
				->render('ARIPDMobileBundle:Default:login.html.twig',
						array('csrf_token' => $csrfToken,));

	}

	/**
	 * @Route("/share", name="aripd_mobile_default_share")
	 * @Template()
	 */
	public function shareAction() {
		return $this->render('ARIPDMobileBundle:Default:share.html.twig');
	}
	
}
