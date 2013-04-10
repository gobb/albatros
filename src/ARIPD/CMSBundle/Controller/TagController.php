<?php
namespace ARIPD\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Subtopic
 *
 * @Route("/tag")
 */
class TagController extends Controller {

	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_cms_tag_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$total = $em->getRepository('ARIPDCMSBundle:Tag')->createQueryBuilder('t')
				->select('COUNT(t.id)')
				->getQuery()->getSingleScalarResult();
		
		$entities = $em->getRepository('ARIPDCMSBundle:Tag')->createQueryBuilder('t')
				->select(array("t.name", "COUNT(t.id)/$total*100 as weight"))
				->groupBy('t.name')
				->orderBy('t.name')
				->getQuery()->getResult();
		
		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Tag/list.html.twig',
						compact('entities'));
	}

}
