<?php
namespace ARIPD\MobileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('username', 'text');
		$builder->add('password', 'password');
	}

	public function getName() {
		return 'aripdmobile_loginformtype';
	}
}
