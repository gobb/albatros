<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\CRMBundle\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CRMImageFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name'));
		$builder
				->add('description', 'text',
						array('label' => 'Description'));
		$builder->add('file', 'file', array('label' => 'File'));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Image()),
						'translation_domain' => 'ARIPDCRMBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_crmimageformtype';
	}
	
}
