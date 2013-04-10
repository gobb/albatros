<?php
namespace ARIPD\DMSBundle\Form;

use ARIPD\DMSBundle\Entity\SalesorderStatus;

use ARIPD\DMSBundle\Entity\Salesorder;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SalesorderType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('content');
		$builder
				->add('status', 'entity',
						array(
								'class' => get_class(new SalesorderStatus()),
								'property' => 'name', 
								'required' => true,
						)
				);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Salesorder()),
		));
	}

	public function getName() {
		return 'aripd_dmsbundle_salesordertype';
	}
	
}
