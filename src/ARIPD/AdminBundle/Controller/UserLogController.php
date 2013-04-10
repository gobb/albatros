<?php
namespace ARIPD\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that lists user logs
 * 
 * @Route("/user/log")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class UserLogController extends Controller {

	/**
	 * Method that gets data in json format to use in datatable
	 * 
	 * @return Ambigous <\LanKit\DatatablesBundle\Datatables\mixed, mixed>
	 * 
	 * @Route("/data", name="aripd_admin_userlog_data")
	 */
	public function dataAction() {
		$datatable = $this->get('lankit_datatables')->getDatatable('ARIPDUserBundle:Log');
		return $datatable->getSearchResults();
	}
	
	/**
	 * Lists user logs
	 * 
	 * @Route("/index", name="aripd_admin_userlog_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDUserBundle:Log')
				->createQueryBuilder('l')->orderBy('l.createdAt', 'DESC')
				->getQuery()->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 10);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:UserLog:index.html.twig',
						compact('pagination'));
	}

}
