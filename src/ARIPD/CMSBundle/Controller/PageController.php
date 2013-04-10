<?php
namespace ARIPD\CMSBundle\Controller;

use ARIPD\CMSBundle\Form\Type\BulletinBasicFormType;
use ARIPD\CMSBundle\Form\Model\BulletinBasicEnquiry;
use ARIPD\CMSBundle\Form\Model\ContactEnquiry;
use ARIPD\CMSBundle\Form\Type\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Page
 * 
 * @Route("/page")
 */
class PageController extends Controller {

	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_cms_page_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDCMSBundle:Page')
				->findOneByInitial(1);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Page/show.html.twig',
						array('entity' => $entity,));
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_cms_page_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDCMSBundle:Page')->createQueryBuilder('p')
		->leftJoin('p.iso639', 'i')
		->where('i.a2 = ?1')->setParameter(1, $this->get('request')->getLocale())
		->getQuery()->getResult();

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Page/list.html.twig',
						compact('entities'));
	}

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/{slug}/show", requirements={"id" = "\d+", "slug" = "[^/]+"}, name="aripd_cms_page_show")
	 * @Template()
	 */
	public function showAction($id, $slug) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDCMSBundle:Page')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Page/show.html.twig',
						compact('entity'));
	}

	/**
	 * @Route("/contact", name="aripd_cms_page_contact")
	 * @Template()
	 */
	public function contactAction() {
		$enquiry = new ContactEnquiry();
		$form = $this->createForm(new ContactFormType(), $enquiry);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {

				$message = \Swift_Message::newInstance()
						->setSubject('Contact enquiry')
						->setFrom($form->getData()->getEmail())
						->setTo($this->container->getParameter('administrators'))
						->setBody(
								$this
										->renderView(
												'ARIPDCMSBundle:Page:contactEmail.txt.twig',
												array('enquiry' => $enquiry)));

				$this->get('mailer')->send($message);

				$this->get('session')
						->getFlashBag()->add('global-notice',
								$this->get('translator')
										->trans(
												'flash.page.contact.message.send.completed'));

				return $this->redirect($this->generateUrl('aripd_default_index'));
				
			}
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Page/contact.html.twig',
						array('form' => $form->createView(),));
	}

}
