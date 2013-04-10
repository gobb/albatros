<?php
namespace ARIPD\AdminBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StoreBrandFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name',));
		$builder->add('url', 'text', array('label' => 'label.url', 'required' => false));
	}

	public function getName() {
		return 'aripdadmin_storebrandformtype';
	}
}
