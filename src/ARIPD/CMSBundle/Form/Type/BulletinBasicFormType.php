<?php
namespace ARIPD\CMSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BulletinBasicFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('emailaddress', 'email',
						array('label' => 'label.emailaddress',));
	}

	public function getName() {
		return 'aripdcms_bulletinbasicformtype';
	}
}
