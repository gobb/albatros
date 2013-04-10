<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\AdminBundle\Entity\Iso4217;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Iso4217FormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name')->add('code')->add('symbol')->add('active')
				->add('decimalPlaces')->add('decimalPoint')
				->add('thousandsSeperator')->add('rate');
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Iso4217()),
		));
	}

	public function getName() {
		return 'aripdadmin_iso4217formtype';
	}
	
}
