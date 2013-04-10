<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\StoreBundle\Entity\Modelimage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoreModelimageFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name'));
		$builder
				->add('description', 'text',
						array('label' => 'Description'));
		
		//$builder->add('file', 'file', array('label' => 'label.file'));
		/*
		$builder->add('defaultimage', 'file', array(
		                                    'label' => 'Image',
		                                    'attr' => array(
		                                        'accept' => 'image/*',
		                                        //'multiple' => 'multiple',
		                                    ),
		                                ))
		 */
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Modelimage()),
						'translation_domain' => 'ARIPDStoreBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_storemodelimageformtype';
	}

}
