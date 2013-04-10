<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\StoreBundle\Entity\Branch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoreBranchFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name'));
		$builder->add('content', 'textarea', array('label' => 'Content'));
		$builder->add('address', 'text', array('label' => 'Address'));
		$builder->add('city', 'text', array('label' => 'City'));
		$builder->add('state', 'text', array('label' => 'State'));
		$builder->add('zip', 'text', array('label' => 'Zip'));
		$builder->add('phone', 'text', array('label' => 'Phone'));
		$builder->add('latitude', 'text', array('label' => 'Latitude'));
		$builder->add('longitude', 'text', array('label' => 'Longitude'));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Branch()),
						'translation_domain' => 'ARIPDStoreBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_storebranchformtype';
	}
	
}
