<?php
namespace ARIPD\AdminBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\Query;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserGroupFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		
		$builder->add('name');
		
		$builder->add('roles', 'choice', array(
				'choices'   => array(
						'ROLE_SUPERADMIN' => 'SUPER ADMIN',
						'ROLE_ADMIN' => 'ADMIN', 
						'ROLE_EDITOR' => 'EDITOR', 
						'ROLE_WRITER' => 'WRITER', 
						'ROLE_USER' => 'USER', 
				),
				'required'  => true,
				'multiple'  => true,
		));
		
		//$builder->add('registrable', 'checkbox', array('required' => false, 'label' => 'label.registrable',));
		$builder->add('approver', 'checkbox', array('required' => false, 'label' => 'label.approver',));
		$builder->add('writer', 'checkbox', array('required' => false, 'label' => 'label.writer',));
		
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'translation_domain' => 'ARIPDUserBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_usergroupformtype';
	}
	
}
