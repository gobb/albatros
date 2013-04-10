<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\AdsBundle\Entity\Advertisement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdsAdvertisementType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name')->add('href');
		$builder->add('banner');
		$builder->add('file', 'file', array('label' => 'label.file'));
		$builder
				->add('tags', 'collection',
						array('type' => new AdsAdvertisementTagType(),
								'allow_add' => true, 'allow_delete' => true,
								'by_reference' => false,));
		$builder
				->add('startingAt', 'datetime',
						array('date_widget' => 'single_text',
								'time_widget' => 'single_text',
								'label' => 'label.startingAt',));
		$builder
				->add('endingAt', 'datetime',
						array('date_widget' => 'single_text',
								'time_widget' => 'single_text',
								'label' => 'label.endingAt',));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Advertisement()),
		));
	}

	public function getName() {
		return 'aripdadmin_adsadvertisementtype';
	}
}
