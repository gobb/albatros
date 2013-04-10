<?php
namespace ARIPD\CRMBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoyaltyFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('token');
	}

	public function getName() {
		return 'aripdcrm_loyaltyformtype';
	}
}
