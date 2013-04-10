<?php

namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\AdminBundle\Entity\Iso3166;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Iso3166FormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name')->add('native')->add('an')->add('a2')->add('a3')
				->add('phonecode')->add('active');
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Iso3166()),
		));
	}

	public function getName() {
		return 'aripdadmin_iso3166formtype';
	}
}
