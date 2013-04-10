<?php
namespace ARIPD\AdminBundle\Controller;

use ARIPD\AdminBundle\Form\Type\CMSSearchFormType;
use ARIPD\AdminBundle\Form\Model\CMSSearchFormModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDCMSBundle:Search
 * 
 * @Route("/cms/search")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class CMSSearchController extends Controller {
	
	/**
	 * Generates form
	 */
	public function formAction() {
		$entity = new CMSSearchFormModel();
		$form = $this->createForm(new CMSSearchFormType(), $entity);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:CMSSearch:form.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/index", name="aripd_admin_cms_search_index")
	 * @Method("POST")
	 * @Template()
	 */
	public function indexAction() {
		$entity = new CMSSearchFormModel();
		$form = $this->createForm(new CMSSearchFormType(), $entity);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
				//$data = $form->getData();
				$postData = $request->request
						->get('aripdadmin_cmssearchformtype');
				return $this
						->redirect(
								$this
										->generateUrl(
												'aripd_admin_cms_search_result',
												array('q' => $postData['name'])));
			}
		}

	}

	/**
	 * @Route("/{q}/result", name="aripd_admin_cms_search_result")
	 * @Template()
	 */
	public function resultAction($q) {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDCMSBundle:Post')
				->createQueryBuilder('p')->select(array('p'))
				->where('p.name LIKE ?1')->setParameter(1, "%$q%")->getQuery()
				->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 5);

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:CMSPost:index.html.twig',
						array('entities' => $entities,
								'pagination' => $pagination, 'q' => $q,));
	}
	
}
