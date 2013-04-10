<?php
namespace ARIPD\UserBundle\Form\Type;
use ARIPD\StoreBundle\Entity\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoreModelimageFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name'))
				->add('description', 'text',
						array('label' => 'Description'))
				->add('file', 'file', array('label' => 'label.file'));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Model()),
						'translation_domain' => 'ARIPDStoreBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripduser_storemodelimageformtype';
	}
	
}
