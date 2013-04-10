<?php
namespace ARIPD\ShoppingBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDShoppingBundle:Paymentgrouptype
 * 
 * @Route("/paymentgrouptype")
 */
class PaymentgrouptypeController extends Controller {

	/**
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/list", name="aripd_shopping_paymentgrouptype_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDShoppingBundle:Paymentgrouptype')
				->createQueryBuilder('p')
				->orderBy('p.id', 'ASC')
				->getQuery()->getResult();

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDShoppingBundle/Paymentgrouptype/list.html.twig',
						compact('entities'));
	}

}
