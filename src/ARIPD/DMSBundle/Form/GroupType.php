<?php
namespace ARIPD\DMSBundle\Form;

use ARIPD\DMSBundle\Entity\Group;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name',));
		$builder->add('impactRate', 'percent', array('label' => 'Impact Rate','required' => true,));
		$builder->add('impactPrice', 'money', array('label' => 'Impact Price','required' => true,));
		$builder->add('iso4217', 'entity', array(
				'class' => get_class(new Iso4217()),
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('i');//->where('i.active = ?1')->setParameter(1, true);
				},
				'required' => true,
			)
		);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Group()),
				'translation_domain' => 'ARIPDDMSBundle',
		));
	}

	public function getName() {
		return 'aripd_dmsbundle_grouptype';
	}
	
}
