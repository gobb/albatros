<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\AdminBundle\Entity\Logtype;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LogtypeFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('code', 'text', array('required' => true,));
		$builder->add('name', 'text', array('required' => true,));
		$builder->add('bonus', 'number', array('required' => true,));
		$builder->add('sendemail', 'checkbox', array('required' => false,));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Logtype()),
						'translation_domain' => 'ARIPDAdminBundle',
				)
		);
	}

	public function getName() {
		return 'aripd_adminbundle_Logtypeformtype';
	}

}
