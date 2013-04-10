<?php
namespace ARIPD\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDStoreBundle:Banner
 *
 * @Route("/banner")
 */
class BannerController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_store_banner_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Banner')
				->createQueryBuilder('b')->select('b')
				->where('?1 BETWEEN b.startingAt AND b.endingAt')
				->setParameter(1, new \DateTime())
				->orderBy('b.sorting', 'ASC')
				->getQuery()->getResult();

		return $this->container->get('templating')
				->renderResponse('::ARIPDStoreBundle/Banner/list.html.twig',
						compact('entities'));
	}
	
}
