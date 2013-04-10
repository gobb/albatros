<?php
namespace ARIPD\UserBundle\Form\Type;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use ARIPD\UserBundle\Entity\Postaladdress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PostaladdressFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name'));
		$builder->add('address', 'text', array('label' => 'Address'));
		$builder->add('county', 'text', array('label' => 'County'));
		$builder->add('city', 'text', array('label' => 'City'));
		$builder->add('postalcode', 'text', array('label' => 'Postal Code'));
		$builder->add('country', 'text', array('label' => 'Country'));
		$builder->add('latitude', 'text', array('label' => 'Latitude'));
		$builder->add('longitude', 'text', array('label' => 'Longitude'));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Postaladdress()),
						'translation_domain' => 'ARIPDUserBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripduser_postaladdressformtype';
	}
	
}
