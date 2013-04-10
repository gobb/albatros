<?php
namespace ARIPD\DMSBundle\Form;
use Doctrine\ORM\EntityRepository;

use ARIPD\UserBundle\Entity\User;

use ARIPD\DMSBundle\Entity\Group;
use ARIPD\DMSBundle\Entity\Dealer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DealerType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name');
		$builder
				->add('group', 'entity',
						array('class' => get_class(new Group()),
								'property' => 'name',));
		/*
		$builder
				->add('users', 'entity',
						array('class' => get_class(new User()),
								'query_builder' => function (EntityRepository $er) {
									return $er->createQueryBuilder('u')
											->where('u.dealer is null');
								},
								'required' => false,
								'multiple' => true,
								'label' => 'label.users',));
								*/
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
				->setDefaults(array('data_class' => get_class(new Dealer()),));
	}

	public function getName() {
		return 'aripd_dmsbundle_dealertype';
	}

}
