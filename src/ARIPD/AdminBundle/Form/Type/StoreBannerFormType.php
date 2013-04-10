<?php
namespace ARIPD\AdminBundle\Form\Type;
use Doctrine\ORM\EntityRepository;

use ARIPD\AdminBundle\Entity\Iso639;

use ARIPD\StoreBundle\Entity\Banner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoreBannerFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('iso639', 'entity', array(
				'class' => get_class(new Iso639()),
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('i')/*->where('i.active = ?1')->setParameter(1, true)*/;
				},
				'required' => true,
			)
		);
		
		$builder->add('name', 'text', array('label' => 'Name'));
		$builder
				->add('description', 'textarea',
						array('label' => 'Description'));
		$builder->add('href', 'text', array('label' => 'label.href'));
		$builder->add('url', 'text', array('label' => 'label.url'));
		$builder
				->add('startingAt', 'datetime',
						array('date_widget' => 'single_text',
								'time_widget' => 'single_text',
								'label' => 'Starting At',));
		$builder
				->add('endingAt', 'datetime',
						array('date_widget' => 'single_text',
								'time_widget' => 'single_text',
								'label' => 'Ending At',));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Banner()),
						'translation_domain' => 'ARIPDStoreBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_storebannerformtype';
	}

}
