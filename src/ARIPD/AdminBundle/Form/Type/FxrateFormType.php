<?php

namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\AdminBundle\Entity\Fxrate;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FxrateFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('date')->add('code')->add('forexBuying')
				->add('forexSelling')->add('banknoteBuying')
				->add('banknoteSelling');
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Fxrate()),
		));
	}

	public function getName() {
		return 'aripdadmin_fxrateformtype';
	}
}
