<?php
namespace ARIPD\CMSBundle\Controller;

use ARIPD\CMSBundle\Entity\Bulletin;
use ARIPD\CMSBundle\Form\Type\BulletinFormType;
use ARIPD\CMSBundle\Form\Model\BulletinEnquiry;
use ARIPD\CMSBundle\Form\Type\BulletinBasicFormType;
use ARIPD\CMSBundle\Form\Model\BulletinBasicEnquiry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Bulletin
 * 
 * @Route("/bulletin")
 */
class BulletinController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_cms_bulletin_index")
	 * @Template()
	 */
	public function indexAction() {
		$enquiry = new BulletinEnquiry();
		$form = $this->createForm(new BulletinFormType(), $enquiry);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {

				$em = $this->getDoctrine()->getManager();

				if ($bulletin = $em->getRepository('ARIPDCMSBundle:Bulletin')
						->findOneBy(
								array(
										'emailaddress' => $form->getData()
												->getEmailaddress()))) {
					$this->get('session')
							->getFlashBag()->add('global-notice',
									$this->get('translator')
											->trans(
													'You have already registered our bulletin list', array(), 'ARIPDCMSBundle'));
				} else {
					$bulletin = new Bulletin();
					$bulletin
							->setEmailaddress(
									$form->getData()->getEmailaddress());
					$bulletin->setGender($form->getData()->getGender());
					$em->persist($bulletin);
					$em->flush();

					$message = \Swift_Message::newInstance()
							->setSubject('E-bülten kayıt')
							->setFrom($this->container->getParameter('mail_sender_address'))
							->setTo($form->getData()->getEmailaddress())
							->setBody(
									$this
											->renderView(
													'::ARIPDCMSBundle/Bulletin/bulletinEmail.txt.twig',
													array(
															'bulletin' => $bulletin)));
					
					$this->get('mailer')->send($message);

					$this->get('session')
							->getFlashBag()->add('global-notice',
									$this->get('translator')
											->trans(
													'Bulletin registration has been completed', array(), 'ARIPDCMSBundle'));
				}

				return $this
						->redirect(
								$this->generateUrl('aripd_cms_bulletin_index'));
			}

		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Bulletin/index.html.twig',
						array('form' => $form->createView(),));
	}
	
	/**
	 * Lists entities
	 * 
	 * @Route("/basic", name="aripd_cms_bulletin_basic")
	 * @Template()
	 */
	public function basicAction() {
		$enquiry = new BulletinBasicEnquiry();
		$form = $this->createForm(new BulletinBasicFormType(), $enquiry);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {

				$em = $this->getDoctrine()->getManager();

				if ($bulletin = $em->getRepository('ARIPDCMSBundle:Bulletin')
						->findOneBy(
								array(
										'emailaddress' => $form->getData()
												->getEmailaddress()))) {
					$this->get('session')
							->getFlashBag()->add('global-notice',
									$this->get('translator')
											->trans(
													'You have already registered our bulletin list', array(), 'ARIPDCMSBundle'));
				} else {
					$bulletin = new Bulletin();
					$bulletin
							->setEmailaddress(
									$form->getData()->getEmailaddress());
					$em->persist($bulletin);
					$em->flush();

					$message = \Swift_Message::newInstance()
							->setSubject('E-bülten kayıt')
							->setFrom(
									$this->container
											->getParameter('mail_sender_address'))
							->setTo($form->getData()->getEmailaddress())
							->setBody(
									$this
											->renderView(
													'::ARIPDCMSBundle/Bulletin/bulletinBasicEmail.txt.twig',
													array(
															'bulletin' => $bulletin)));

					$this->get('mailer')->send($message);

					$this->get('session')
							->getFlashBag()->add('global-notice',
									$this->get('translator')
											->trans(
													'Bulletin registration has been completed', array(), 'ARIPDCMSBundle'));
				}

			} else {
				$this->get('session')
						->getFlashBag()->add('global-notice',
								$this->get('translator')
										->trans(
												'Bulletin registration has not been completed. Please try again.', array(), 'ARIPDCMSBundle'));
			}
			
			return $this->redirect($this->generateUrl('aripd_default_index'));
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Bulletin/basic.html.twig',
						array('form' => $form->createView(),));
	}
	
	/**
	 * @Route("/{token}/delete", requirements={"token" = "[^/]+"}, name="aripd_cms_bulletin_delete")
	 * @Template()
	 */
	public function deleteAction($token) {
		$em = $this->getDoctrine()->getManager();
		$bulletin = $em->getRepository('ARIPDCMSBundle:Bulletin')
				->findOneBy(array('token' => urldecode($token)));

		if (!$bulletin) {
			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans(
											'E-mail address has not found', array(), 'ARIPDCMSBundle'));
		} else {
			$em->remove($bulletin);
			$em->flush();
			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans(
											'You have been removed from the bulletin list', array(), 'ARIPDCMSBundle'));
		}

		return $this->redirect($this->generateUrl('aripd_cms_bulletin_index'));
	}

}
