<?php
namespace ARIPD\ShoppingBundle\Controller;

use ARIPD\ShoppingBundle\Form\Type\VoucherFormType;
use ARIPD\ShoppingBundle\Form\Model\VoucherEnquiry;
use ARIPD\ShoppingBundle\Entity\Voucher;
use ARIPD\ShoppingBundle\Entity\Vouchercode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDShoppingBundle:Voucher
 * 
 * @Route("/voucher")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class VoucherController extends Controller {
	
	/**
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/form", name="aripd_shopping_voucher_form")
	 * @Template()
	 */
	public function formAction() {
		$session = $this->get('session');

		$enquiry = new VoucherEnquiry();
		$enquiry->setVouchercode($session->get('vouchercode'));

		$form = $this->createForm(new VoucherFormType(), $enquiry);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {

				$sVouchercode = $form->getData()->getVouchercode();

				if (!empty($sVouchercode)) {
					$em = $this->getDoctrine()->getManager();
					if (
							$em->getRepository('ARIPDShoppingBundle:Vouchercode')->findOneByVouchercode($sVouchercode)
							||
							$em->getRepository('ARIPDShoppingBundle:Voucher')->findOneByCode($sVouchercode)
							) {
						$session->set('vouchercode', $sVouchercode);
					} else {
						$session->remove('vouchercode');
					}
				} else {
					$session->remove('vouchercode');
				}

				return $this
						->redirect(
								$this
										->generateUrl(
												'aripd_shopping_payment_list'));
			}
		}

		return $this->container->get('templating')
				->renderResponse('::ARIPDShoppingBundle/Voucher/form.html.twig',
						array('form' => $form->createView(),));
	}
	
}
