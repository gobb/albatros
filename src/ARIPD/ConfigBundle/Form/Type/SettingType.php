<?php

namespace ARIPD\ConfigBundle\Form\Type;

use ARIPD\ConfigBundle\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingType extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'hidden');
		$builder->add('section', 'hidden');
		$builder->add('value', null, array(
			'required' => false,
		));
	}

	/**
	 * {@inheritDoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => get_class(new Setting()),
		));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'aripd_config_setting';
	}

}
