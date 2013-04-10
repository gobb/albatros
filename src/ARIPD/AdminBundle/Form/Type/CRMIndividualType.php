<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\CRMBundle\Entity\Individual;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CRMIndividualType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('firstname')->add('middlename')->add('lastname');

		$builder->add('idno');

		$builder
				->add('birthdate', 'date',
						array('widget' => 'single_text',
								'label' => 'label.birthdate',
								'required' => false));

		$builder
				->add('gender', 'choice',
						array('label' => 'label.gender',
								'choices' => array(
										'M' => 'Male',
										'F' => 'Female'),
								'expanded' => true,));
		$builder
				->add('tags', 'collection',
						array('type' => new CRMTagType(), 'allow_add' => true,
								'allow_delete' => true,
								'by_reference' => false,
						)
				);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Individual()),
						'translation_domain' => 'ARIPDCRMBundle',
				)
		);
	}

	public function getName() {
		return 'aripdadmin_crmindividualtype';
	}
}
