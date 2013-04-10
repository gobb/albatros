<?php
namespace ARIPD\StoreBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AttributekeyType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('code');
		$builder->add('name');
	}

	public function getName() {
		return 'aripd_storebundle_attributekeytype';
	}
	
}
