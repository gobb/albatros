<?php
namespace ARIPD\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:Search
 * 
 * @Route("/search")
 */
class SearchController extends Controller {
	
	/**
	 * @Route("/result", name="aripd_cms_search_result")
	 * @Template()
	 */
	public function resultAction() {
		$request = $this->getRequest();
		$q = $request->query->get('q');
		
		$this->container->get('aripdcms.search_service')->saveSearch($q);

		$em = $this->getDoctrine()->getManager();

		$client = $this->get('solarium.client');
		$ping = $client->createPing();
		try {
			$query = $client->createSelect();
			
			$resultset = $client->select($query);
			
			header('Content-Type: text/html; charset=utf-8');
			echo 'NumFound: '.$resultset->getNumFound();
			
			foreach ($resultset as $document) {
				echo '<hr/><table>';
				//echo '<tr><th>name</th><td>' . $document->name . '</td></tr>';
				/**/
				foreach($document AS $field => $value) {
					if(is_array($value)) $value = implode(', ', $value);
					echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
				}
				/**/
				echo '</table>';
			}
			
			
			exit;
			
		} catch (\Solarium_Exception $e) {
			$entities = $em->getRepository('ARIPDCMSBundle:Post')
					->createQueryBuilder('p')->select(array('p', 'u'))
					->leftJoin('p.user', 'u')->where('p.approved = ?1')
					->setParameter(1, true)
					->andWhere(
							'p.name LIKE ?3 OR u.username LIKE ?4 OR u.firstname LIKE ?5 OR u.lastname LIKE ?6')
					->setParameter(3, "%$q%")->setParameter(4, "%$q%")
					->setParameter(5, "%$q%")->setParameter(6, "%$q%")
					->getQuery()->getResult();
		}

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 12);

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Search/result.html.twig',
						array('entities' => $entities, 'pagination' => $pagination, 'q' => $q,));
	}
	
	/**
	 * @Route("/{tag}/tag", requirements={"tag" = "[^/]+"}, name="aripd_cms_search_tag")
	 * @Template()
	 */
	public function tagAction($tag) {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDCMSBundle:Post')
				->createQueryBuilder('p')->select(array('p', 't'))
				->innerJoin('p.tags', 't')->where('t.name = ?1')
				->setParameter(1, $tag)->andWhere('p.approved = ?2')
				->setParameter(2, true)->orderBy('p.updatedAt', 'DESC')
				->getQuery()->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 12);

		return $this->container->get('templating')
				->renderResponse('::ARIPDCMSBundle/Search/result.html.twig',
						array('pagination' => $pagination, 'q' => $tag,));
	}

}
