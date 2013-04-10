<?php
namespace ARIPD\ShoppingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VoucherFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('vouchercode', 'text',
						array('label' => 'İndirim Çeki'));
	}

	public function getName() {
		return 'aripdshopping_voucherformtype';
	}
}
