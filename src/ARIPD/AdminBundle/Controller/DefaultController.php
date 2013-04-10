<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\AdminBundle\Form\Type\ContactFormType;
use ARIPD\AdminBundle\Form\Model\ContactFormModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/")
 */
class DefaultController extends Controller {
	
	/**
	 * Login page for admin
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/login", name="aripd_admin_default_login")
	 * @Template()
	 */
	public function loginAction() {
		/*
		  $entity = new LoginFormModel();
		$form = $this->createForm(new LoginFormType(), $entity);
		
		return $this
		->render('ARIPDAdminBundle:Security:login.html.twig',
		    array('form' => $form->createView(),));
		 */

		/*
		if (true
				=== $this->container->get('security.context')
						->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this
					->redirect($this->generateUrl('aripd_admin_default_index'));
		}

		$csrfToken = $this->container->get('form.csrf_provider')
				->generateCsrfToken('authenticate');

		return $this
				->render('ARIPDAdminBundle:Security:login.html.twig',
						array('csrf_token' => $csrfToken,));
						*/
		
		$request = $this->getRequest();
		$session = $request->getSession();
		
		$error = $request->attributes->get(
				SecurityContext::AUTHENTICATION_ERROR, $session->get(SecurityContext::AUTHENTICATION_ERROR)
		);
		
		if (true === $this->get('security.context')->isGranted('ROLE_EDITOR' || 'ROLE_ADMIN')) {
			//throw new AccessDeniedException();
			return $this->redirect($this->generateUrl('usuario_aplicacion'));
		} else {
			return $this->render('ARIPDAdminBundle:Security:login.html.twig', array(
					'last_username' => $session->get(SecurityContext::LAST_USERNAME),
					'error' => $error
			));
		}

	}
	
	/**
	 * Shows dashboard
	 * 
	 * @Route("/", name="aripd_admin_default_index")
	 * @Template()
	 * @Secure(roles="ROLE_ADMIN, ROLE_EDITOR")
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$products = $em->getRepository('ARIPDStoreBundle:Product')->findAll();

		$users = $em->getRepository('ARIPDUserBundle:User')->findAll();

		$iso4217s = $em->getRepository('ARIPDAdminBundle:Iso4217')->findAll();
		
		$advertisements = $em->getRepository('ARIPDAdsBundle:Advertisement')->findAll();

		$logs = $em->getRepository('ARIPDUserBundle:Log')
				->createQueryBuilder('l')->setMaxResults(10)
				->orderBy('l.createdAt', 'DESC')->getQuery()->getResult();

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:Default:index.html.twig',
						compact('products', 'users', 'advertisements', 'logs', 'iso4217s'));
	}

	/**
	 * Shows sidebar
	 * 
	 * @Route("/sidebar", name="aripd_admin_default_sidebar")
	 * @Template()
	 * @Secure(roles="ROLE_ADMIN, ROLE_EDITOR")
	 */
	public function sidebarAction() {
		$config = $this->container->get('aripd_config')->all();

		return $this->container->get('templating')
				->renderResponse('::adminsidebar.html.twig',
						compact('config'));
	}
	
	/**
	 * Shows support form
	 * 
	 * @Route("/contact", name="aripd_admin_default_contact")
	 * @Template()
	 * @Secure(roles="ROLE_ADMIN, ROLE_EDITOR")
	 */
	public function contactAction() {
		$enquiry = new ContactFormModel();
		$form = $this->createForm(new ContactFormType(), $enquiry);
	
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);
	
			if ($form->isValid()) {
	
				// Ref: https://groups.google.com/forum/?fromgroups=#!topic/symfony2/nr85Gno8fNE
				//$this->getContainer()->get('translator')->setLocale($reminder->getLocale());
				
				$message = \Swift_Message::newInstance()
						->setSubject('Support Request')
						->setFrom($form->getData()->getEmail())
						->setTo($this->container->getParameter('administrators'))
						->setBody($this->renderView(
								'ARIPDAdminBundle:Default:contactEmail.txt.twig',
								array('enquiry' => $enquiry)));
	
				$this->get('mailer')->send($message);
	
				$this->get('session')->getFlashBag->add('global-notice',
						$this->get('translator')->trans('flash.message.send.ok'));
			} else {
				$this->get('session')->getFlashBag->add('global-notice',
						$this->get('translator')->trans('flash.message.send.nok'));
			}
	
			return $this->redirect($this->generateUrl('aripd_admin_default_index'));
		}
	
		return array('form' => $form->createView());
	}
	
}
