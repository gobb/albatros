<?php
namespace ARIPD\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Faq
 *
 * @Route("/faq")
 */
class FaqController extends Controller {

	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_cms_faq_index")
	 * @Template()
	 */
	public function indexAction() {
		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Faq/index.html.twig');
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_cms_faq_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDCMSBundle:Faq')->createQueryBuilder('f')
				->leftJoin('f.iso639', 'i')
				->where('i.a2 = ?1')->setParameter(1, $this->get('request')->getLocale())
				->getQuery()->getResult();

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Faq/list.html.twig',
						compact('entities'));
	}

}
