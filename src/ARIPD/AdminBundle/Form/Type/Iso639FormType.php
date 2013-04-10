<?php

namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\AdminBundle\Entity\Iso4217;

use ARIPD\AdminBundle\Entity\Iso639;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Iso639FormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('a2')->add('a3')->add('name')->add('native')
				->add('active');
		$builder
				->add('iso4217', 'entity',
						array('class' => get_class(new Iso4217()),
								'property' => 'code',
								'required' => true, 'label' => 'label.iso4217',));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Iso639()),
		));
	}

	public function getName() {
		return 'aripd_adminbundle_iso639formtype';
	}
}
