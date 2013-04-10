<?php
namespace ARIPD\UserBundle\Form\Type;
use ARIPD\UserBundle\Entity\User;
use Doctrine\ORM\Query;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileFormType extends BaseType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		parent::buildForm($builder, $options);
	}

	protected function buildUserForm(FormBuilderInterface $builder, array $options) {
		$builder->add('username', null, array('label' => 'Username',));
		$builder
				->add('email', 'email',
						array('label' => 'E-mail Address',));
		$builder->add('motto');
		$builder->add('firstname');
		$builder->add('lastname');
		$builder->add('mobile');
		$builder->add('gender', 'choice', array(
				'label' => 'Gender',
				'choices' => array(
						'M' => 'Male',
						'F' => 'Female'
				),
				'expanded' => true,
		));
		$builder->add('dateofbirth', 'birthday',array(
				'widget' => 'single_text',
				'label' => 'Date of birth',
		));
		$builder->add('twitter_username');
		$builder->add('facebookId');
		$builder->add('google');
		$builder->add('linkedin');
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
		return 'aripduser_profileformtype';
	}
	
}
