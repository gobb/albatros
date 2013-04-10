<?php
namespace ARIPD\UserBundle\Controller;

use ARIPD\UserBundle\Form\Model\StoreSearchFormModel;
use ARIPD\UserBundle\Form\Type\StoreSearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/store/search")
 */
class StoreSearchController extends Controller {
	public function formAction() {
		$entity = new StoreSearchFormModel();
		$form = $this->createForm(new StoreSearchFormType(), $entity);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/StoreSearch/form.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/index", name="aripd_user_store_search_index")
	 * @Template()
	 * @Secure(roles="ROLE_WRITER")
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function indexAction() {
		$entity = new StoreSearchFormModel();
		$form = $this->createForm(new StoreSearchFormType(), $entity);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
				//$data = $form->getData();
				$postData = $request->request
						->get('aripduser_storesearchformtype');
				return $this
						->redirect(
								$this
										->generateUrl(
												'aripd_user_store_product_result',
												array('q' => $postData['name'])));
			}
		}

	}

	public function resultAction($q) {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Product')
				->createQueryBuilder('p')->select(array('p'))
				->where('p.name LIKE ?1')->setParameter(1, "%$q%")->getQuery()
				->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 5);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/StoreSearch/index.html.twig',
						array('entities' => $entities,
								'pagination' => $pagination, 'q' => $q,));
	}

}
