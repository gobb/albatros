<?php
namespace ARIPD\MobileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserPostaladdressFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name')->add('address')->add('county')->add('city')
				->add('postalcode')->add('country')->add('latitude')
				->add('longitude')->add('active');
	}

	public function getName() {
		return 'aripdmobile_userpostaladdressformtype';
	}
	
}
