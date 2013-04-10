<?php
namespace ARIPD\CMSBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BulletinFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('emailaddress', 'email', array('label' => 'E-mail address',));
		$builder->add('gender', 'choice', array(
				'label' => 'Gender',
				'choices' => array(
						'M' => 'Male',
						'F' => 'Female'),
				'expanded' => true,
				)
		);
		$builder->add('captcha', 'captcha', array('label' => 'Security Code'));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'translation_domain' => 'ARIPDCMSBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdcms_bulletinformtype';
	}
	
}
