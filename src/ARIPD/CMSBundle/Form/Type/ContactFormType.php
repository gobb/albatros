<?php
namespace ARIPD\CMSBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Your name'));
		$builder->add('email', 'email', array('label' => 'E-mail address'));
		$builder->add('subject', 'text', array('label' => 'Subject'));
		$builder->add('body', 'textarea', array('label' => 'Your message'));
		$builder->add('captcha', 'captcha', array(
				'label' => 'Security Code',
				'invalid_message' => 'Bad Code Value',
		));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'translation_domain' => 'ARIPDCMSBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdcms_contactformtype';
	}
	
}
