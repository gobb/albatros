<?php
namespace ARIPD\AdminBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserSearchFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text');
	}

	public function getName() {
		return 'aripdadmin_usersearchtype';
	}
}
