<?php
namespace ARIPD\CMSBundle\Controller;

use ARIPD\CMSBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Comment
 * 
 * @Route("/comment")
 */
class CommentController extends Controller {
	
	/**
	 * @Route("/{post_id}/list", requirements={"post_id" = "\d+"}, name="aripd_cms_comment_list")
	 * @Template()
	 */
	public function listAction($post_id) {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDCMSBundle:Comment')
				->createQueryBuilder('c')->where('c.post = ?1')
				->setParameter(1, $post_id)->andWhere('c.approved = ?2')
				->setParameter(2, true)->getQuery()->getResult();

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Comment/list.html.twig',
						compact('entities'));
	}

	/**
	 * @Route("/{maxResults}/most", requirements={"maxResults" = "\d+"}, name="aripd_cms_comment_most")
	 * @Template()
	 */
	public function mostAction($maxResults = 3) {
		$em = $this->getDoctrine()->getManager();

		$qb = $em->getRepository('ARIPDCMSBundle:Comment')
				->createQueryBuilder('c')
				->select(
						array('p.id', 'p.name', 'p.slug', 'p.description',
								'COUNT(c.id) as nofComments'))
				->leftJoin('c.post', 'p')->where('c.approved = ?2')
				->setParameter(2, true)->andWhere('p.approved = ?3')
				->setParameter(3, true)->orderBy('nofComments', 'desc')
				->addOrderBy('p.createdAt', 'desc')->groupBy('c.post')
				->setMaxResults($maxResults);

		//$entities = $qb->getQuery()->getResult();
		$results = $qb->getQuery()->getResult();

		$entities = array();
		foreach ($results as $result) {
			$entities[] = $em->getRepository('ARIPDCMSBundle:Post')
					->find($result['id']);
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Comment/most.html.twig',
						compact('entities'));
	}

}
