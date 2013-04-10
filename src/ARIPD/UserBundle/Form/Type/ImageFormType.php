<?php
namespace ARIPD\UserBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ImageFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name');
		$builder->add('file', 'file', array('label' => 'label.file'));
	}

	public function getName() {
		return 'aripduser_imageformtype';
	}
}
