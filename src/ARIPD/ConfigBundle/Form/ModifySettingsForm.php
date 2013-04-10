<?php

namespace ARIPD\ConfigBundle\Form;

use ARIPD\ConfigBundle\Form\Type\SettingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ModifySettingsForm extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('settings', 'collection', array(
			'type' => new SettingType(),
		));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'aripd_config_modifySettings';
	}

}
