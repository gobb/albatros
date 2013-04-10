<?php
namespace ARIPD\AdminBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Your name'));
		$builder->add('email', 'email', array('label' => 'E-mail address'));
		$builder->add('subject', 'text', array('label' => 'Subject'));
		$builder->add('body', 'textarea', array('label' => 'Your message'));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'translation_domain' => 'ARIPDAdminBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_contactformtype';
	}
	
}
