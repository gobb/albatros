<?php
namespace ARIPD\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Post
 * 
 * @Route("/post")
 */
class PostController extends Controller {

	/**
	 * @Route("/archive", name="aripd_cms_post_archive")
	 * @Template()
	 */
	public function archiveAction() {
		$entities = $this->get('aripd_cms.post_service')->getHistory();
		
		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Post/archive.html.twig',
						compact('entities'));
	}

	/**
	 * @Route("/{maxResults}/latest", requirements={"maxResults" = "\d+"}, name="aripd_cms_post_latest")
	 * @Template()
	 */
	public function latestAction($maxResults) {
		$em = $this->getDoctrine()->getManager();

		$qb = $em->getRepository('ARIPDCMSBundle:Post')
				->createQueryBuilder('p');
		$qb->select(array('p', 't'));
		$qb->leftJoin('p.topic', 't');
		$qb->leftJoin('t.iso639', 'i');
		$qb->where('p.approved = ?1')->setParameter(1, true);
		$qb->andWhere('i.a2 = ?3')
				->setParameter(3, $this->get('request')->getLocale());
		$qb->andWhere('p.publishedAt <= ?4')->setParameter(4, new \DateTime());
		$qb->orderBy('p.publishedAt', 'DESC');
		$qb->setMaxResults($maxResults);
		$entities = $qb->getQuery()->getResult();

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Post/latest.html.twig',
						compact('entities'));
	}

	/**
	 * Developer note
	 * ".+" means that all characters are allowed
	 * "[^/]+" means that all characters are allowed except /.
	 * "\d+" means that only numbers are allowed
	 * "\w+" means that only words are allowed
	 * 
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * @param string $slug
	 * 
	 * @Route("/{id}/{slug}/show", requirements={"id" = "\d+", "slug" = "[^/]+"}, name="aripd_cms_post_show")
	 * @Template()
	 */
	public function showAction($id, $slug) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Post')
				->findOneBy(array('approved' => true, 'id' => $id));

		if (!$entity) {
			//throw $this->createNotFoundException('Unable to find');
			throw new \RuntimeException(
					sprintf('Post "%s" couldn\'t be found.', $id));
		}

		$this->container->get('session')->remove('aripdads_tags');
		$this->container->get('session')->set('aripdads_tags', $entity->getTags());
		$this->container->get('aripdcms.post_service')->saveHit($entity);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDCMSBundle/Post/'
								. $entity->getTopic()->getTemplatePost(),
						array('entity' => $entity,));
	}

}
