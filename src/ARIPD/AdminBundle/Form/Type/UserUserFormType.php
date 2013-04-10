<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\UserBundle\Entity\User;
use ARIPD\DMSBundle\Entity\Dealer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserUserFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('username');
		$builder->add('email', 'email');
		$builder->add('mobile');
		$builder
				->add('dateofbirth', 'birthday',
						array('widget' => 'single_text',
								'label' => 'Date of birth',));
		$builder->add('twitter_username');
		$builder->add('facebookId');
		$builder->add('google');
		$builder->add('firstname');
		$builder->add('lastname');
		$builder
				->add('gender', 'choice',
						array('label' => 'Gender',
								'choices' => array('M' => 'Male',
										'F' => 'Female'),
								'expanded' => true,));
		$builder
				->add('groups', 'entity',
						array('class' => 'ARIPD\UserBundle\Entity\Group',
								'property' => 'name', 'multiple' => true,
								'expanded' => false,));
		/*
		$builder
				->add('dealer', 'entity',
						array('class' => get_class(new Dealer()),
								'property' => 'name', 'multiple' => false,
								'expanded' => false,));
								*/
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new User()),
						'translation_domain' => 'ARIPDUserBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripd_admin_useruserformtype';
	}
	
}
