<?php
namespace ARIPD\UserBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StoreSearchFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text');
	}

	public function getName() {
		return 'aripduser_storesearchformtype';
	}
}
