<?php
namespace ARIPD\ShoppingBundle\Controller;
use ARIPD\AdminBundle\Util\ARIPDString;

use ARIPD\ShoppingBundle\Entity\Vouchercode;
use ARIPD\ShoppingBundle\Form\Type\VouchercodeFormType;
use ARIPD\ShoppingBundle\Form\Model\VouchercodeFormModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDShoppingBundle:Vouchercode
 * 
 * @Route("/vouchercode")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class VouchercodeController extends Controller {

	/**
	 * Generate codes
	 * 
	 * @param number $voucher_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{voucher_id}/generate", name="aripd_shopping_vouchercode_generate")
	 * @Template()
	 */
	public function generateAction($voucher_id) {
		$em = $this->getDoctrine()->getManager();

		$voucher = $em->getRepository('ARIPDShoppingBundle:Voucher')
				->find($voucher_id);

		$enquiry = new VouchercodeFormModel();
		$enquiry->setVoucher($voucher);

		$form = $this->createForm(new VouchercodeFormType(), $enquiry);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
				foreach (range(1, $form->getData()->getQuantity()) as $k) {
					$vouchercode = new Vouchercode();
					$vouchercode->setVoucher($voucher);
					$vouchercode
							->setVouchercode(
									'U' . ARIPDString::randomUniqueString());
					$em->persist($vouchercode);
				}
				$em->flush();
			}

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_shopping_voucher_show',
											array('id' => $voucher->getId())));
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDShoppingBundle:Vouchercode:form.html.twig',
						array('voucher' => $voucher,
								'form' => $form->createView(),));
	}

}
