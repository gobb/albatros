<?php
namespace ARIPD\ShoppingBundle\Form\Type;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use ARIPD\ShoppingBundle\Entity\Vouchercode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VouchercodeFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('quantity', 'integer', array('label' => 'Quantity'));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Vouchercode()),
						'translation_domain' => 'ARIPDShoppingBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdshopping_vouchercodeformtype';
	}

}
