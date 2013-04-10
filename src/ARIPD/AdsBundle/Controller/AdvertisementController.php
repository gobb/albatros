<?php
namespace ARIPD\AdsBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDAdsBundle:Advertisement
 * 
 * @Route("/advertisement")
 */
class AdvertisementController extends Controller {
	
	/**
	 * @param number $id
	 * 
	 * @Route("/{id}/redir", requirements={"id" = "\d+"}, name="aripd_ads_advertisement_redir")
	 * @Template()
	 */
	public function redirAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDAdsBundle:Advertisement')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$this->container->get('aripdads.ads_service')->saveHit($entity);

		return new RedirectResponse($entity->getHref());
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/slider", name="aripd_ads_advertisement_slider")
	 * @Template()
	 */
	public function sliderAction() {
		$em = $this->getDoctrine()->getManager();

		$qb = $em->getRepository('ARIPDAdsBundle:Advertisement')->createQueryBuilder('a');
		$qb->leftJoin('a.banner', 'b');
		$qb->where('b.name = ?1')->setParameter(1, 'Leaderboard');
		$entities = $qb->getQuery()->getResult();
		
		foreach ($entities as $entity) {
			$this->container->get('aripdads.ads_service')->saveView($entity);
		}

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDAdsBundle/Advertisement/slider.html.twig',
						compact('entities'));
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/showbybanner", name="aripd_ads_advertisement_showbybanner")
	 * @Template()
	 */
	public function showByBannerAction() {
		$em = $this->getDoctrine()->getManager();

		$qb = $em->getRepository('ARIPDAdsBundle:Advertisement')->createQueryBuilder('a');
		$qb->leftJoin('a.banner', 'b');
		$qb->where('b.name = ?1')->setParameter(1, 'Square Pop-Up');
		$entities = $qb->getQuery()->getResult();
		
		foreach ($entities as $entity) {
			$this->container->get('aripdads.ads_service')->saveView($entity);
		}

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDAdsBundle/Advertisement/list.html.twig',
						compact('entities'));
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/showbytags", name="aripd_ads_advertisement_showbytags")
	 * @Template()
	 */
	public function showByTagsAction($maxResults = 1) {
		$tags = $this->container->get('session')->get('aripdads_tags');

		$em = $this->getDoctrine()->getManager();

		$qb = $em->getRepository('ARIPDAdsBundle:Advertisement')
				->createQueryBuilder('a');
		$qb->select(array('a', 't'));
		$qb->leftJoin('a.tags', 't');
		if ($tags) {
			foreach ($tags as $k => $tag) {
				if ($k == 0)
					$qb->where("t.name = '" . $tag->getName() . "'");
				else
					$qb->orWhere("t.name = '" . $tag->getName() . "'");
			}
		}
		$qb->groupBy('a.id');
		$qb->setMaxResults($maxResults);
		$entities = $qb->getQuery()->getResult();

		foreach ($entities as $entity) {
			$this->container->get('aripdads.ads_service')->saveView($entity);
		}

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDAdsBundle/Advertisement/list.html.twig',
						compact('entities'));
	}

}
