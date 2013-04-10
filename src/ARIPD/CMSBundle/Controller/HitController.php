<?php
namespace ARIPD\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Hit
 *
 * @Route("/hit")
 */
class HitController extends Controller {

	/**
	 * @Route("/{maxResults}/most", requirements={"maxResults" = "\d+"}, name="aripd_cms_hit_most")
	 * @Template()
	 */
	public function mostAction($maxResults) {
		$em = $this->getDoctrine()->getManager();

		$qb = $em->getRepository('ARIPDCMSBundle:Hit')->createQueryBuilder('h');
		$qb->select(array('p.id', 'p.name', 'p.slug', 'p.description', 'COUNT(h.id) as nofHits'));
		$qb->leftJoin('h.post', 'p');
		/*
		if ($interval == '24h') {
			$qb->where('h.createdAt = ?1')->setParameter(1, true);
			$qb->expr()->gte("h.createdAt", "DATE_SUB(NOW(), 7, 'day')");
		}
		*/
		$qb->where('p.approved = ?2')->setParameter(2, true);
		$qb->orderBy('nofHits', 'desc');
		$qb->addOrderBy('p.createdAt', 'desc');
		$qb->groupBy('h.post');
		//if ($unique_visitor) $qb->addGroupBy('h.sessionId');
		$qb->setMaxResults($maxResults);

		/**
		 * TODO yukarıdaki şekilde $entities döndürürsen defaultimage alamıyorsun.
		 * Bu nedenle id lerini döngüye sokup post nesnelerini döndürüyorum
		 */
		//$entities = $qb->getQuery()->getResult();
		$results = $qb->getQuery()->getResult();

		$entities = array();
		foreach ($results as $result) {
			$entities[] = $em->getRepository('ARIPDCMSBundle:Post')
					->find($result['id']);
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Hit/most.html.twig',
						compact('entities'));
	}

}