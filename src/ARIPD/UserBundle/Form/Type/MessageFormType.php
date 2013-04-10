<?php
namespace ARIPD\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MessageFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('subject');
		$builder->add('body');
	}

	public function getName() {
		return 'aripduser_messageformtype';
	}
}
